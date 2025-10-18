<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Proposals extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('settingsMiddlewareIndex');
    }

    /**
     * Display proposals report by client
     */
    public function client(Request $request) {
        $page = [
            'page' => 'reports',
            'crumbs' => [
                __('lang.reports'),
                __('lang.proposals'),
                __('lang.by_client'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.proposals_by_client'),
            'heading' => __('lang.proposals_by_client'),
        ];

        return view('pages.reports.proposals.client', compact('page'));
    }
}
