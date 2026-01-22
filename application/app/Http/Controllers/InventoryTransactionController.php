<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\InventoryTransactionRequest;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Repositories\InventoryTransactionRepository;
use App\Services\InventoryCalculationService;
use App\Imports\InventoryTransactionImport;
use App\Exceptions\Inventory\InsufficientStockException;
use App\Exceptions\Inventory\InventoryCalculationException;
use App\Exceptions\Inventory\InventoryNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryTransactionController extends Controller
{
    protected $transactionRepo;
    protected $calculationService;

    public function __construct(InventoryTransactionRepository $transactionRepo, InventoryCalculationService $calculationService)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->transactionRepo = $transactionRepo;
        $this->calculationService = $calculationService;
    }

    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        // Get filters from request
        $filters = [
            'inventory_id' => $request->get('filter_inventory_id'),
            'transaction_type' => $request->get('filter_transaction_type'),
            'from_date' => $request->get('filter_from_date'),
            'to_date' => $request->get('filter_to_date'),
            'document_number' => $request->get('filter_document_number'),
            'base_document_number' => $request->get('filter_base_document_number'),
            'warehouse' => $request->get('filter_warehouse'),
            'user_id' => $request->get('filter_user_id'),
            'search' => $request->get('search_query'),
            'order_by' => $request->get('order_by', 'transaction_date'),
            'order_dir' => $request->get('order_dir', 'desc'),
        ];

        // Get transactions
        $transactions = $this->transactionRepo->search($filters);

        // Paginate if needed
        if ($request->ajax() || $request->wantsJson()) {
            $perPage = $request->get('per_page', 25);
            $transactions = $transactions->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                ]
            ]);
        }

        // Get all inventories for filter dropdown
        $inventories = Inventory::where('inventory_status', 'active')
            ->orderBy('inventory_name')
            ->get();

        // Basic page settings
        $page = [
            'page' => 'transactions',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
                'گردش کالا',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'گردش کالا',
            'heading' => 'گردش کالا',
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.inventory.transactions.index', compact('page', 'transactions', 'inventories', 'filters'));
    }

    /**
     * Show the form for creating a new transaction
     */
    public function create()
    {
        $inventories = Inventory::where('inventory_status', 'active')
            ->orderBy('inventory_name')
            ->get();

        $page = [
            'page' => 'create_transaction',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
                'گردش کالا',
                'ایجاد تراکنش',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'ایجاد تراکنش',
            'heading' => 'ایجاد تراکنش',
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.inventory.transactions.create', compact('page', 'inventories'));
    }

    /**
     * Store a newly created transaction
     */
    public function store(InventoryTransactionRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Calculate amount if not provided
                $amount = $request->input('amount');
                if (empty($amount) && $request->input('quantity') > 0 && $request->input('unit_price') > 0) {
                    $amount = $request->input('quantity') * $request->input('unit_price');
                }

                // Prepare data
                $data = $request->only([
                    'inventory_id', 'transaction_type', 'quantity', 'sub_quantity',
                    'unit_price', 'transaction_date', 'document_number',
                    'base_document_number', 'warehouse', 'notes'
                ]);
                $data['amount'] = $amount;
                $data['user_id'] = auth()->id();

                // Create transaction
                $transaction = $this->transactionRepo->create($data);

                // Recalculate inventory
                if (!$this->calculationService->recalculateInventory($transaction->inventory_id)) {
                    throw new InventoryCalculationException('خطا در محاسبه موجودی');
                }

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تراکنش با موفقیت ایجاد شد',
                        'transaction' => $transaction->load(['inventory', 'user'])
                    ]);
                }

                return redirect()->route('inventory.transactions.index')
                    ->with('success', 'تراکنش با موفقیت ایجاد شد');
            });
        } catch (InsufficientStockException | InventoryCalculationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], $e->getCode());
            }
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Transaction creation failed: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطا در ایجاد تراکنش: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'خطا در ایجاد تراکنش']);
        }
    }

    /**
     * Show the form for editing a transaction
     */
    public function edit($id)
    {
        $transaction = InventoryTransaction::with(['inventory', 'user'])->findOrFail($id);
        $inventories = Inventory::where('inventory_status', 'active')
            ->orderBy('inventory_name')
            ->get();

        $page = [
            'page' => 'edit_transaction',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
                'گردش کالا',
                'ویرایش تراکنش',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'ویرایش تراکنش',
            'heading' => 'ویرایش تراکنش',
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.inventory.transactions.edit', compact('page', 'transaction', 'inventories'));
    }

    /**
     * Update a transaction
     */
    public function update(InventoryTransactionRequest $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $transaction = InventoryTransaction::findOrFail($id);
                $oldInventoryId = $transaction->inventory_id;
                $oldType = $transaction->transaction_type;
                $oldQuantity = $transaction->quantity;

                // Calculate amount if not provided
                $amount = $request->input('amount');
                if (empty($amount) && $request->input('quantity') > 0 && $request->input('unit_price') > 0) {
                    $amount = $request->input('quantity') * $request->input('unit_price');
                }

                // Prepare data
                $data = $request->only([
                    'inventory_id', 'transaction_type', 'quantity', 'sub_quantity',
                    'unit_price', 'transaction_date', 'document_number',
                    'base_document_number', 'warehouse', 'notes'
                ]);
                $data['amount'] = $amount;

                // Update transaction
                $this->transactionRepo->update($id, $data);

                // Recalculate old inventory if inventory_id changed
                if ($oldInventoryId != $request->input('inventory_id')) {
                    if (!$this->calculationService->recalculateInventory($oldInventoryId)) {
                        throw new InventoryCalculationException('خطا در محاسبه موجودی قدیمی');
                    }
                }

                // Recalculate new inventory
                if (!$this->calculationService->recalculateInventory($request->input('inventory_id'))) {
                    throw new InventoryCalculationException('خطا در محاسبه موجودی جدید');
                }

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تراکنش با موفقیت به‌روزرسانی شد',
                        'transaction' => $transaction->fresh(['inventory', 'user'])
                    ]);
                }

                return redirect()->route('inventory.transactions.index')
                    ->with('success', 'تراکنش با موفقیت به‌روزرسانی شد');
            });
        } catch (InsufficientStockException | InventoryCalculationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], $e->getCode());
            }
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Transaction update failed: ' . $e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطا در به‌روزرسانی تراکنش: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'خطا در به‌روزرسانی تراکنش']);
        }
    }

    /**
     * Delete a transaction
     */
    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $transaction = InventoryTransaction::findOrFail($id);
                $inventoryId = $transaction->inventory_id;

                // Delete transaction
                $this->transactionRepo->delete($id);

                // Recalculate inventory
                if (!$this->calculationService->recalculateInventory($inventoryId)) {
                    throw new InventoryCalculationException('خطا در محاسبه موجودی پس از حذف');
                }

                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تراکنش با موفقیت حذف شد'
                    ]);
                }

                return redirect()->route('inventory.transactions.index')
                    ->with('success', 'تراکنش با موفقیت حذف شد');
            });
        } catch (InventoryCalculationException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], $e->getCode());
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Transaction deletion failed: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطا در حذف تراکنش: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'خطا در حذف تراکنش']);
        }
    }

    /**
     * Show import form
     */
    public function showImport()
    {
        $inventories = Inventory::where('inventory_status', 'active')
            ->orderBy('inventory_name')
            ->get();

        $page = [
            'page' => 'import_transactions',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
                'گردش کالا',
                'وارد کردن از اکسل',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'وارد کردن گردش کالا از اکسل',
            'heading' => 'وارد کردن گردش کالا از اکسل',
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.inventory.transactions.import', compact('page', 'inventories'));
    }

    /**
     * Process import
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
                'inventory_id' => 'nullable|exists:inventory,inventory_id', // Optional: for bulk import of one inventory
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }

        if (!$request->hasFile('attachments') || !$request->file('attachments')[0]) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        $file = $request->file('attachments')[0];
        $file_path = $file->getPathname();

        if (!file_exists($file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
                'imported' => 0,
                'skipped' => 0,
            ], 404);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $allowed_extensions = ['xlsx', 'xls', 'csv'];
        
        if (!in_array($extension, $allowed_extensions)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file type',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        try {
            $import = new InventoryTransactionImport();
            $import->import($file_path);

            $import_results = [
                'success' => true,
                'imported' => $import->getRowCount(),
                'skipped' => $import->getSkippedCount(),
                'message' => "Successfully imported {$import->getRowCount()} transactions",
            ];

            return response()->json($import_results);
        } catch (\Exception $e) {
            Log::error("Transaction import failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

