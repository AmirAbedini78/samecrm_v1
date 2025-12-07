<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\InventoryReportService;
use App\Repositories\InventoryReportRepository;
use App\Http\Responses\Reports\Warehouse\WarehouseReportsResponse;
use App\Models\Category;
use App\Models\InventoryCustomCategory;
use App\Models\InventoryEntry;
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
     * List all inventory entries with filters
     */
    public function listInventoryEntries(Request $request)
    {
        try {
            $validated = $request->validate([
                'inventory_id' => 'nullable|exists:inventory,inventory_id',
                'status' => 'nullable|in:all,expired,near_expiry,available',
                'near_expiry_days' => 'nullable|integer|min:1|max:365',
                'search' => 'nullable|string|max:120',
                'warehouse' => 'nullable|string|max:255',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ]);

            $status = $validated['status'] ?? 'all';
            $nearExpiryDays = $validated['near_expiry_days'] ?? 30;

            $query = InventoryEntry::with('inventory:inventory_id,inventory_name,inventory_code');

            if (!empty($validated['inventory_id'])) {
                $query->where('inventory_id', $validated['inventory_id']);
            }

            if (!empty($validated['search'])) {
                $query->where(function ($builder) use ($validated) {
                    $builder->where('entry_code', 'like', '%' . $validated['search'] . '%')
                        ->orWhere('document_number', 'like', '%' . $validated['search'] . '%')
                        ->orWhereHas('inventory', function ($q) use ($validated) {
                            $q->where('inventory_name', 'like', '%' . $validated['search'] . '%')
                                ->orWhere('inventory_code', 'like', '%' . $validated['search'] . '%');
                        });
                });
            }

            if (!empty($validated['from_date'])) {
                $query->where('entry_date', '>=', $validated['from_date']);
            }

            if (!empty($validated['to_date'])) {
                $query->where('entry_date', '<=', $validated['to_date']);
            }

            if ($status === 'expired') {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<', now()->format('Y-m-d'));
            } elseif ($status === 'near_expiry') {
                $query->whereNotNull('expiry_date')
                    ->whereBetween('expiry_date', [
                        now()->format('Y-m-d'),
                        now()->addDays($nearExpiryDays)->format('Y-m-d'),
                    ]);
            } elseif ($status === 'available') {
                $query->where(function ($builder) {
                    $builder->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', now()->format('Y-m-d'));
                });
            }

            $entries = $query->orderByDesc('entry_date')
                ->orderByDesc('entry_id')
                ->get()
                ->map(function (InventoryEntry $entry) {
                    return [
                        'entry_id' => $entry->entry_id,
                        'inventory_id' => $entry->inventory_id,
                        'inventory_name' => optional($entry->inventory)->inventory_name,
                        'inventory_code' => optional($entry->inventory)->inventory_code,
                        'entry_date' => optional($entry->entry_date)->format('Y-m-d'),
                        'entry_code' => $entry->entry_code,
                        'entry_type' => $entry->entry_type,
                        'document_number' => $entry->document_number,
                        'quantity' => $entry->quantity,
                        'unit_price' => $entry->unit_price,
                        'total_amount' => $entry->total_amount,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $entries,
            ]);
        } catch (\Exception $e) {
            Log::error('List Inventory Entries Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get inventory entry batches for a given SKU.
     */
    public function getInventoryEntries(Request $request)
    {
        try {
            $validated = $request->validate([
                'inventory_id' => 'required|exists:inventory,inventory_id',
                'status' => 'nullable|in:all,expired,near_expiry,available',
                'near_expiry_days' => 'nullable|integer|min:1|max:365',
                'search' => 'nullable|string|max:120',
            ]);

            $status = $validated['status'] ?? 'all';
            $nearExpiryDays = $validated['near_expiry_days'] ?? 30;

            $query = InventoryEntry::where('inventory_id', $validated['inventory_id']);

            if (!empty($validated['search'])) {
                $query->where(function ($builder) use ($validated) {
                    $builder->where('entry_code', 'like', '%' . $validated['search'] . '%')
                        ->orWhere('document_number', 'like', '%' . $validated['search'] . '%');
                });
            }

            $entries = $query->orderByDesc('entry_date')
                ->orderByDesc('entry_id')
                ->get()
                ->map(function (InventoryEntry $entry) {
                    return [
                        'entry_id' => $entry->entry_id,
                        'inventory_id' => $entry->inventory_id,
                        'entry_date' => optional($entry->entry_date)->format('Y-m-d'),
                        'entry_code' => $entry->entry_code,
                        'entry_type' => $entry->entry_type,
                        'document_number' => $entry->document_number,
                        'quantity' => $entry->quantity,
                        'unit_price' => $entry->unit_price,
                        'total_amount' => $entry->total_amount,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $entries,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Inventory Entries Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
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

