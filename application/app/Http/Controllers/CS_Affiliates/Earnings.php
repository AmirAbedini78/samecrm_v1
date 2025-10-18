<?php

namespace App\Http\Controllers\CS_Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Earnings extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of affiliate earnings
     */
    public function index() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.earnings'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.affiliate_earnings'),
            'heading' => __('lang.affiliate_earnings'),
        ];

        return view('pages.cs_affiliates.earnings.index', compact('page'));
    }

    /**
     * Show the form for creating a new affiliate earning
     */
    public function create() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.earnings'),
                __('lang.create'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.create_affiliate_earning'),
            'heading' => __('lang.create_affiliate_earning'),
        ];

        return view('pages.cs_affiliates.earnings.create', compact('page'));
    }

    /**
     * Store a newly created affiliate earning
     */
    public function store(Request $request) {
        // Implementation for storing affiliate earning
        return redirect()->route('cs.affiliates.earnings.index')->with('success', __('lang.affiliate_earning_created_successfully'));
    }

    /**
     * Display the specified affiliate earning
     */
    public function show($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.earnings'),
                __('lang.view'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.view_affiliate_earning'),
            'heading' => __('lang.view_affiliate_earning'),
        ];

        return view('pages.cs_affiliates.earnings.show', compact('page', 'id'));
    }

    /**
     * Show the form for editing the specified affiliate earning
     */
    public function edit($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.earnings'),
                __('lang.edit'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.edit_affiliate_earning'),
            'heading' => __('lang.edit_affiliate_earning'),
        ];

        return view('pages.cs_affiliates.earnings.edit', compact('page', 'id'));
    }

    /**
     * Update the specified affiliate earning
     */
    public function update(Request $request, $id) {
        // Implementation for updating affiliate earning
        return redirect()->route('cs.affiliates.earnings.index')->with('success', __('lang.affiliate_earning_updated_successfully'));
    }

    /**
     * Remove the specified affiliate earning
     */
    public function destroy($id) {
        // Implementation for deleting affiliate earning
        return redirect()->route('cs.affiliates.earnings.index')->with('success', __('lang.affiliate_earning_deleted_successfully'));
    }
}
