<?php

namespace App\Repositories;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        //search: general search
        if (request()->filled('search')) {
            $sales->where(function ($query) {
                $query->where('document_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('product_name', 'LIKE', '%' . request('search') . '%');
            });
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
        if (in_array(request('sortorder'), array('desc', 'asc')) && request()->filled('orderby')) {
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
}