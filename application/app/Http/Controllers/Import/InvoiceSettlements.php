<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\InvoiceSettlementsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceSettlements extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $page = [
            'page' => 'import',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.invoice_settlements'),
                __('lang.import'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.import_invoice_settlements'),
            'heading' => __('lang.import_invoice_settlements'),
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.import.invoice-settlements', compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if (!$request->hasFile('attachments') || !$request->file('attachments')[0]) {
            return response()->json([
                'success' => false,
                'message' => __('lang.no_file_uploaded'),
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        $file = $request->file('attachments')[0];
        $file_path = $file->getPathname();
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            abort(409, __('lang.invalid_file_type'));
        }

        $importResults = [
            'success' => false,
            'imported' => 0,
            'skipped' => 0,
            'message' => '',
        ];

        try {
            $import = new InvoiceSettlementsImport();
            $import->import($file_path);

            $importResults = [
                'success' => true,
                'imported' => $import->getRowCount(),
                'skipped' => $import->getSkippedCount(),
                'skipped_details' => $import->getSkippedDetails(),
                'message' => __('lang.import_invoice_settlements_success', ['count' => $import->getRowCount()]),
            ];
        } catch (\Exception $e) {
            Log::error('Invoice settlements import failed', ['error' => $e->getMessage()]);
            $importResults = [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => __('lang.import_failed_generic'),
            ];
        }

        if ($request->ajax()) {
            return response()->json($importResults);
        }

        return redirect()->back()->with('import_results', $importResults);
    }
}

