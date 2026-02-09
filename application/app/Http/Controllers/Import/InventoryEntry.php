<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\InventoryEntryImport;
use App\Models\Inventory;
use App\Services\InventoryEntryService;
use App\Services\PdfInventoryExtractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryEntry extends Controller
{
    protected $entryService;
    protected $pdfExtractService;

    public function __construct(InventoryEntryService $entryService, PdfInventoryExtractService $pdfExtractService)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->entryService = $entryService;
        $this->pdfExtractService = $pdfExtractService;
    }

    /**
     * Show the import form
     */
    public function index()
    {
        $page = [
            'page' => 'import',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
                'ایمپورت ورودهای انبار',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'ایمپورت ورودهای انبار',
            'heading' => 'ایمپورت ورودهای انبار',
            'mainmenu_accounting' => 'active',
            'enable_python_ml' => config('inventory.enable_python_ml', true),
        ];

        return view('pages.import.inventory-entry', compact('page'));
    }

    /**
     * Process the import
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
                'inventory_code' => 'required|string|max:255',
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
                'message' => 'فایلی آپلود نشده است',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        $file = $request->file('attachments')[0];
        $file_path = $file->getPathname();

        if (!file_exists($file_path)) {
            Log::error("Inventory Entry Import: File not found", ['file_path' => $file_path]);
            return response()->json([
                'success' => false,
                'message' => 'فایل یافت نشد',
                'imported' => 0,
                'skipped' => 0,
            ], 404);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $allowed_extensions = ['xlsx', 'xls', 'csv'];

        if (!in_array($extension, $allowed_extensions)) {
            return response()->json([
                'success' => false,
                'message' => 'نوع فایل نامعتبر است',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        try {
            // دریافت کد کالا از درخواست
            $inventoryCode = $request->input('inventory_code');
            
            if (empty($inventoryCode)) {
                return response()->json([
                    'success' => false,
                    'message' => 'کد کالا الزامی است',
                    'imported' => 0,
                    'skipped' => 0,
                ], 400);
            }

            $import = new InventoryEntryImport($this->entryService, null, $inventoryCode);
            $import->import($file_path);

            $failures = $import->failures();
            $failureCount = $failures->count();

            $import_results = [
                'success' => true,
                'imported' => $import->getRowCount() ?? 0,
                'skipped' => $failureCount,
                'message' => "با موفقیت {$import->getRowCount()} ورود انبار ایمپورت شد",
                'failures' => $failures->map(function ($failure) {
                    return [
                        'row' => $failure->row(),
                        'errors' => $failure->errors(),
                    ];
                })->toArray(),
            ];

            if ($request->ajax()) {
                return response()->json($import_results);
            }

            return redirect()->back()->with('import_results', $import_results);
        } catch (\Exception $e) {
            Log::error("Inventory Entry Import failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $import_results = [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => 'ایمپورت با خطا مواجه شد: ' . $e->getMessage(),
            ];

            if ($request->ajax()) {
                return response()->json($import_results, 500);
            }

            return redirect()->back()->with('import_results', $import_results);
        }
    }

    /**
     * Process PDF upload: extract data and optionally create entries.
     * If inventory_code is provided it overrides auto-detection.
     */
    public function processPdf(Request $request)
    {
        try {
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'required|file|mimes:pdf|max:20480',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'لطفاً یک فایل PDF معتبر آپلود کنید (حداکثر ۲۰ مگابایت)',
                'errors' => $e->errors(),
            ], 422);
        }

        $file = $request->file('attachments')[0];
        $tempPath = $file->getRealPath();
        if (!file_exists($tempPath)) {
            return response()->json([
                'success' => false,
                'message' => 'فایل یافت نشد',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        $extract = $this->pdfExtractService->extractFromPdf($tempPath);
        if (!empty($extract['error'])) {
            return response()->json([
                'success' => false,
                'message' => $extract['error'],
                'imported' => 0,
                'skipped' => 0,
                'preview' => $extract,
            ], 400);
        }

        $inventoryCode = $request->input('inventory_code') ?: ($extract['inventory_code'] ?? null);
        if (empty($inventoryCode)) {
            return response()->json([
                'success' => false,
                'message' => 'کد کالا در PDF تشخیص داده نشد. لطفاً کد کالا را وارد کنید یا فایل PDF شامل «کد کالا» باشد.',
                'imported' => 0,
                'skipped' => 0,
                'preview' => $extract,
            ], 400);
        }

        $inventory = Inventory::where('inventory_code', $inventoryCode)->first();
        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'کالایی با کد «' . $inventoryCode . '» در سیستم یافت نشد.',
                'imported' => 0,
                'skipped' => 0,
                'preview' => $extract,
            ], 400);
        }

        $rows = $extract['rows'] ?? [];
        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'هیچ ردیف گردش در PDF استخراج نشد. لطفاً فایل دیگری امتحان کنید.',
                'imported' => 0,
                'skipped' => 0,
                'preview' => $extract,
            ], 400);
        }

        $importBatch = now()->format('Ymd-His');
        $imported = 0;
        $skipped = 0;
        foreach ($rows as $row) {
            $quantity = (float) ($row['quantity'] ?? 0);
            if ($quantity <= 0) {
                $skipped++;
                continue;
            }
            $entryDate = PdfInventoryExtractService::normalizeEntryDate($row['entry_date'] ?? null);
            $created = $this->entryService->create([
                'inventory_id' => $inventory->inventory_id,
                'entry_date' => $entryDate,
                'entry_code' => $row['entry_code'] ?? '',
                'entry_type' => $row['entry_type'] ?? 'ورودی',
                'document_number' => $row['document_number'] ?? '',
                'quantity' => $quantity,
                'unit_price' => (float) ($row['unit_price'] ?? 0),
                'total_amount' => (float) ($row['total_amount'] ?? ($quantity * (float)($row['unit_price'] ?? 0))),
                'import_batch' => $importBatch,
            ]);
            if ($created) {
                $imported++;
            } else {
                $skipped++;
            }
        }

        $import_results = [
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => "با موفقیت {$imported} ورود انبار از PDF ایمپورت شد.",
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($import_results);
        }
        return redirect()->back()->with('import_results', $import_results);
    }
}

