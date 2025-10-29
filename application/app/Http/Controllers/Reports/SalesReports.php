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
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates', 'analytics']);
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
     * Get unique values for a column (used by both comparison and analytics)
     */
    public function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            
            // For comparison page (range-based)
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            // For analytics page (simple from/to)
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            
            $query = Sales::query();
            
            // Apply date range filter for comparison page
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else if ($range == 2) {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }
            
            // Apply date filter for analytics page
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
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
                'data' => array_values($values),
                'count' => count($values)
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

    /**
     * Display sales analytics dashboard
     */
    public function analytics() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') . ' - ' . 'تحلیل‌های فروش' ];
        $payload = ['page' => $page];
        return new \App\Http\Responses\Reports\Sales\SalesAnalyticsResponse($payload);
    }

    /**
     * Get monthly sales trend
     */
    public function getMonthlyTrend(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = Sales::query();
            
            // Date filters
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Additional filters
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            if ($status) {
                $query->where('sales_status', $status);
            }

            // Get all sales data
            $sales = $query->select('document_date', 'month', 'base_sales_amount')->get();
            
            // Group by year and month manually
            $grouped = [];
            foreach ($sales as $sale) {
                // Extract year from document_date (format: YYYY/MM/DD or YYYY-MM-DD)
                $dateParts = preg_split('/[-\/]/', $sale->document_date);
                $year = $dateParts[0] ?? '1403';
                $month = $sale->month;
                
                $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'year' => $year,
                        'month' => (int)$month,
                        'year_month' => $key,
                        'count' => 0,
                        'total_amount' => 0,
                        'amounts' => []
                    ];
                }
                
                $grouped[$key]['count']++;
                $grouped[$key]['total_amount'] += $sale->base_sales_amount;
                $grouped[$key]['amounts'][] = $sale->base_sales_amount;
            }
            
            // Calculate averages and sort
            $monthlyData = [];
            foreach ($grouped as $key => $data) {
                $data['avg_amount'] = $data['count'] > 0 ? $data['total_amount'] / $data['count'] : 0;
                unset($data['amounts']); // Remove raw amounts array
                $monthlyData[] = $data;
            }
            
            // Sort by year_month
            usort($monthlyData, function($a, $b) {
                return strcmp($a['year_month'], $b['year_month']);
            });

            return response()->json([
                'success' => true,
                'data' => $monthlyData
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly Trend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $limit = $request->get('limit', 10);
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = Sales::query();
            
            // Date filters
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Additional filters
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            if ($status) {
                $query->where('sales_status', $status);
            }

            // Group by product
            $topProducts = $query->selectRaw('product_name, COUNT(*) as sales_count, SUM(main_quantity) as total_quantity, SUM(base_sales_amount) as total_amount')
                ->groupBy('product_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Top Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $limit = $request->get('limit', 10);
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = Sales::query();
            
            // Date filters
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Additional filters
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            if ($status) {
                $query->where('sales_status', $status);
            }

            // Group by customer
            $topCustomers = $query->selectRaw('customer_name, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount, AVG(base_sales_amount) as avg_amount')
                ->groupBy('customer_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topCustomers
            ]);

        } catch (\Exception $e) {
            Log::error('Top Customers Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get profit analysis
     */
    public function getProfitAnalysis(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = Sales::query();
            
            // Date filters
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Additional filters
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            if ($status) {
                $query->where('sales_status', $status);
            }

            // Calculate profit by product
            $profitData = $query->selectRaw('product_name, SUM(base_net_amount) as net_amount, SUM(base_sales_amount) as sales_amount, SUM(base_net_amount - base_sales_amount) as profit, COUNT(*) as count')
                ->groupBy('product_name')
                ->orderBy('profit', 'desc')
                ->limit(15)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profitData
            ]);

        } catch (\Exception $e) {
            Log::error('Profit Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get seasonal analysis (by quarter/season)
     */
    public function getSeasonalAnalysis(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = Sales::query();
            
            // Date filters
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Additional filters
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            if ($status) {
                $query->where('sales_status', $status);
            }

            // Get all sales
            $sales = $query->get();

            // Group by season (فصل)
            $seasonalData = [
                'spring' => ['name' => 'بهار', 'count' => 0, 'total' => 0, 'months' => [1, 2, 3]], // فروردین، اردیبهشت، خرداد
                'summer' => ['name' => 'تابستان', 'count' => 0, 'total' => 0, 'months' => [4, 5, 6]], // تیر، مرداد، شهریور
                'autumn' => ['name' => 'پاییز', 'count' => 0, 'total' => 0, 'months' => [7, 8, 9]], // مهر، آبان، آذر
                'winter' => ['name' => 'زمستان', 'count' => 0, 'total' => 0, 'months' => [10, 11, 12]], // دی، بهمن، اسفند
            ];

            foreach ($sales as $sale) {
                $month = (int) $sale->month;
                foreach ($seasonalData as $key => $season) {
                    if (in_array($month, $season['months'])) {
                        $seasonalData[$key]['count']++;
                        $seasonalData[$key]['total'] += $sale->base_sales_amount;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($seasonalData)
            ]);

        } catch (\Exception $e) {
            Log::error('Seasonal Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivery status analysis
     */
    public function getDeliveryStatus(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = Sales::query();
            
            // Date filters
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Additional filters
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            if ($status) {
                $query->where('sales_status', $status);
            }

            // Calculate delivery statistics
            $stats = $query->selectRaw('
                COUNT(*) as total_orders,
                SUM(issued_main_quantity) as total_issued,
                SUM(remaining_main_quantity) as total_remaining,
                SUM(main_quantity) as total_quantity
            ')->first();

            $deliveryRate = 0;
            if ($stats->total_quantity > 0) {
                $deliveryRate = ($stats->total_issued / $stats->total_quantity) * 100;
            }

            // Top products with pending delivery
            $pendingProducts = $query->selectRaw('product_name, SUM(remaining_main_quantity) as pending_quantity, COUNT(*) as order_count')
                ->where('remaining_main_quantity', '>', 0)
                ->groupBy('product_name')
                ->orderBy('pending_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'delivery_rate' => round($deliveryRate, 2),
                    'pending_products' => $pendingProducts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test analytics data - for debugging
     */
    public function testAnalyticsData(Request $request) {
        try {
            // Get total count
            $totalCount = Sales::count();
            
            // Get date range
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            
            // Sample data
            $sampleData = Sales::orderBy('document_date', 'desc')->limit(5)->get();
            
            // Monthly distribution
            $monthlyCount = Sales::selectRaw('month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return response()->json([
                'success' => true,
                'total_records' => $totalCount,
                'date_range' => [
                    'min' => $minDate,
                    'max' => $maxDate
                ],
                'sample_data' => $sampleData,
                'monthly_distribution' => $monthlyCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}