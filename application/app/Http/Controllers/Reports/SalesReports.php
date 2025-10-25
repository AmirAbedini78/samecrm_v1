<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Models\Sales;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReports extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates']);
    }

    public function comparison() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0, 'average_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0, 'average_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }

    public function comparisonData(Request $request) {
        try {
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');

            Log::info('Comparison Data Request', [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product
            ]);

            // Build Range 1 Query
            $range1_query = Sales::query();
            if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                $range1_query->where('document_date', '>=', $range1_from);
            }
            if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                $range1_query->where('document_date', '<=', $range1_to);
            }
            if ($sales_status) {
                $range1_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range1_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range1_query->where('product_name', 'LIKE', '%' . $product . '%');
            }

            // Build Range 2 Query
            $range2_query = Sales::query();
            if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                $range2_query->where('document_date', '>=', $range2_from);
            }
            if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                $range2_query->where('document_date', '<=', $range2_to);
            }
            if ($sales_status) {
                $range2_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range2_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range2_query->where('product_name', 'LIKE', '%' . $product . '%');
            }

            $result = [
                'range1' => [
                    'count' => (int) $range1_query->count(),
                    'total_sales_amount' => (float) ($range1_query->sum('base_sales_amount') ?? 0),
                    'average_sales_amount' => (float) ($range1_query->avg('base_sales_amount') ?? 0),
                ],
                'range2' => [
                    'count' => (int) $range2_query->count(),
                    'total_sales_amount' => (float) ($range2_query->sum('base_sales_amount') ?? 0),
                    'average_sales_amount' => (float) ($range2_query->avg('base_sales_amount') ?? 0),
                ],
            ];

            Log::info('Comparison Results', $result);

            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Comparison Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function comparisonDataTables(Request $request) {
        try {
            $range = $request->get('range', 1);
            
            // Get all parameters
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');

            Log::info("DataTables Range $range Request", [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product
            ]);

            // Build query based on range
            $query = Sales::query();
            
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }

            // Apply additional filters
            if ($sales_status) {
                $query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }

            // Apply column-specific search
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        if (in_array($column, ['product_name', 'customer_name', 'document_number', 'sales_status'])) {
                            $query->where($column, 'LIKE', '%' . $value . '%');
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
                          ->orderBy('document_date', 'desc')
                          ->skip($start)
                          ->take($length)
                          ->get();

            // Format data
            $data = [];
            foreach ($sales as $item) {
                $data[] = [
                    'sales_id' => $item->sales_id,
                    'product_name' => $item->product_name ?? '',
                    'customer_name' => $item->customer_name ?? '',
                    'document_number' => $item->document_number ?? '',
                    'main_quantity' => $item->main_quantity ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_date_persian' => $item->document_date, // Already in Persian format
                    'creator' => $item->creator ? $item->creator->first_name . ' ' . $item->creator->last_name : '',
                    'actions' => '<a href="/sales/' . $item->sales_id . '" class="btn btn-sm btn-outline-primary">مشاهده</a>'
                ];
            }

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        $payload = ['page' => $page];
        return response()->view('pages.reports.sales.aggregates', $payload);
    }
}