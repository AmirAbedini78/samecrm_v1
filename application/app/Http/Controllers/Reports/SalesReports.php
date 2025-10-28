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

    public function comparison(Request $request) {
        // Check if requesting unique values for a column
        if ($request->get('action') === 'unique_values' && $request->has('column')) {
            return $this->getUniqueValues($request);
        }
        
        // Check if requesting sort action
        if ($request->get('action') === 'sort') {
            return $this->handleSort($request);
        }
        
        // Check if requesting search action
        if ($request->get('action') === 'search') {
            return $this->handleSearch($request);
        }
        
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0, 'average_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0, 'average_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }
    
    /**
     * Get unique values for a column
     */
    private function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            $query = Sales::query();
            
            // Apply date range filter
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
            
            $values = [];
            
            // Handle different column types
            switch ($column) {
                case 'creator':
                    $values = $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                        ->select('users.first_name', 'users.last_name')
                        ->distinct()
                        ->whereNotNull('users.first_name')
                        ->where('users.first_name', '!=', '')
                        ->get()
                        ->map(function($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
                default:
                    $values = $query->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
            }
            
            sort($values);
            
            return response()->json([
                'success' => true,
                'data' => array_values($values)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Unique Values Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Handle sort action
     */
    private function handleSort(Request $request) {
        // Store sort preferences in session
        $orderby = $request->get('orderby');
        $sortorder = $request->get('sortorder', 'asc');
        $range = $request->get('range', 1);
        
        session(['sales_report_sort_' . $range => [
            'orderby' => $orderby,
            'sortorder' => $sortorder
        ]]);
        
        // Return success - the actual sorting will be applied in comparisonDataTables
        return response()->json(['success' => true]);
    }
    
    /**
     * Handle search action
     */
    private function handleSearch(Request $request) {
        // Store search preferences in session
        $range = $request->get('range', 1);
        $searchParams = [];
        
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $searchParams[$key] = $value;
            }
        }
        
        session(['sales_report_search_' . $range => $searchParams]);
        
        return response()->json(['success' => true]);
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

            // Apply column-specific search from request or session
            $searchParams = session('sales_report_search_' . $range, []);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $searchParams[$key] = $value;
                }
            }
            
            foreach ($searchParams as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        // Apply search to various columns
                        $searchableColumns = [
                            'sales_id', 'document_number', 'product_name', 'customer_name',
                            'main_quantity', 'base_price', 'base_sales_amount', 'base_net_amount',
                            'document_type', 'sales_type', 'product_code', 'product_barcode',
                            'tracking_code', 'main_unit', 'warehouse', 'base_tax_amount',
                            'base_duty_amount', 'base_additional_amount', 'base_increasing_factors',
                            'month', 'description', 'sales_status'
                        ];
                        
                        if (in_array($column, $searchableColumns)) {
                            if ($column === 'creator') {
                                // Special handling for creator
                                $query->whereHas('creator', function($q) use ($value) {
                                    $q->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                                });
                            } else {
                                $query->where($column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Apply sorting
            $sortSettings = session('sales_report_sort_' . $range);
            $orderby = $request->get('orderby', $sortSettings['orderby'] ?? 'document_date');
            $sortorder = $request->get('sortorder', $sortSettings['sortorder'] ?? 'desc');
            
            // Handle special sort columns
            if ($orderby === 'creator') {
                $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                      ->orderBy('users.first_name', $sortorder)
                      ->select('sales.*');
            } else {
                $query->orderBy($orderby, $sortorder);
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
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
                    'base_price' => $item->base_price ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'base_tax_amount' => $item->base_tax_amount ?? 0,
                    'base_duty_amount' => $item->base_duty_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_type' => $item->document_type ?? '',
                    'sales_type' => $item->sales_type ?? '',
                    'product_code' => $item->product_code ?? '',
                    'product_barcode' => $item->product_barcode ?? '',
                    'warehouse' => $item->warehouse ?? '',
                    'document_date' => $item->document_date,
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