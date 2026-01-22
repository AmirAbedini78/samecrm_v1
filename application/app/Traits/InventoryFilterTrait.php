<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait InventoryFilterTrait
{
    /**
     * Apply inventory filters to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @param string|null $dateColumn
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyInventoryFilters($query, $filters = [], $dateColumn = null)
    {
        if (!empty($filters['category_id'])) {
            $query->where('inventory_categoryid', $filters['category_id']);
        }

        if (!empty($filters['custom_category_id'])) {
            $query->whereHas('customCategories', function ($q) use ($filters) {
                $q->where('category_id', $filters['custom_category_id']);
            });
        }

        if (!empty($filters['custom_category_alias'])) {
            $query->whereHas('customCategories', function ($q) use ($filters) {
                $q->where('alias', $filters['custom_category_alias']);
            });
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('inventory_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('inventory_code', 'LIKE', '%' . $search . '%');
            });
        }

        [$from, $to] = $this->resolveDateBounds($filters);
        if ($dateColumn) {
            $column = $dateColumn === 'entry_date'
                ? DB::raw('COALESCE(entry_date, created_at)')
                : $dateColumn;
            if ($from) {
                $query->whereDate($column, '>=', $from);
            }
            if ($to) {
                $query->whereDate($column, '<=', $to);
            }
        }

        if (!empty($filters['status_filter'])) {
            $alertDays = config('inventory.default_alert_days_before', 7);
            if ($filters['status_filter'] === 'expired') {
                $query->whereHas('expiryDate', function ($q) {
                    $q->where('is_expired', true);
                });
            } elseif ($filters['status_filter'] === 'expiring') {
                $query->whereHas('expiryDate', function ($q) use ($alertDays) {
                    $q->where('is_expired', false)
                        ->whereNotNull('expiry_date')
                        ->whereDate('expiry_date', '<=', Carbon::now()->addDays($alertDays));
                });
            } elseif ($filters['status_filter'] === 'negative') {
                $query->where('current_quantity', '<', 0);
            }
        }

        if (!empty($filters['flags']) && is_array($filters['flags'])) {
            $alertDays = config('inventory.default_alert_days_before', 7);
            foreach ($filters['flags'] as $flag) {
                switch ($flag) {
                    case 'low-stock':
                        $query->whereColumn('current_quantity', '<=', 'minimum_stock');
                        break;
                    case 'negative':
                        $query->where('current_quantity', '<', 0);
                        break;
                    case 'critical':
                        $query->where(function ($critical) use ($alertDays) {
                            $critical->whereColumn('current_quantity', '<=', 'minimum_stock')
                                ->orWhereHas('expiryDate', function ($exp) use ($alertDays) {
                                    $exp->where('is_expired', false)
                                        ->whereNotNull('expiry_date')
                                        ->whereDate('expiry_date', '<=', Carbon::now()->addDays($alertDays));
                                });
                        });
                        break;
                    case 'high-value':
                        $query->where('current_amount', '>=', config('inventory.high_value_threshold', 500000000));
                        break;
                    case 'fast-move':
                        $query->whereColumn('current_quantity', '<=', DB::raw('GREATEST(minimum_stock, 1) * 1.2'));
                        break;
                    case 'slow-move':
                        $query->whereDate(DB::raw('COALESCE(entry_date, created_at)'), '<=', Carbon::now()->subDays(120));
                        break;
                    case 'near-expiry':
                        $query->whereHas('expiryDate', function ($exp) use ($alertDays) {
                            $exp->where('is_expired', false)
                                ->whereNotNull('expiry_date')
                                ->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays($alertDays)]);
                        });
                        break;
                }
            }
        }

        return $query;
    }

    /**
     * Resolve date bounds from filters
     *
     * @param array $filters
     * @return array
     */
    protected function resolveDateBounds($filters = [])
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

    /**
     * Parse date value
     *
     * @param mixed $value
     * @return string|null
     */
    protected function parseDateValue($value)
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

