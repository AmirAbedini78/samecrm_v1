<?php

namespace App\Repositories;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SalesRepository {

    /**
     * The sales repository instance.
     */
    protected $sales;

    public function __construct(Sales $sales) {
        $this->sales = $sales;
    }

    /**
     * get sales records
     * @param object $request instance of the request object
     * @return object
     */
    public function search($id = '') {

        $sales = $this->sales->newQuery();

        //filter by id
        if (is_numeric($id)) {
            $sales->where('sales_id', $id);
            // For single record, return the query builder without pagination
            $sales->with(['creator', 'tags']);
            return $sales;
        }

        //filter by status
        if (request()->filled('filter_sales_status')) {
            $sales->where('sales_status', request('filter_sales_status'));
        }

        //filter by creator
        if (request()->filled('filter_sales_creatorid')) {
            $sales->where('sales_creatorid', request('filter_sales_creatorid'));
        }

        //filter by date
        if (request()->filled('filter_document_date_from')) {
            $sales->where('document_date', '>=', request('filter_document_date_from'));
        }
        if (request()->filled('filter_document_date_to')) {
            $sales->where('document_date', '<=', request('filter_document_date_to'));
        }

        //search: multiple sales fields
        if (request()->filled('search_query')) {
            $sales->where(function ($query) {
                $query->where('product_name', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('document_number', 'LIKE', '%' . request('search_query') . '%');
            });
        }
        
        //search: general search (support DataTables format: search[value])
        $globalSearch = request('search');
        if (is_array($globalSearch)) {
            $globalSearch = $globalSearch['value'] ?? '';
        }
        if (!empty($globalSearch)) {
            $sales->where(function ($query) use ($globalSearch) {
                $query->where('document_number', 'LIKE', '%' . $globalSearch . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $globalSearch . '%')
                    ->orWhere('product_name', 'LIKE', '%' . $globalSearch . '%');
            });
        }
        
        //column-specific search
        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                
                // Only apply search if value is not empty
                if (!empty($value)) {
                    // Decode URL encoded values
                    $value = urldecode($value);
                    
                    // Handle different column types
                    switch ($column) {
                        case 'creator':
                            $sales->whereHas('creator', function ($query) use ($value) {
                                $query->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        case 'tags':
                            $sales->whereHas('tags', function ($query) use ($value) {
                                $query->where('tag_title', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        default:
                            // Direct column search
                            if (Schema::hasColumn('sales', $column)) {
                                $sales->where($column, 'LIKE', '%' . $value . '%');
                            }
                            break;
                    }
                }
            }
        }
        
        //filter by sales status
        if (request()->filled('filter_sales_status')) {
            $sales->where('sales_status', request('filter_sales_status'));
        }
        
        //filter by document type
        if (request()->filled('filter_document_type')) {
            $sales->where('document_type', request('filter_document_type'));
        }
        
        //filter by date from
        if (request()->filled('filter_document_date_from')) {
            $sales->where('document_date', '>=', request('filter_document_date_from'));
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            $sales->orderBy(request('orderby'), request('sortorder'));
        } else {
            $sales->orderBy('created_at', 'desc');
        }

        //eager load
        $sales->with([
            'creator',
            'tags',
        ]);

        //return paginated results
        return $sales->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * create a new record
     * @param int $id optional id of the record
     * @return mixed int|bool
     */
    public function create($id = '') {

        //save new sales
        $sales = new $this->sales;

        //data
        $sales->document_type = request('document_type', 'sale');
        $sales->document_number = request('document_number');
        $sales->document_date = request('document_date');
        
        // Customer Information
        $sales->customer_code = request('customer_code');
        $sales->customer_name = request('customer_name');
        $sales->customer_full_name = request('customer_full_name');
        $sales->sales_type = request('sales_type', 'sale');
        
        // Product/Service Information
        $sales->product_code = request('product_code');
        $sales->product_name = request('product_name');
        $sales->product_barcode = request('product_barcode');
        $sales->tracking_code = request('tracking_code');
        $sales->main_unit = request('main_unit', 'pcs');
        $sales->main_quantity = request('main_quantity', 0);
        $sales->warehouse = request('warehouse');
        
        // Pricing (Base Currency)
        $sales->base_price = request('base_price', 0);
        $sales->base_sales_amount = request('base_sales_amount', 0);
        $sales->base_tax_amount = request('base_tax_amount', 0);
        $sales->base_duty_amount = request('base_duty_amount', 0);
        $sales->base_additional_amount = request('base_additional_amount', 0);
        $sales->base_increasing_factors = request('base_increasing_factors', 0);
        $sales->base_net_amount = request('base_net_amount', 0);
        
        // Additional Information
        $sales->month = request('month');
        $sales->description = request('description');
        
        // Quantities
        $sales->issued_main_quantity = request('issued_main_quantity', 0);
        $sales->issued_sub_quantity = request('issued_sub_quantity', 0);
        $sales->remaining_main_quantity = request('remaining_main_quantity', 0);
        $sales->remaining_sub_quantity = request('remaining_sub_quantity', 0);
        
        // Currency
        $sales->currency = request('currency', 'IRR');
        
        // System
        $sales->sales_status = request('sales_status', 'pending');
        $sales->sales_creatorid = auth()->id();

        //save and return id
        if ($sales->save()) {
            return $sales->sales_id;
        } else {
            Log::error("record could not be created - database error", ['process' => '[SalesRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
        if (!$sales = $this->sales->find($id)) {
            Log::error("record could not be found - database error", ['process' => '[SalesRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //data
        $sales->document_type = request('document_type', 'sale');
        $sales->document_number = request('document_number');
        $sales->document_date = request('document_date');
        
        // Customer Information
        $sales->customer_code = request('customer_code');
        $sales->customer_name = request('customer_name');
        $sales->customer_full_name = request('customer_full_name');
        $sales->sales_type = request('sales_type', 'sale');
        
        // Product/Service Information
        $sales->product_code = request('product_code');
        $sales->product_name = request('product_name');
        $sales->product_barcode = request('product_barcode');
        $sales->tracking_code = request('tracking_code');
        $sales->main_unit = request('main_unit', 'pcs');
        $sales->main_quantity = request('main_quantity', 0);
        $sales->warehouse = request('warehouse');
        
        // Pricing (Base Currency)
        $sales->base_price = request('base_price', 0);
        $sales->base_sales_amount = request('base_sales_amount', 0);
        $sales->base_tax_amount = request('base_tax_amount', 0);
        $sales->base_duty_amount = request('base_duty_amount', 0);
        $sales->base_additional_amount = request('base_additional_amount', 0);
        $sales->base_increasing_factors = request('base_increasing_factors', 0);
        $sales->base_net_amount = request('base_net_amount', 0);
        
        // Additional Information
        $sales->month = request('month');
        $sales->description = request('description');
        
        // Quantities
        $sales->issued_main_quantity = request('issued_main_quantity', 0);
        $sales->issued_sub_quantity = request('issued_sub_quantity', 0);
        $sales->remaining_main_quantity = request('remaining_main_quantity', 0);
        $sales->remaining_sub_quantity = request('remaining_sub_quantity', 0);
        
        // Currency
        $sales->currency = request('currency', 'IRR');
        
        // System
        $sales->sales_status = request('sales_status', 'pending');

        //save and return id
        if ($sales->save()) {
            return $sales->sales_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[SalesRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * Get unique values for a specific column
     * @param string $column
     * @return array
     */
    public function getUniqueValues($column) {
        $sales = new Sales();
        
        // Handle different column types
        switch ($column) {
            case 'creator':
                $values = $sales->join('users', 'sales.sales_creatorid', '=', 'users.id')
                    ->select('users.first_name', 'users.last_name')
                    ->distinct()
                    ->whereNotNull('users.first_name')
                    ->where('users.first_name', '!=', '')
                    ->get()
                    ->map(function($user) {
                        return $user->first_name . ' ' . $user->last_name;
                    })
                    ->toArray();
                break;
            default:
                // Direct column search
                if (Schema::hasColumn('sales', $column)) {
                    $values = $sales->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->toArray();
                } else {
                    $values = [];
                }
                break;
        }
        
        // Sort values and return
        sort($values);
        return array_values(array_unique($values));
    }

    /**
     * Calculate stats for filtered sales
     * @return array
     */
    public function calculateStats() {
        $sales = $this->sales->newQuery();
        
        // Apply same filters as search method
        if (request()->filled('filter_sales_status')) {
            $sales->where('sales_status', request('filter_sales_status'));
        }
        
        if (request()->filled('filter_sales_creatorid')) {
            $sales->where('sales_creatorid', request('filter_sales_creatorid'));
        }
        
        if (request()->filled('filter_document_date_from')) {
            $sales->where('document_date', '>=', request('filter_document_date_from'));
        }
        if (request()->filled('filter_document_date_to')) {
            $sales->where('document_date', '<=', request('filter_document_date_to'));
        }
        
        if (request()->filled('search_query')) {
            $sales->where(function ($query) {
                $query->where('product_name', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('document_number', 'LIKE', '%' . request('search_query') . '%');
            });
        }
        
        // Apply column-specific filters
        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                
                if (!empty($value)) {
                    // Decode URL encoded values
                    $value = urldecode($value);
                    
                    switch ($column) {
                        case 'creator':
                            $sales->whereHas('creator', function ($query) use ($value) {
                                $query->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        case 'tags':
                            $sales->whereHas('tags', function ($query) use ($value) {
                                $query->where('tag_title', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        default:
                            if (Schema::hasColumn('sales', $column)) {
                                $sales->where($column, 'LIKE', '%' . $value . '%');
                            }
                            break;
                    }
                }
            }
        }
        
        // Debug: Log the query (only in debug mode)
        if (config('app.debug')) {
            \Log::info('Sales stats query: ' . $sales->toSql());
            \Log::info('Sales stats bindings: ' . json_encode($sales->getBindings()));
        }
        
        // Get stats in one query to optimize performance
        $stats = $sales->selectRaw('
            COALESCE(SUM(base_sales_amount), 0) as total_sales_amount
        ')->first();
        
        return [
            'total_sales_amount' => (float) $stats->total_sales_amount,
        ];
    }
}