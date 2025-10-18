<?php

namespace App\Http\Controllers\CS_Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Users extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of affiliate users
     */
    public function index() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.users'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.affiliate_users'),
            'heading' => __('lang.affiliate_users'),
        ];

        return view('pages.cs_affiliates.users.index', compact('page'));
    }

    /**
     * Show the form for creating a new affiliate user
     */
    public function create() {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.users'),
                __('lang.create'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.create_affiliate_user'),
            'heading' => __('lang.create_affiliate_user'),
        ];

        return view('pages.cs_affiliates.users.create', compact('page'));
    }

    /**
     * Store a newly created affiliate user
     */
    public function store(Request $request) {
        // Implementation for storing affiliate user
        return redirect()->route('cs.affiliates.users.index')->with('success', __('lang.affiliate_user_created_successfully'));
    }

    /**
     * Display the specified affiliate user
     */
    public function show($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.users'),
                __('lang.view'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.view_affiliate_user'),
            'heading' => __('lang.view_affiliate_user'),
        ];

        return view('pages.cs_affiliates.users.show', compact('page', 'id'));
    }

    /**
     * Show the form for editing the specified affiliate user
     */
    public function edit($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.users'),
                __('lang.edit'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.edit_affiliate_user'),
            'heading' => __('lang.edit_affiliate_user'),
        ];

        return view('pages.cs_affiliates.users.edit', compact('page', 'id'));
    }

    /**
     * Update the specified affiliate user
     */
    public function update(Request $request, $id) {
        // Implementation for updating affiliate user
        return redirect()->route('cs.affiliates.users.index')->with('success', __('lang.affiliate_user_updated_successfully'));
    }

    /**
     * Remove the specified affiliate user
     */
    public function destroy($id) {
        // Implementation for deleting affiliate user
        return redirect()->route('cs.affiliates.users.index')->with('success', __('lang.affiliate_user_deleted_successfully'));
    }

    /**
     * Show the form for editing password
     */
    public function editPassword($id) {
        $page = [
            'page' => 'affiliates',
            'crumbs' => [
                __('lang.affiliates'),
                __('lang.users'),
                __('lang.change_password'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.change_password'),
            'heading' => __('lang.change_password'),
        ];

        return view('pages.cs_affiliates.users.edit_password', compact('page', 'id'));
    }

    /**
     * Update the password
     */
    public function updatePassword(Request $request, $id) {
        // Implementation for updating password
        return redirect()->route('cs.affiliates.users.index')->with('success', __('lang.password_updated_successfully'));
    }
}
