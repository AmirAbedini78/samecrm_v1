<?php

namespace App\Http\Controllers\CS_Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Profit extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display affiliate profit/earnings
     */
    public function index() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.my_earnings'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.my_affiliate_earnings'),
            'heading' => __('lang.my_affiliate_earnings'),
        ];

        return view('pages.cs_affiliates.profit.index', compact('page'));
    }
}
