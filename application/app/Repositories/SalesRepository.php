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
            $sales->with(['creator', 'category', 'client', 'project', 'tags']);
            return $sales;
        }

        //filter by status
        if (request()->filled('filter_sales_status')) {
            $sales->where('sales_status', request('filter_sales_status'));
        }

        //filter by type
        if (request()->filled('filter_sales_type')) {
            $sales->where('sales_type', request('filter_sales_type'));
        }

        //filter by payment status
        if (request()->filled('filter_sales_payment_status')) {
            $sales->where('sales_payment_status', request('filter_sales_payment_status'));
        }

        //filter by category
        if (request()->filled('filter_sales_categoryid')) {
            $sales->where('sales_categoryid', request('filter_sales_categoryid'));
        }

        //filter by client
        if (request()->filled('filter_sales_clientid')) {
            $sales->where('sales_clientid', request('filter_sales_clientid'));
        }

        //filter by project
        if (request()->filled('filter_sales_projectid')) {
            $sales->where('sales_projectid', request('filter_sales_projectid'));
        }

        //filter by creator
        if (request()->filled('filter_sales_creatorid')) {
            $sales->where('sales_creatorid', request('filter_sales_creatorid'));
        }

        //filter by date
        if (request()->filled('filter_sales_date_from')) {
            $sales->where('sales_date', '>=', request('filter_sales_date_from'));
        }
        if (request()->filled('filter_sales_date_to')) {
            $sales->where('sales_date', '<=', request('filter_sales_date_to'));
        }

        //search: multiple sales fields
        if (request()->filled('search_query')) {
            $sales->where(function ($query) {
                $query->where('sales_title', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('sales_description', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('sales_reference', 'LIKE', '%' . request('search_query') . '%');
            });
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
            'category',
            'client',
            'project',
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
        $sales->sales_title = request('sales_title');
        $sales->sales_description = request('sales_description');
        $sales->sales_type = request('sales_type');
        $sales->sales_quantity = request('sales_quantity');
        $sales->sales_unit_price = request('sales_unit_price');
        $sales->sales_total_amount = request('sales_total_amount');
        $sales->sales_discount_amount = request('sales_discount_amount');
        $sales->sales_tax_amount = request('sales_tax_amount');
        $sales->sales_final_amount = request('sales_final_amount');
        $sales->sales_currency = request('sales_currency');
        $sales->sales_status = request('sales_status');
        $sales->sales_payment_status = request('sales_payment_status');
        $sales->sales_payment_method = request('sales_payment_method');
        $sales->sales_date = request('sales_date');
        $sales->sales_due_date = request('sales_due_date');
        $sales->sales_creatorid = auth()->id();
        $sales->sales_clientid = request('sales_clientid');
        $sales->sales_projectid = request('sales_projectid');
        $sales->sales_categoryid = request('sales_categoryid');
        $sales->sales_reference = request('sales_reference');
        $sales->sales_notes = request('sales_notes');
        $sales->sales_salesperson = request('sales_salesperson');

        //save and return id
        if ($sales->save()) {
            return $sales->sales_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[SalesRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
            return false;
        }

        //general
        $sales->sales_title = request('sales_title');
        $sales->sales_description = request('sales_description');
        $sales->sales_type = request('sales_type');
        $sales->sales_quantity = request('sales_quantity');
        $sales->sales_unit_price = request('sales_unit_price');
        $sales->sales_total_amount = request('sales_total_amount');
        $sales->sales_discount_amount = request('sales_discount_amount');
        $sales->sales_tax_amount = request('sales_tax_amount');
        $sales->sales_final_amount = request('sales_final_amount');
        $sales->sales_currency = request('sales_currency');
        $sales->sales_status = request('sales_status');
        $sales->sales_payment_status = request('sales_payment_status');
        $sales->sales_payment_method = request('sales_payment_method');
        $sales->sales_date = request('sales_date');
        $sales->sales_due_date = request('sales_due_date');
        $sales->sales_clientid = request('sales_clientid');
        $sales->sales_projectid = request('sales_projectid');
        $sales->sales_categoryid = request('sales_categoryid');
        $sales->sales_reference = request('sales_reference');
        $sales->sales_notes = request('sales_notes');
        $sales->sales_salesperson = request('sales_salesperson');

        //save
        if ($sales->save()) {
            return $sales->sales_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[SalesRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

}
