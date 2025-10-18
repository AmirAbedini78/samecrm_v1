<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Units extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('settingsMiddlewareIndex');
    }

    /**
     * Display units settings
     */
    public function index() {
        $page = [
            'page' => 'settings',
            'crumbs' => [
                __('lang.settings'),
                __('lang.units'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.units'),
            'heading' => __('lang.units'),
        ];

        return view('pages.settings.units', compact('page'));
    }

    /**
     * Update units settings
     */
    public function update(Request $request) {
        // Implementation for updating units
        return redirect()->back()->with('success', __('lang.settings_updated_successfully'));
    }
}
