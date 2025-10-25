<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Helpers\PersianCalendarHelper;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Http\Responses\Reports\Sales\SalesAggregatesResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SalesReports extends Controller {

    public function __construct() {
        //parent
        parent::__construct();
        
        //authenticated
        $this->middleware('auth');
        
        //route middleware
        $this->middleware('reportsMiddlewareShow')->only([
            'comparison',
            'aggregates',
        ]);
    }

    // Render comparison page
    public function comparison() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        // Get comparison data
        $report = $this->getComparisonData();
        
        // Response payload
        $payload = [
            'report' => $report,
            'page' => $this->pageSettings('comparison'),
        ];
        
        // Process response
        return new SalesComparisonResponse($payload);
    }

    // Comparison data endpoint
    public function comparisonData(Request $request) {
        $range1_from = $request->get('range1_from');
        $range1_to   = $request->get('range1_to');
        $range2_from = $request->get('range2_from');
        $range2_to   = $request->get('range2_to');

        // Convert Persian dates to Gregorian for database query
        $range1_from_gregorian = null;
        $range1_to_gregorian = null;
        $range2_from_gregorian = null;
        $range2_to_gregorian = null;

        if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
            $range1_from_gregorian = PersianCalendarHelper::persianToGregorian($range1_from);
        }
        if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
            $range1_to_gregorian = PersianCalendarHelper::persianToGregorian($range1_to);
        }
        if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
            $range2_from_gregorian = PersianCalendarHelper::persianToGregorian($range2_from);
        }
        if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
            $range2_to_gregorian = PersianCalendarHelper::persianToGregorian($range2_to);
        }

        $query = Sales::query();
        $range1 = (clone $query)
            ->when($range1_from_gregorian, function($q) use($range1_from_gregorian){ $q->where('document_date', '>=', $range1_from_gregorian); })
            ->when($range1_to_gregorian, function($q) use($range1_to_gregorian){ $q->where('document_date', '<=', $range1_to_gregorian); });

        $query2 = Sales::query();
        $range2 = (clone $query2)
            ->when($range2_from_gregorian, function($q) use($range2_from_gregorian){ $q->where('document_date', '>=', $range2_from_gregorian); })
            ->when($range2_to_gregorian, function($q) use($range2_to_gregorian){ $q->where('document_date', '<=', $range2_to_gregorian); });

        // Get rows for both ranges
        $rows1 = (clone $range1)->select(['sales_id','document_date','customer_name','product_name','main_quantity','base_sales_amount'])->orderBy('document_date','desc')->get();
        $rows2 = (clone $range2)->select(['sales_id','document_date','customer_name','product_name','main_quantity','base_sales_amount'])->orderBy('document_date','desc')->get();

        // Convert document dates to Persian for display
        $rows1->transform(function ($item) {
            $item->document_date_persian = PersianCalendarHelper::gregorianToPersian($item->document_date);
            return $item;
        });

        $rows2->transform(function ($item) {
            $item->document_date_persian = PersianCalendarHelper::gregorianToPersian($item->document_date);
            return $item;
        });

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
        
        // Get aggregates data
        $report = $this->getAggregatesData();
        
        // Response payload
        $payload = [
            'report' => $report,
            'page' => $this->pageSettings('aggregates'),
        ];
        
        // Process response
        return new SalesAggregatesResponse($payload);
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

    // Get comparison data
    private function getComparisonData() {
        $range1_from = request()->get('range1_from');
        $range1_to   = request()->get('range1_to');
        $range2_from = request()->get('range2_from');
        $range2_to   = request()->get('range2_to');

        // Convert Persian dates to Gregorian for database query
        $range1_from_gregorian = null;
        $range1_to_gregorian = null;
        $range2_from_gregorian = null;
        $range2_to_gregorian = null;

        if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
            $range1_from_gregorian = PersianCalendarHelper::persianToGregorian($range1_from);
        }
        if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
            $range1_to_gregorian = PersianCalendarHelper::persianToGregorian($range1_to);
        }
        if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
            $range2_from_gregorian = PersianCalendarHelper::persianToGregorian($range2_from);
        }
        if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
            $range2_to_gregorian = PersianCalendarHelper::persianToGregorian($range2_to);
        }

        $query = Sales::query();
        $range1 = (clone $query)
            ->when($range1_from_gregorian, function($q) use($range1_from_gregorian){ $q->where('document_date', '>=', $range1_from_gregorian); })
            ->when($range1_to_gregorian, function($q) use($range1_to_gregorian){ $q->where('document_date', '<=', $range1_to_gregorian); });

        $query2 = Sales::query();
        $range2 = (clone $query2)
            ->when($range2_from_gregorian, function($q) use($range2_from_gregorian){ $q->where('document_date', '>=', $range2_from_gregorian); })
            ->when($range2_to_gregorian, function($q) use($range2_to_gregorian){ $q->where('document_date', '<=', $range2_to_gregorian); });

        // Get rows for both ranges
        $rows1 = (clone $range1)->select(['sales_id','document_date','customer_name','product_name','main_quantity','base_sales_amount'])->orderBy('document_date','desc')->get();
        $rows2 = (clone $range2)->select(['sales_id','document_date','customer_name','product_name','main_quantity','base_sales_amount'])->orderBy('document_date','desc')->get();

        // Convert document dates to Persian for display
        $rows1->transform(function ($item) {
            $item->document_date_persian = PersianCalendarHelper::gregorianToPersian($item->document_date);
            return $item;
        });

        $rows2->transform(function ($item) {
            $item->document_date_persian = PersianCalendarHelper::gregorianToPersian($item->document_date);
            return $item;
        });

        return [
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
    }

    // Get aggregates data
    private function getAggregatesData() {
        $sales = Sales::query();

        if (request()->filled('document_date_from')) {
            $sales->where('document_date', '>=', request()->get('document_date_from'));
        }
        if (request()->filled('document_date_to')) {
            $sales->where('document_date', '<=', request()->get('document_date_to'));
        }

        // Column-specific filters (unique values applied)
        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_') === 0 && !empty($value)) {
                $column = substr($key, 7); // remove 'column_'
                if (Schema::hasColumn('sales', $column)) {
                    $sales->where($column, $value);
                }
            }
        }

        return [
            'count' => (int) $sales->count(),
            'total_sales_amount' => (float) ($sales->sum('base_sales_amount') ?? 0),
            'average_sales_amount' => (float) ($sales->avg('base_sales_amount') ?? 0),
        ];
    }

    // Page settings
    private function pageSettings($section = '') {
        $page = [];

        if ($section == 'comparison') {
            $page += [
                'breadcrumbs-heading' => __('lang.sales'),
                'breadcrumbs-sub-heading' => __('lang.comparison'),
            ];
        }

        if ($section == 'aggregates') {
            $page += [
                'breadcrumbs-heading' => __('lang.sales'),
                'breadcrumbs-sub-heading' => __('lang.aggregates'),
            ];
        }

        return $page;
    }
}


