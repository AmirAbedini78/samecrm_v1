<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SalesReports extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    // Render comparison page
    public function comparison() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        return response()->view('pages.reports.sales.comparison', compact('page'));
    }

    // Comparison data endpoint
    public function comparisonData(Request $request) {
        $range1_from = $request->get('range1_from');
        $range1_to   = $request->get('range1_to');
        $range2_from = $request->get('range2_from');
        $range2_to   = $request->get('range2_to');

        $query = Sales::query();
        $range1 = (clone $query)
            ->when($range1_from, function($q) use($range1_from){ $q->where('document_date', '>=', $range1_from); })
            ->when($range1_to, function($q) use($range1_to){ $q->where('document_date', '<=', $range1_to); });

        $query2 = Sales::query();
        $range2 = (clone $query2)
            ->when($range2_from, function($q) use($range2_from){ $q->where('document_date', '>=', $range2_from); })
            ->when($range2_to, function($q) use($range2_to){ $q->where('document_date', '<=', $range2_to); });

        // rows for both ranges (basic fields for readability)
        $rows1 = (clone $range1)->select(['sales_id','document_date','customer_name','product_name','main_quantity','base_sales_amount'])->orderBy('document_date','desc')->get();
        $rows2 = (clone $range2)->select(['sales_id','document_date','customer_name','product_name','main_quantity','base_sales_amount'])->orderBy('document_date','desc')->get();

        $result = [
            'range1' => [
                'count' => (int) $range1->count(),
                'total_sales_amount' => (float) ($range1->sum('base_sales_amount') ?? 0),
                'average_sales_amount' => (float) ($range1->avg('base_sales_amount') ?? 0),
                'rows' => $rows1,
            ],
            'range2' => [
                'count' => (int) $range2->count(),
                'total_sales_amount' => (float) ($range2->sum('base_sales_amount') ?? 0),
                'average_sales_amount' => (float) ($range2->avg('base_sales_amount') ?? 0),
                'rows' => $rows2,
            ],
        ];

        return response()->json(['success' => true, 'data' => $result]);
    }

    // Render aggregates page
    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        return response()->view('pages.reports.sales.aggregates', compact('page'));
    }

    // Aggregates data endpoint with filters similar to list
    public function aggregatesData(Request $request) {
        $sales = Sales::query();

        if ($request->filled('document_date_from')) {
            $sales->where('document_date', '>=', $request->get('document_date_from'));
        }
        if ($request->filled('document_date_to')) {
            $sales->where('document_date', '<=', $request->get('document_date_to'));
        }

        // Column-specific filters (unique values applied)
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_') === 0 && !empty($value)) {
                $column = substr($key, 7); // remove 'column_'
                if (Schema::hasColumn('sales', $column)) {
                    $sales->where($column, $value);
                }
            }
        }

        $data = [
            'count' => (int) $sales->count(),
            'total_sales_amount' => (float) ($sales->sum('base_sales_amount') ?? 0),
            'average_sales_amount' => (float) ($sales->avg('base_sales_amount') ?? 0),
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}


