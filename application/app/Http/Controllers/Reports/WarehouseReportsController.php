<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\InventoryReportService;
use App\Repositories\InventoryReportRepository;
use App\Http\Responses\Reports\Warehouse\WarehouseReportsResponse;
use App\Models\Category;
use App\Models\InventoryCustomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WarehouseReportsController extends Controller
{
    protected $reportService;
    protected $reportRepository;

    public function __construct(
        InventoryReportService $reportService,
        InventoryReportRepository $reportRepository
    ) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['index']);

        $this->reportService = $reportService;
        $this->reportRepository = $reportRepository;
    }

    /**
     * Display warehouse reports main page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = [
            'title' => 'گزارش انبار',
            'heading' => 'گزارش انبار',
            'dynamic_url' => url('/report/warehouse')
        ];

        $categories = Category::select('category_id', 'category_name')
            ->orderBy('category_name')
            ->get();

        $customCategories = InventoryCustomCategory::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $payload = [
            'page' => $page,
            'categories' => $categories,
            'customCategories' => $customCategories,
        ];

        return new WarehouseReportsResponse($payload);
    }

    /**
     * Get current stock data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentStock(Request $request)
    {
        try {
            $filters = $this->extractFilters($request, [
                'warehouse', 'physical_available', 'min_quantity', 'max_quantity'
            ]);

            $data = $this->reportService->getCurrentStock($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Current Stock Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get expiry report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExpiryReport(Request $request)
    {
        try {
            $filters = $this->extractFilters($request, ['status']);
            $data = $this->reportService->getExpiryReport($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Expiry Report Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesReport(Request $request)
    {
        try {
            $filters = $this->extractFilters($request, [
                'from_date', 'to_date', 'product_code', 'warehouse', 'year'
            ]);
            if (!empty($filters['year'])) {
                $filters['sales_year'] = $filters['year'];
            }

            $data = $this->reportService->getSalesReport($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Sales Report Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get outside inventory report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOutsideInventory(Request $request)
    {
        try {
            $filters = $this->extractFilters($request);
            $data = $this->reportService->getOutsideInventory($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Outside Inventory Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top selling products
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopSelling(Request $request)
    {
        try {
            $filters = $this->extractFilters($request, ['from_date', 'to_date', 'year']);
            $limit = $request->get('limit', 10);

            $data = $this->reportService->getTopSellingProducts($filters, $limit);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Top Selling Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalytics(Request $request)
    {
        try {
            $filters = $this->extractFilters($request, ['from_date', 'to_date']);
            $data = $this->reportService->getAnalytics($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Analytics Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transactions log
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions(Request $request)
    {
        try {
            $filters = $this->extractFilters($request, [
                'inventory_id', 'transaction_type', 'from_date', 'to_date', 'warehouse'
            ]);

            $data = $this->reportService->getTransactions($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Transactions Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        try {
            $filters = $this->extractFilters($request);
            $data = $this->reportRepository->getSummary($filters);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Get Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Normalize filters from request
     */
    private function extractFilters(Request $request, array $additional = [])
    {
        $base = [
            'quick_range',
            'from_date',
            'to_date',
            'category_id',
            'custom_category_id',
            'custom_category_alias',
            'search',
            'status_filter',
            'flags',
            'sales_year',
            'year',
        ];

        $filters = $request->only(array_unique(array_merge($base, $additional)));

        $filters['flags'] = $request->input('flags', []);

        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }

        if ($request->filled('year') && empty($filters['sales_year'])) {
            $filters['sales_year'] = $request->input('year');
        }

        return $filters;
    }
}

