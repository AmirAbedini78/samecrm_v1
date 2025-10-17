<?php

namespace App\Repositories;

use App\Models\Accounting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountingRepository {

    /**
     * The accounting repository instance.
     */
    protected $accounting;

    public function __construct(Accounting $accounting) {
        $this->accounting = $accounting;
    }

    /**
     * get accounting records
     * @param object $request instance of the request object
     * @return object
     */
    public function search($id = '') {

        $accounting = $this->accounting->newQuery();

        //filter by id
        if (is_numeric($id)) {
            $accounting->where('accounting_id', $id);
        }

        //filter by status
        if (request()->filled('filter_accounting_status')) {
            $accounting->where('accounting_status', request('filter_accounting_status'));
        }

        //filter by type
        if (request()->filled('filter_accounting_type')) {
            $accounting->where('accounting_type', request('filter_accounting_type'));
        }

        //filter by category
        if (request()->filled('filter_accounting_categoryid')) {
            $accounting->where('accounting_categoryid', request('filter_accounting_categoryid'));
        }

        //filter by creator
        if (request()->filled('filter_accounting_creatorid')) {
            $accounting->where('accounting_creatorid', request('filter_accounting_creatorid'));
        }

        //filter by date
        if (request()->filled('filter_accounting_date_from')) {
            $accounting->where('accounting_date', '>=', request('filter_accounting_date_from'));
        }
        if (request()->filled('filter_accounting_date_to')) {
            $accounting->where('accounting_date', '<=', request('filter_accounting_date_to'));
        }

        //search: multiple client fields
        if (request()->filled('search_query')) {
            $accounting->where(function ($query) {
                $query->where('accounting_title', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('accounting_description', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('accounting_reference', 'LIKE', '%' . request('search_query') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request()->filled('orderby')) {
            $accounting->orderBy(request('orderby'), request('sortorder'));
        } else {
            $accounting->orderBy('accounting_created', 'desc');
        }

        //eager load
        $accounting->with([
            'creator',
            'category',
            'tags',
        ]);

        return $accounting;
    }

    /**
     * create a new record
     * @param int $id optional id of the record
     * @return mixed int|bool
     */
    public function create($id = '') {

        //save new user
        $accounting = new $this->accounting;

        //data
        $accounting->accounting_title = request('accounting_title');
        $accounting->accounting_description = request('accounting_description');
        $accounting->accounting_type = request('accounting_type');
        $accounting->accounting_amount = request('accounting_amount');
        $accounting->accounting_currency = request('accounting_currency');
        $accounting->accounting_date = request('accounting_date');
        $accounting->accounting_status = request('accounting_status');
        $accounting->accounting_creatorid = auth()->id();
        $accounting->accounting_categoryid = request('accounting_categoryid');
        $accounting->accounting_reference = request('accounting_reference');
        $accounting->accounting_notes = request('accounting_notes');
        $accounting->accounting_payment_method = request('accounting_payment_method');
        $accounting->accounting_payment_status = request('accounting_payment_status');
        $accounting->accounting_due_date = request('accounting_due_date');

        //save and return id
        if ($accounting->save()) {
            return $accounting->accounting_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[AccountingRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
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
        if (!$accounting = $this->accounting->find($id)) {
            return false;
        }

        //general
        $accounting->accounting_title = request('accounting_title');
        $accounting->accounting_description = request('accounting_description');
        $accounting->accounting_type = request('accounting_type');
        $accounting->accounting_amount = request('accounting_amount');
        $accounting->accounting_currency = request('accounting_currency');
        $accounting->accounting_date = request('accounting_date');
        $accounting->accounting_status = request('accounting_status');
        $accounting->accounting_categoryid = request('accounting_categoryid');
        $accounting->accounting_reference = request('accounting_reference');
        $accounting->accounting_notes = request('accounting_notes');
        $accounting->accounting_payment_method = request('accounting_payment_method');
        $accounting->accounting_payment_status = request('accounting_payment_status');
        $accounting->accounting_due_date = request('accounting_due_date');

        //save
        if ($accounting->save()) {
            return $accounting->accounting_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[AccountingRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

}

