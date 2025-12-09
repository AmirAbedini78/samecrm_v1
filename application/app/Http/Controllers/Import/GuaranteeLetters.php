<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\GuaranteeLetterImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class GuaranteeLetters extends Controller {

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
                __('lang.guarantee_letters'),
                __('lang.import'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.import_guarantee_letters'),
            'heading' => __('lang.import_guarantee_letters'),
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.import.guarantee-letters', compact('page'));
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
                'guarantee_type' => 'nullable|in:tender_participation,performance,advance_payment',
                'sheet_name' => 'nullable|string',
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
                $guaranteeType = $request->input('guarantee_type');
                $sheetName = $request->input('sheet_name');
                
                $import = new GuaranteeLetterImport($guaranteeType, $sheetName);

                try {
                    // For Excel files with multiple sheets, we need to import each sheet
                    if (in_array($extension, ['xlsx', 'xls'])) {
                        // Try to import all sheets
                        $sheets = Excel::toArray($import, $file_path);
                        
                        $totalImported = 0;
                        $totalSkipped = 0;
                        
                        foreach ($sheets as $sheetIndex => $sheetData) {
                            // Get sheet name if available
                            $currentSheetName = $sheetName;
                            if (!$currentSheetName && isset($sheets[$sheetIndex])) {
                                // Try to get sheet name from Excel
                                try {
                                    $reader = Excel::import($import, $file_path);
                                    // Sheet name detection would need additional implementation
                                } catch (\Exception $e) {
                                    // Continue with default
                                }
                            }
                            
                            // Import this sheet
                            $sheetImport = new GuaranteeLetterImport($guaranteeType, $currentSheetName);
                            Excel::import($sheetImport, $file_path, null, \Maatwebsite\Excel\Excel::XLSX);
                            
                            $totalImported += $sheetImport->getRowCount();
                            $totalSkipped += $sheetImport->getSkippedCount();
                        }
                        
                        $import_results = [
                            'success' => true,
                            'imported' => $totalImported,
                            'skipped' => $totalSkipped,
                            'skipped_details' => $import->getSkippedDetails(),
                            'message' => "Successfully imported {$totalImported} guarantee letter records",
                        ];
                    } else {
                        // CSV file - single sheet
                        $import->import($file_path);

                        $import_results = [
                            'success' => true,
                            'imported' => $import->getRowCount(),
                            'skipped' => $import->getSkippedCount(),
                            'skipped_details' => $import->getSkippedDetails(),
                            'message' => "Successfully imported {$import->getRowCount()} guarantee letter records",
                        ];
                    }

                } catch (\Exception $e) {
                    $import_results = [
                        'success' => false,
                        'imported' => 0,
                        'skipped' => 0,
                        'message' => 'Import failed: ' . $e->getMessage(),
                    ];
                    Log::error("Excel/CSV guarantee letter import failed: " . $e->getMessage(), [
                        'guarantee-letters.import', 
                        config('app.debug_ref'), 
                        basename(__FILE__), 
                        __line__,
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

        } catch (\Exception $e) {
            $import_results = [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => 'Import failed due to an error',
            ];
            Log::error("Guarantee letter import failed: " . $e->getMessage(), [
                'guarantee-letters.import', 
                config('app.debug_ref'), 
                basename(__FILE__), 
                __line__,
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Return response
        if ($request->ajax()) {
            return response()->json($import_results);
        }

        // For non-AJAX requests, store results in session
        return redirect()->back()->with('import_results', $import_results);
    }
}

