<?php

namespace App\Repositories;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryRepository {

    /**
     * The inventory repository instance.
     */
    protected $inventory;

    public function __construct(Inventory $inventory) {
        $this->inventory = $inventory;
    }

    /**
     * get inventory records
     * @param object $request instance of the request object
     * @return object
     */
    public function search($id = '') {

        $inventory = $this->inventory->newQuery();

        //filter by id
        if (is_numeric($id)) {
            $inventory->where('inventory_id', $id);
            // For single record, return the query builder without pagination
            $inventory->with(['creator', 'category', 'tags']);
            return $inventory;
        }

        //filter by status
        if (request()->filled('filter_inventory_status')) {
            $inventory->where('inventory_status', request('filter_inventory_status'));
        }

        //filter by category
        if (request()->filled('filter_inventory_categoryid')) {
            $inventory->where('inventory_categoryid', request('filter_inventory_categoryid'));
        }

        //filter by creator
        if (request()->filled('filter_inventory_creatorid')) {
            $inventory->where('inventory_creatorid', request('filter_inventory_creatorid'));
        }

        //filter by quantity
        if (request()->filled('filter_inventory_quantity_min')) {
            $inventory->where('inventory_quantity', '>=', request('filter_inventory_quantity_min'));
        }
        if (request()->filled('filter_inventory_quantity_max')) {
            $inventory->where('inventory_quantity', '<=', request('filter_inventory_quantity_max'));
        }

        //search: multiple inventory fields
        if (request()->filled('search_query')) {
            $inventory->where(function ($query) {
                $query->where('inventory_name', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('inventory_description', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('inventory_sku', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('inventory_barcode', 'LIKE', '%' . request('search_query') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request()->filled('orderby')) {
            $inventory->orderBy(request('orderby'), request('sortorder'));
        } else {
            $inventory->orderBy('created_at', 'desc');
        }

        //eager load
        $inventory->with([
            'creator',
            'category',
            'tags',
        ]);

        //return paginated results
        return $inventory->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * create a new record
     * @param int $id optional id of the record
     * @return mixed int|bool
     */
    public function create($id = '') {

        //save new inventory
        $inventory = new $this->inventory;

        //data
        $inventory->inventory_name = request('inventory_name');
        $inventory->inventory_description = request('inventory_description');
        $inventory->inventory_sku = request('inventory_sku');
        $inventory->inventory_barcode = request('inventory_barcode');
        $inventory->inventory_quantity = request('inventory_quantity');
        $inventory->inventory_minimum_quantity = request('inventory_minimum_quantity');
        $inventory->inventory_maximum_quantity = request('inventory_maximum_quantity');
        $inventory->inventory_cost_price = request('inventory_cost_price');
        $inventory->inventory_selling_price = request('inventory_selling_price');
        $inventory->inventory_currency = request('inventory_currency');
        $inventory->inventory_unit = request('inventory_unit');
        $inventory->inventory_status = request('inventory_status');
        $inventory->inventory_creatorid = auth()->id();
        $inventory->inventory_categoryid = request('inventory_categoryid');
        $inventory->inventory_supplier = request('inventory_supplier');
        $inventory->inventory_notes = request('inventory_notes');
        $inventory->inventory_location = request('inventory_location');
        $inventory->inventory_last_restocked = request('inventory_last_restocked');
        $inventory->inventory_expiry_date = request('inventory_expiry_date');

        //save and return id
        if ($inventory->save()) {
            return $inventory->inventory_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[InventoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id) {

        //get the record
        if (!$inventory = $this->inventory->find($id)) {
            return false;
        }

        //general
        $inventory->inventory_name = request('inventory_name');
        $inventory->inventory_description = request('inventory_description');
        $inventory->inventory_sku = request('inventory_sku');
        $inventory->inventory_barcode = request('inventory_barcode');
        $inventory->inventory_quantity = request('inventory_quantity');
        $inventory->inventory_minimum_quantity = request('inventory_minimum_quantity');
        $inventory->inventory_maximum_quantity = request('inventory_maximum_quantity');
        $inventory->inventory_cost_price = request('inventory_cost_price');
        $inventory->inventory_selling_price = request('inventory_selling_price');
        $inventory->inventory_currency = request('inventory_currency');
        $inventory->inventory_unit = request('inventory_unit');
        $inventory->inventory_status = request('inventory_status');
        $inventory->inventory_categoryid = request('inventory_categoryid');
        $inventory->inventory_supplier = request('inventory_supplier');
        $inventory->inventory_notes = request('inventory_notes');
        $inventory->inventory_location = request('inventory_location');
        $inventory->inventory_last_restocked = request('inventory_last_restocked');
        $inventory->inventory_expiry_date = request('inventory_expiry_date');

        //save
        if ($inventory->save()) {
            return $inventory->inventory_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[InventoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

}
