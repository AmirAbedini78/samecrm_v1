<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\InventoryExpiryDate;
use App\Models\Sales;
use App\Traits\InventoryFilterTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReportService
{
    use InventoryFilterTrait;
    /**
     * Get current stock report
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCurrentStock($filters = [])
    {
        $query = Inventory::with(['expiryDate', 'customCategories', 'category'])
            ->where('inventory_status', 'active');

        $this->applyInventoryFilters($query, $filters);

        if (isset($filters['warehouse']) && $filters['warehouse']) {
            $query->where('warehouse', 'LIKE', '%' . $filters['warehouse'] . '%');
        }

        if (isset($filters['physical_available'])) {
            $query->where('physical_available', $filters['physical_available']);
        }

        if (isset($filters['min_quantity'])) {
            $query->where('current_quantity', '>=', $filters['min_quantity']);
        }

        if (isset($filters['max_quantity'])) {
            $query->where('current_quantity', '<=', $filters['max_quantity']);
        }

        return $query->orderBy('inventory_name')->get();
    }

    /**
     * Get expiry report
     *
     * @param array $filters
     * @return array
     */
    public function getExpiryReport($filters = [])
    {
        $query = InventoryExpiryDate::with('inventory')
            ->whereNotNull('expiry_date');

        $query->whereHas('inventory', function ($inventoryQuery) use ($filters) {
            $this->applyInventoryFilters($inventoryQuery, $filters);
        });

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('expiry_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('expiry_date', '<=', $to);
        }

            // Filter by expiry status
        if (isset($filters['status'])) {
            if ($filters['status'] === 'expired') {
                $query->where('is_expired', true);
            } elseif ($filters['status'] === 'approaching') {
                $query->where('is_expired', false)
                      ->whereRaw('DATEDIFF(expiry_date, CURDATE()) <= alert_days_before');
            } elseif ($filters['status'] === 'normal') {
                $query->where('is_expired', false)
                      ->whereRaw('DATEDIFF(expiry_date, CURDATE()) > alert_days_before');
            }
        }

        $expiryDates = $query->get();

        $report = [
            'expired' => [],
            'approaching' => [],
            'normal' => [],
            'summary' => [
                'total' => $expiryDates->count(),
                'expired_count' => 0,
                'approaching_count' => 0,
                'normal_count' => 0
            ]
        ];

        foreach ($expiryDates as $expiry) {
            $daysUntil = $expiry->days_until_expiry;
            $item = [
                'inventory' => $expiry->inventory,
                'expiry_date' => $expiry->expiry_date,
                'days_until_expiry' => $daysUntil,
                'alert_days_before' => $expiry->alert_days_before
            ];

            if ($daysUntil < 0) {
                $report['expired'][] = $item;
                $report['summary']['expired_count']++;
            } elseif ($daysUntil <= $expiry->alert_days_before) {
                $report['approaching'][] = $item;
                $report['summary']['approaching_count']++;
            } else {
                $report['normal'][] = $item;
                $report['summary']['normal_count']++;
            }
        }

        return $report;
    }

    /**
     * Get sales report for inventory items
     *
     * @param array $filters
     * @return array
     */
    public function getSalesReport($filters = [])
    {
        $query = Sales::query();

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('document_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('document_date', '<=', $to);
        }

        // Product filter
        if (isset($filters['product_code']) && $filters['product_code']) {
            $query->where('product_code', $filters['product_code']);
        }

        // Warehouse filter
        if (isset($filters['warehouse']) && $filters['warehouse']) {
            $query->where('warehouse', 'LIKE', '%' . $filters['warehouse'] . '%');
        }

        // Group by product
        $salesData = $query->selectRaw('
                product_code,
                product_name,
                SUM(main_quantity) as total_quantity,
                SUM(base_sales_amount) as total_amount,
                COUNT(*) as sales_count,
                AVG(base_price) as avg_price,
                MAX(main_unit) as unit
            ')
            ->groupBy('product_code', 'product_name')
            ->orderByDesc('total_amount')
            ->get();

        return $salesData;
    }

    /**
     * Get outside inventory report (negative stock or not physically available)
     *
     * @param array $filters
     * @return array
     */
    public function getOutsideInventory($filters = [])
    {
        $baseQuery = Inventory::where('inventory_status', 'active');
        $this->applyInventoryFilters($baseQuery, $filters);

        $negativeStock = (clone $baseQuery)
            ->where('current_quantity', '<', 0)
            ->get();

        $notPhysical = (clone $baseQuery)
            ->where('physical_available', false)
            ->get();

        $discrepancy = (clone $baseQuery)
            ->where('physical_available', false)
            ->where('current_quantity', '>', 0)
            ->get();

        return [
            'negative_stock' => $negativeStock,
            'not_physical' => $notPhysical,
            'discrepancy' => $discrepancy,
            'summary' => [
                'negative_count' => $negativeStock->count(),
                'not_physical_count' => $notPhysical->count(),
                'discrepancy_count' => $discrepancy->count()
            ]
        ];
    }

    /**
     * Get top selling products
     *
     * @param array $filters
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProducts($filters = [], $limit = 10)
    {
        $query = Sales::query();

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('document_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('document_date', '<=', $to);
        }

        // Year filter
        if (isset($filters['year']) && $filters['year']) {
            $query->whereYear('document_date', $filters['year']);
        }

        return $query->selectRaw('
                product_code,
                product_name,
                SUM(main_quantity) as total_quantity,
                SUM(base_sales_amount) as total_amount,
                COUNT(*) as sales_count,
                AVG(base_price) as avg_price,
                MAX(main_unit) as unit
            ')
            ->groupBy('product_code', 'product_name')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get();
    }

    /**
     * Get analytics data
     *
     * @param array $filters
     * @return array
     */
    public function getAnalytics($filters = [])
    {
        $baseInventory = Inventory::where('inventory_status', 'active');
        $this->applyInventoryFilters($baseInventory, $filters);

        $totalValue = (clone $baseInventory)->sum('current_amount');
        $totalItems = (clone $baseInventory)->count();
        $lowStockCount = (clone $baseInventory)->whereColumn('current_quantity', '<=', 'minimum_stock')->count();

        $alertDays = config('inventory.default_alert_days_before', 7);
        $expiryBase = InventoryExpiryDate::whereNotNull('expiry_date')
            ->whereHas('inventory', function ($q) use ($filters) {
                $this->applyInventoryFilters($q, $filters);
            });

        $approachingExpiryCount = (clone $expiryBase)
            ->where('is_expired', false)
            ->whereDate('expiry_date', '<=', Carbon::now()->addDays($alertDays))
            ->count();

        $expiredCount = (clone $expiryBase)
            ->where(function ($q) {
                $q->where('is_expired', true)
                    ->orWhereDate('expiry_date', '<', Carbon::today());
            })->count();

        $monthlyTrend = $this->getMonthlySalesTrend($filters);
        $categoryDistribution = $this->getCategoryDistribution($filters);
        $warehouseDistribution = $this->getWarehouseDistribution($filters);

        return [
            'summary' => [
                'total_value' => $totalValue,
                'total_items' => $totalItems,
                'low_stock_count' => $lowStockCount,
                'approaching_expiry_count' => $approachingExpiryCount,
                'expired_count' => $expiredCount
            ],
            'monthly_trend' => $monthlyTrend,
            'category_distribution' => $categoryDistribution,
            'warehouse_distribution' => $warehouseDistribution,
            'top_products' => $this->getTopSellingProducts($filters, 10),
        ];
    }

    /**
     * Get monthly sales trend
     *
     * @param array $filters
     * @return array
     */
protected function getMonthlySalesTrend($filters = [])
{
    $query = Sales::query();

    [$from, $to] = $this->resolveDateBounds($filters);
    if ($from) {
        $query->whereDate('document_date', '>=', $from);
    }
    if ($to) {
        $query->whereDate('document_date', '<=', $to);
    }

        return $query->selectRaw('
                YEAR(document_date) as year,
                MONTH(document_date) as month,
                SUM(main_quantity) as total_quantity,
                SUM(base_sales_amount) as total_amount,
                COUNT(*) as sales_count
            ')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get category distribution
     *
     * @return array
     */
    protected function getCategoryDistribution($filters = [])
    {
        $query = Inventory::selectRaw('
                inventory_categoryid,
                COUNT(*) as item_count,
                SUM(current_amount) as total_value,
                SUM(current_quantity) as total_quantity
            ')
            ->where('inventory_status', 'active');

        $this->applyInventoryFilters($query, $filters);

        return $query->groupBy('inventory_categoryid')->get();
    }

    /**
     * Get warehouse distribution
     *
     * @return array
     */
    protected function getWarehouseDistribution($filters = [])
    {
        $query = Sales::selectRaw('
                warehouse,
                COUNT(DISTINCT product_code) as product_count,
                SUM(main_quantity) as total_quantity,
                SUM(base_sales_amount) as total_amount
            ')
            ->whereNotNull('warehouse')
            ->where('warehouse', '!=', '');

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('document_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('document_date', '<=', $to);
        }

        return $query->groupBy('warehouse')->get();
    }

    /**
     * Get transactions log
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    /**
     * Get transaction flow report with detailed transaction data
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionFlowReport($filters = [])
    {
        $query = InventoryTransaction::with(['inventory', 'user']);

        if (isset($filters['inventory_id']) && $filters['inventory_id']) {
            $query->where('inventory_id', $filters['inventory_id']);
        }

        if (isset($filters['transaction_type']) && $filters['transaction_type']) {
            $query->where('transaction_type', $filters['transaction_type']);
        }

        if (isset($filters['from_date']) && $filters['from_date']) {
            $query->where('transaction_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date']) && $filters['to_date']) {
            $query->where('transaction_date', '<=', $filters['to_date']);
        }

        if (isset($filters['document_number']) && $filters['document_number']) {
            $query->where('document_number', 'LIKE', '%' . $filters['document_number'] . '%');
        }

        if (isset($filters['base_document_number']) && $filters['base_document_number']) {
            $query->where('base_document_number', 'LIKE', '%' . $filters['base_document_number'] . '%');
        }

        return $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTransactions($filters = [])
    {
        $query = InventoryTransaction::with(['inventory', 'user']);

        if (isset($filters['inventory_id']) && $filters['inventory_id']) {
            $query->where('inventory_id', $filters['inventory_id']);
        }

        if (isset($filters['transaction_type']) && $filters['transaction_type']) {
            $query->where('transaction_type', $filters['transaction_type']);
        }

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('transaction_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        if (isset($filters['warehouse']) && $filters['warehouse']) {
            $query->where('warehouse', 'LIKE', '%' . $filters['warehouse'] . '%');
        }

        if (!empty($filters['flags']) || !empty($filters['category_id']) || !empty($filters['search'])) {
            $query->whereHas('inventory', function ($inventoryQuery) use ($filters) {
                $this->applyInventoryFilters($inventoryQuery, $filters);
            });
        }

        return $query->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->get();
    }

}

