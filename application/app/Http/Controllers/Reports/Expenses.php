<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Expenses extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('settingsMiddlewareIndex');
    }

    /**
     * Display expenses report by client
     */
    public function client(Request $request) {
        $page = [
            'page' => 'reports',
            'crumbs' => [
                __('lang.reports'),
                __('lang.expenses'),
                __('lang.by_client'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.expenses_by_client'),
            'heading' => __('lang.expenses_by_client'),
        ];

        return view('pages.reports.expenses.client', compact('page'));
    }

    /**
     * Display expenses report by project
     */
    public function project(Request $request) {
        $page = [
            'page' => 'reports',
            'crumbs' => [
                __('lang.reports'),
                __('lang.expenses'),
                __('lang.by_project'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.expenses_by_project'),
            'heading' => __('lang.expenses_by_project'),
        ];

        return view('pages.reports.expenses.project', compact('page'));
    }
}
