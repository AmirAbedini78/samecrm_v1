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

        //filter by current quantity
        if (request()->filled('filter_current_quantity_min')) {
            $inventory->where('current_quantity', '>=', request('filter_current_quantity_min'));
        }
        if (request()->filled('filter_current_quantity_max')) {
            $inventory->where('current_quantity', '<=', request('filter_current_quantity_max'));
        }

        //filter by status
        if (request()->filled('filter_inventory_status')) {
            $inventory->where('inventory_status', request('filter_inventory_status'));
        }
        
        //filter by stock level
        if (request()->filled('filter_stock')) {
            if (request('filter_stock') == 'low_stock') {
                $inventory->whereRaw('current_quantity <= minimum_stock');
            } elseif (request('filter_stock') == 'out_of_stock') {
                $inventory->where('current_quantity', '<=', 0);
            }
        }

        //search: multiple inventory fields
        if (request()->filled('search_query')) {
            $inventory->where(function ($query) {
                $query->where('inventory_name', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('inventory_code', 'LIKE', '%' . request('search_query') . '%');
            });
        }
        
        //search: general search
        if (request()->filled('search')) {
            $inventory->where(function ($query) {
                $query->where('inventory_name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('inventory_code', 'LIKE', '%' . request('search') . '%');
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
        $inventory->inventory_code = request('inventory_code');
        $inventory->inventory_name = request('inventory_name');
        
        // First Period
        $inventory->first_period_quantity = request('first_period_quantity', 0);
        $inventory->first_period_sub_quantity = request('first_period_sub_quantity', 0);
        $inventory->first_period_amount = request('first_period_amount', 0);
        $inventory->first_period_avg_price = request('first_period_avg_price', 0);
        
        // Input
        $inventory->input_quantity = request('input_quantity', 0);
        $inventory->input_sub_quantity = request('input_sub_quantity', 0);
        $inventory->input_amount = request('input_amount', 0);
        $inventory->input_avg_price = request('input_avg_price', 0);
        
        // Output
        $inventory->output_quantity = request('output_quantity', 0);
        $inventory->output_sub_quantity = request('output_sub_quantity', 0);
        $inventory->output_amount = request('output_amount', 0);
        $inventory->output_avg_price = request('output_avg_price', 0);
        
        // Current Stock
        $inventory->current_quantity = request('current_quantity', 0);
        $inventory->current_sub_quantity = request('current_sub_quantity', 0);
        $inventory->current_amount = request('current_amount', 0);
        $inventory->current_avg_price = request('current_avg_price', 0);
        
        // Weighing
        $inventory->weighing_input = request('weighing_input', 0);
        $inventory->weighing_output = request('weighing_output', 0);
        
        // Stock Limits
        $inventory->minimum_stock = request('minimum_stock', 0);
        $inventory->maximum_stock = request('maximum_stock');
        $inventory->discrepancy = request('discrepancy', 0);
        
        // Units
        $inventory->main_unit = request('main_unit', 'pcs');
        $inventory->sub_unit = request('sub_unit');
        
        // System
        $inventory->inventory_status = request('inventory_status', 'active');
        $inventory->inventory_creatorid = auth()->id();
        $inventory->inventory_categoryid = request('inventory_categoryid');

        //save and return id
        if ($inventory->save()) {
            return $inventory->inventory_id;
        } else {
            Log::error("record could not be created - database error", ['process' => '[InventoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
            Log::error("record could not be found - database error", ['process' => '[InventoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //data
        $inventory->inventory_code = request('inventory_code');
        $inventory->inventory_name = request('inventory_name');
        
        // First Period
        $inventory->first_period_quantity = request('first_period_quantity', 0);
        $inventory->first_period_sub_quantity = request('first_period_sub_quantity', 0);
        $inventory->first_period_amount = request('first_period_amount', 0);
        $inventory->first_period_avg_price = request('first_period_avg_price', 0);
        
        // Input
        $inventory->input_quantity = request('input_quantity', 0);
        $inventory->input_sub_quantity = request('input_sub_quantity', 0);
        $inventory->input_amount = request('input_amount', 0);
        $inventory->input_avg_price = request('input_avg_price', 0);
        
        // Output
        $inventory->output_quantity = request('output_quantity', 0);
        $inventory->output_sub_quantity = request('output_sub_quantity', 0);
        $inventory->output_amount = request('output_amount', 0);
        $inventory->output_avg_price = request('output_avg_price', 0);
        
        // Current Stock
        $inventory->current_quantity = request('current_quantity', 0);
        $inventory->current_sub_quantity = request('current_sub_quantity', 0);
        $inventory->current_amount = request('current_amount', 0);
        $inventory->current_avg_price = request('current_avg_price', 0);
        
        // Weighing
        $inventory->weighing_input = request('weighing_input', 0);
        $inventory->weighing_output = request('weighing_output', 0);
        
        // Stock Limits
        $inventory->minimum_stock = request('minimum_stock', 0);
        $inventory->maximum_stock = request('maximum_stock');
        $inventory->discrepancy = request('discrepancy', 0);
        
        // Units
        $inventory->main_unit = request('main_unit', 'pcs');
        $inventory->sub_unit = request('sub_unit');
        
        // System
        $inventory->inventory_status = request('inventory_status', 'active');
        $inventory->inventory_categoryid = request('inventory_categoryid');

        //save and return id
        if ($inventory->save()) {
            return $inventory->inventory_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[InventoryRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}