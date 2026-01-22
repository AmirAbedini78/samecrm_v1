<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\Sales;
use App\Models\InventoryExpiryDate;
use App\Models\InventoryTransaction;
use App\Traits\InventoryFilterTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReportRepository
{
    use InventoryFilterTrait;
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

}

