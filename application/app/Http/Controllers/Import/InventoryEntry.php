<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\InventoryEntryImport;
use App\Services\InventoryEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryEntry extends Controller
{
    protected $entryService;

    public function __construct(InventoryEntryService $entryService)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->entryService = $entryService;
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
}

