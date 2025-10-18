<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\InventoryImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Inventory extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the import form
     */
    public function index() {
        $page = [
            'page' => 'import',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
                __('lang.import'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.import_inventory'),
            'heading' => __('lang.import_inventory'),
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.import.inventory', compact('page'));
    }

    /**
     * Process the import
     */
    public function store(Request $request) {
        
        try {
            // Validate request
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }

        // Get the uploaded file directly from request
        if (!$request->hasFile('attachments') || !$request->file('attachments')[0]) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }
        
        $file = $request->file('attachments')[0];
        
        // Debug information
        Log::info("Inventory Import Debug", [
            'file' => $file,
            'file_path' => $file ? $file->getPathname() : 'No file',
            'file_name' => $file ? $file->getClientOriginalName() : 'No file',
            'request_data' => $request->all()
        ]);

        // Use the temporary file path
        $file_path = $file->getPathname();

        // Check if file exists
        if (!file_exists($file_path)) {
            Log::error("File not found", ['file_path' => $file_path]);
            return response()->json([
                'success' => false,
                'message' => 'File not found: ' . $file_path,
                'imported' => 0,
                'skipped' => 0,
            ], 404);
        }

        // Get file extension
        $extension = strtolower($file->getClientOriginalExtension());

        // Validate file type
        $allowed_extensions = ['xlsx', 'xls', 'csv'];
        if (!in_array($extension, $allowed_extensions)) {
            abort(409, __('lang.invalid_file_type'));
        }

        // Initialize results
        $import_results = [
            'success' => false,
            'imported' => 0,
            'skipped' => 0,
            'message' => '',
        ];

        try {
            // Handle Excel/CSV files
            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
                $import = new InventoryImport(1000); // Import limit

                try {
                    $import->import($file_path);

                    $import_results = [
                        'success' => true,
                        'imported' => $import->getRowCount(),
                        'skipped' => $import->getSkippedCount(),
                        'message' => "Successfully imported {$import->getRowCount()} inventory items",
                    ];

                    if ($import->maxLimitReached()) {
                        $import_results['message'] .= __('lang.maximum_importing_limit_reached') . ": " . $import->getMaxItems();
                    }

                } catch (\Exception $e) {
                    $import_results = [
                        'success' => false,
                        'imported' => 0,
                        'skipped' => 0,
                        'message' => 'Import failed: ' . $e->getMessage(),
                    ];
                    Log::error("Excel/CSV inventory import failed: " . $e->getMessage(), ['inventory.import', config('app.debug_ref'), basename(__FILE__), __line__]);
                }
            }

        } catch (\Exception $e) {
            $import_results = [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => 'Import failed due to an error',
            ];
            Log::error("Inventory import failed: " . $e->getMessage(), ['inventory.import', config('app.debug_ref'), basename(__FILE__), __line__]);
        }

        // Clean up - delete the temporary file
        // No need to clean up as we're using temporary files directly

        // Return response
        if ($request->ajax()) {
            return response()->json($import_results);
        }

        return redirect()->back()->with('import_results', $import_results);
    }
}
