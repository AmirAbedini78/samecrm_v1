<?php

namespace App\Repositories;

use App\Models\InvoiceSettlement;
use Illuminate\Support\Facades\Schema;

class InvoiceSettlementRepository
{
    protected $model;

    public function __construct(InvoiceSettlement $model)
    {
        $this->model = $model;
    }

    public function search($id = '')
    {
        $query = $this->model->newQuery();

        if (is_numeric($id)) {
            $query->where('invoice_settlement_id', $id)->with('creator');
            return $query;
        }

        if ($search = request('search_query')) {
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $search . '%');
            });
        }

        $columnSearch = request('column_search', []);
        if (is_array($columnSearch)) {
            foreach ($columnSearch as $column => $value) {
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        if ($value = request('filter_document_number')) {
            $query->where('document_number', 'LIKE', '%' . $value . '%');
        }

        if ($value = request('filter_customer_name')) {
            $query->where('customer_name', 'LIKE', '%' . $value . '%');
        }

        $this->applyRangeFilter($query, 'base_net_amount', request('filter_base_net_amount_min'), request('filter_base_net_amount_max'));
        $this->applyRangeFilter($query, 'paid_amount', request('filter_paid_amount_min'), request('filter_paid_amount_max'));
        $this->applyRangeFilter($query, 'balance_amount', request('filter_balance_amount_min'), request('filter_balance_amount_max'));

        if ($value = request('filter_document_date_start')) {
            $query->where('document_date', '>=', $value);
        }

        if ($value = request('filter_document_date_end')) {
            $query->where('document_date', '<=', $value);
        }

        if ($currencies = request('filter_currency')) {
            $query->whereIn('currency', (array) $currencies);
        }

        if (in_array(request('sortorder'), ['asc', 'desc']) && Schema::hasColumn('invoice_settlements', request('orderby'))) {
            $query->orderBy(request('orderby'), request('sortorder'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $query->with('creator');

        return $query->paginate(config('system.settings_system_pagination_limits'));
    }

    public function getUniqueValues($column)
    {
        if (!Schema::hasColumn('invoice_settlements', $column)) {
            return [];
        }

        return $this->model->select($column)
            ->distinct()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->pluck($column)
            ->toArray();
    }

    public function calculateStats()
    {
        $query = $this->model->newQuery();
        $query = $this->applySharedFilters($query);

        $stats = $query->selectRaw('
            COUNT(*) as total_records,
            COALESCE(SUM(base_net_amount),0) as total_net,
            COALESCE(SUM(paid_amount),0) as total_paid,
            COALESCE(SUM(balance_amount),0) as total_balance
        ')->first();

        return [
            'total_records' => (int) $stats->total_records,
            'total_net' => (float) $stats->total_net,
            'total_paid' => (float) $stats->total_paid,
            'total_balance' => (float) $stats->total_balance,
        ];
    }

    protected function applySharedFilters($query)
    {
        if ($search = request('search_query')) {
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $search . '%');
            });
        }

        $columnSearch = request('column_search', []);
        if (is_array($columnSearch)) {
            foreach ($columnSearch as $column => $value) {
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        if ($value = request('filter_document_number')) {
            $query->where('document_number', 'LIKE', '%' . $value . '%');
        }

        if ($value = request('filter_customer_name')) {
            $query->where('customer_name', 'LIKE', '%' . $value . '%');
        }

        $this->applyRangeFilter($query, 'base_net_amount', request('filter_base_net_amount_min'), request('filter_base_net_amount_max'));
        $this->applyRangeFilter($query, 'paid_amount', request('filter_paid_amount_min'), request('filter_paid_amount_max'));
        $this->applyRangeFilter($query, 'balance_amount', request('filter_balance_amount_min'), request('filter_balance_amount_max'));

        if ($value = request('filter_document_date_start')) {
            $query->where('document_date', '>=', $value);
        }

        if ($value = request('filter_document_date_end')) {
            $query->where('document_date', '<=', $value);
        }

        if ($currencies = request('filter_currency')) {
            $query->whereIn('currency', (array) $currencies);
        }

        return $query;
    }

    protected function applyColumnSearch($query, $column, $value)
    {
        if ($value === null || $value === '') {
            return;
        }

        $value = urldecode($value);

        if (!Schema::hasColumn('invoice_settlements', $column) && $column !== 'creator') {
            return;
        }

        if ($column === 'creator') {
            $query->whereHas('creator', function ($q) use ($value) {
                $q->where('first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $value . '%');
            });
            return;
        }

        $query->where($column, 'LIKE', '%' . $value . '%');
    }

    protected function applyRangeFilter($query, $column, $min, $max)
    {
        if ($min !== null && $min !== '') {
            $query->where($column, '>=', $this->toDecimal($min));
        }

        if ($max !== null && $max !== '') {
            $query->where($column, '<=', $this->toDecimal($max));
        }
    }

    protected function toDecimal($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = preg_replace('/[^0-9E\.\-]/', '', (string) $value);
        return (float) $value;
    }
}

