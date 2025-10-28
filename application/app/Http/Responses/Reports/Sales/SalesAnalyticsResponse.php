<?php

namespace App\Http\Responses\Reports\Sales;

use Illuminate\Contracts\Support\Responsable;

class SalesAnalyticsResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for sales analytics report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        // For normal page load, return the view directly
        if (!request()->ajax() && !request('action')) {
            return response()->view('pages.reports.sales.analytics', compact('page'));
        }

        // For AJAX requests, return the wrapper
        $html = view('pages.reports.sales.analytics-wrapper')->render();
        $jsondata['dom_html'][] = [
            'selector' => '#embed-content-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //breadcrumbs
        $jsondata['dom_classes'][] = [
            'selector' => '.reports-breadcrumbs',
            'action' => 'remove',
            'value' => 'active',
        ];
        $jsondata['dom_classes'][] = [
            'selector' => '.reports-breadcrumbs',
            'action' => 'remove',
            'value' => 'hidden',
        ];
        $jsondata['dom_html'][] = [
            'selector' => '#reports-breadcrumbs-heading',
            'action' => 'replace',
            'value' => 'تحلیل‌های فروش',
        ];
        $jsondata['dom_html'][] = [
            'selector' => '#reports-breadcrumbs-sub-heading',
            'action' => 'replace',
            'value' => 'داشبورد تحلیل و نمودارها',
        ];
        $jsondata['dom_classes'][] = [
            'selector' => '#reports-breadcrumbs-sub-heading',
            'action' => 'add',
            'value' => 'active',
        ];

        //ajax response
        return response()->json($jsondata);
    }

}

