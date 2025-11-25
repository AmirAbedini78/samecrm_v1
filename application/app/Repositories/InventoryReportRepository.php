<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\Sales;
use App\Models\InventoryExpiryDate;
use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReportRepository
{
    /**
     * Get inventory summary statistics
     *
     * @return array
     */
    public function getSummary($filters = [])
    {
        $baseInventory = Inventory::where('inventory_status', 'active');
        $this->applyInventoryFilters($baseInventory, $filters);

        $expiryBase = InventoryExpiryDate::whereNotNull('expiry_date')
            ->whereHas('inventory', function ($q) use ($filters) {
                $this->applyInventoryFilters($q, $filters);
            });

        $alertDays = config('inventory.default_alert_days_before', 7);

        return [
            'total_items' => (clone $baseInventory)->count(),
            'total_value' => (clone $baseInventory)->sum('current_amount'),
            'total_quantity' => (clone $baseInventory)->sum('current_quantity'),
            'low_stock_count' => (clone $baseInventory)
                ->whereColumn('current_quantity', '<=', 'minimum_stock')
                ->count(),
            'expired_count' => (clone $expiryBase)
                ->where(function ($q) {
                    $q->where('is_expired', true)
                        ->orWhereDate('expiry_date', '<', Carbon::today());
                })->count(),
            'approaching_expiry_count' => (clone $expiryBase)
                ->where('is_expired', false)
                ->whereDate('expiry_date', '<=', Carbon::now()->addDays($alertDays))
                ->count(),
        ];
    }

    /**
     * Get inventory by category
     *
     * @return \Illuminate\Support\Collection
     */
    public function getByCategory($filters = [])
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
     * Get inventory movement (input/output) summary
     *
     * @param array $filters
     * @return array
     */
    public function getMovementSummary($filters = [])
    {
        $query = InventoryTransaction::query();

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('transaction_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        $input = (clone $query)->where('transaction_type', 'input')
            ->selectRaw('SUM(quantity) as total_quantity, SUM(amount) as total_amount, COUNT(*) as count')
            ->first();

        $output = (clone $query)->where('transaction_type', 'output')
            ->selectRaw('SUM(quantity) as total_quantity, SUM(amount) as total_amount, COUNT(*) as count')
            ->first();

        return [
            'input' => [
                'quantity' => $input->total_quantity ?? 0,
                'amount' => $input->total_amount ?? 0,
                'count' => $input->count ?? 0
            ],
            'output' => [
                'quantity' => $output->total_quantity ?? 0,
                'amount' => $output->total_amount ?? 0,
                'count' => $output->count ?? 0
            ]
        ];
    }

    /**
     * Get top products by sales
     *
     * @param array $filters
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopProductsBySales($filters = [], $limit = 10)
    {
        $query = Sales::query();

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate('document_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('document_date', '<=', $to);
        }

        if (isset($filters['year']) && $filters['year']) {
            $query->whereYear('document_date', $filters['year']);
        }

        return $query->selectRaw('
                product_code,
                product_name,
                SUM(main_quantity) as total_quantity,
                SUM(base_sales_amount) as total_amount,
                COUNT(*) as sales_count,
                AVG(base_price) as avg_price
            ')
            ->groupBy('product_code', 'product_name')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get();
    }

    /**
     * Get inventory aging report
     *
     * @return array
     */
    public function getAgingReport($filters = [])
    {
        $inventories = Inventory::where('inventory_status', 'active')
            ->whereNotNull('entry_date')
            ->get();

        $aging = [
            '0-30' => [],
            '31-60' => [],
            '61-90' => [],
            '91-180' => [],
            '180+' => []
        ];

        foreach ($inventories as $inventory) {
            $age = \App\Helpers\InventoryHelper::getInventoryAge($inventory);
            
            if ($age === null) {
                continue;
            }

            $item = [
                'inventory' => $inventory,
                'age' => $age
            ];

            if ($age <= 30) {
                $aging['0-30'][] = $item;
            } elseif ($age <= 60) {
                $aging['31-60'][] = $item;
            } elseif ($age <= 90) {
                $aging['61-90'][] = $item;
            } elseif ($age <= 180) {
                $aging['91-180'][] = $item;
            } else {
                $aging['180+'][] = $item;
            }
        }

        return $aging;
    }

    private function applyInventoryFilters($query, $filters = [])
    {
        if (!empty($filters['category_id'])) {
            $query->where('inventory_categoryid', $filters['category_id']);
        }

        if (!empty($filters['custom_category_id'])) {
            $query->whereHas('customCategories', function ($q) use ($filters) {
                $q->where('category_id', $filters['custom_category_id']);
            });
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('inventory_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('inventory_code', 'LIKE', '%' . $search . '%');
            });
        }

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($from) {
            $query->whereDate(DB::raw('COALESCE(entry_date, created_at)'), '>=', $from);
        }
        if ($to) {
            $query->whereDate(DB::raw('COALESCE(entry_date, created_at)'), '<=', $to);
        }
    }

    private function resolveDateBounds($filters = [])
    {
        $from = $this->parseDateValue($filters['from_date'] ?? null);
        $to = $this->parseDateValue($filters['to_date'] ?? null);

        if (!$from && !$to && !empty($filters['quick_range'])) {
            $range = $filters['quick_range'];
            $now = Carbon::today();
            switch ($range) {
                case 'today':
                    $from = $now->copy();
                    $to = $now->copy();
                    break;
                case 'week':
                    $from = $now->copy()->subDays(6);
                    $to = $now->copy();
                    break;
                case 'month':
                    $from = $now->copy()->subDays(29);
                    $to = $now->copy();
                    break;
                case 'quarter':
                    $from = $now->copy()->subDays(89);
                    $to = $now->copy();
                    break;
            }
            $from = $from ? $from->format('Y-m-d') : null;
            $to = $to ? $to->format('Y-m-d') : null;
        }

        return [$from, $to];
    }

    private function parseDateValue($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}

