<?php

namespace App\Repositories;

use App\Models\BelzonaInventory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BelzonaInventoryRepository
{
    /**
     * The inventory repository instance.
     */
    protected $belzonaInventory;

    public function __construct(BelzonaInventory $belzonaInventory)
    {
        $this->belzonaInventory = $belzonaInventory;
    }

    /**
     * Get belzona inventory records (supports simple searching).
     *
     * @param mixed $id
     * @return mixed
     */
    public function search($id = '')
    {
        $query = $this->belzonaInventory->newQuery();

        // single record
        if (is_numeric($id)) {
            return $query->where('belzona_inventory_id', $id);
        }

        // basic search
        if (request()->filled('search_query')) {
            $term = request('search_query');
            $query->where(function ($q) use ($term) {
                $q->where('product_name', 'LIKE', "%{$term}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$term}%")
                    ->orWhere('customer_name', 'LIKE', "%{$term}%");
            });
        }

        // DataTables global search support
        if (request()->filled('search') && is_array(request('search')) && !empty(request('search')['value'])) {
            $term = request('search')['value'];
            $query->where(function ($q) use ($term) {
                $q->where('product_name', 'LIKE', "%{$term}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$term}%")
                    ->orWhere('customer_name', 'LIKE', "%{$term}%");
            });
        }

        // sorting
        if (in_array(request('sortorder'), ['desc', 'asc']) && request('orderby') != '') {
            $query->orderBy(request('orderby'), request('sortorder'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Create a new belzona inventory record.
     */
    public function create()
    {
        try {
            $item = new $this->belzonaInventory;

            $item->product_name = request('product_name');
            $item->date = request('date') ?: null;
            $item->input = $this->parseDecimal(request('input', 0));
            $item->output = $this->parseDecimal(request('output', 0));
            $item->balance = $this->parseDecimal(request('balance', 0));
            $item->invoice_number = request('invoice_number');
            $item->customer_name = request('customer_name');

            if ($item->save()) {
                return $item->belzona_inventory_id;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("BelzonaInventory create failed: " . $e->getMessage(), ['process' => '[BelzonaInventoryRepository]']);
            return false;
        }
    }

    /**
     * Update an existing belzona inventory record.
     */
    public function update($id)
    {
        try {
            if (!$item = $this->belzonaInventory->find($id)) {
                return false;
            }

            $item->product_name = request('product_name');
            $item->date = request('date') ?: null;
            $item->input = $this->parseDecimal(request('input', 0));
            $item->output = $this->parseDecimal(request('output', 0));
            $item->balance = $this->parseDecimal(request('balance', 0));
            $item->invoice_number = request('invoice_number');
            $item->customer_name = request('customer_name');

            return (bool) $item->save();
        } catch (\Exception $e) {
            Log::error("BelzonaInventory update failed: " . $e->getMessage(), ['process' => '[BelzonaInventoryRepository]']);
            return false;
        }
    }

    /**
     * Get unique values for a specific column.
     */
    public function getUniqueValues($column)
    {
        $model = new BelzonaInventory();

        if (!Schema::hasColumn('belzona_inventories', $column)) {
            return [];
        }

        $values = $model->select($column)
            ->distinct()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->pluck($column)
            ->toArray();

        sort($values);

        return array_values(array_unique($values));
    }

    private function parseDecimal($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = preg_replace('/[^0-9.-]/', '', (string) $value);
        return (float) $value;
    }
}

