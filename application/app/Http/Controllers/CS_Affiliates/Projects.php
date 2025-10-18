<?php

namespace App\Http\Controllers\CS_Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Projects extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of affiliate projects
     */
    public function index() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.projects'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.affiliate_projects'),
            'heading' => __('lang.affiliate_projects'),
        ];

        return view('pages.cs_affiliates.projects.index', compact('page'));
    }

    /**
     * Show the form for creating a new affiliate project
     */
    public function create() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.projects'),
                __('lang.create'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.create_affiliate_project'),
            'heading' => __('lang.create_affiliate_project'),
        ];

        return view('pages.cs_affiliates.projects.create', compact('page'));
    }

    /**
     * Store a newly created affiliate project
     */
    public function store(Request $request) {
        // Implementation for storing affiliate project
        return redirect()->route('cs.affiliates.projects.index')->with('success', __('lang.affiliate_project_created_successfully'));
    }

    /**
     * Display the specified affiliate project
     */
    public function show($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.projects'),
                __('lang.view'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.view_affiliate_project'),
            'heading' => __('lang.view_affiliate_project'),
        ];

        return view('pages.cs_affiliates.projects.show', compact('page', 'id'));
    }

    /**
     * Show the form for editing the specified affiliate project
     */
    public function edit($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.projects'),
                __('lang.edit'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.edit_affiliate_project'),
            'heading' => __('lang.edit_affiliate_project'),
        ];

        return view('pages.cs_affiliates.projects.edit', compact('page', 'id'));
    }

    /**
     * Update the specified affiliate project
     */
    public function update(Request $request, $id) {
        // Implementation for updating affiliate project
        return redirect()->route('cs.affiliates.projects.index')->with('success', __('lang.affiliate_project_updated_successfully'));
    }

    /**
     * Remove the specified affiliate project
     */
    public function destroy($id) {
        // Implementation for deleting affiliate project
        return redirect()->route('cs.affiliates.projects.index')->with('success', __('lang.affiliate_project_deleted_successfully'));
    }
}
