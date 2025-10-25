<?php

namespace App\Http\Responses\Reports\Sales;

use Illuminate\Contracts\Support\Responsable;

class SalesComparisonResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for sales comparison report
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
            return response()->view('pages.reports.sales.comparison', compact('page', 'report'));
        }

        if (request('action') == 'load' || request('action') == 'sort') {

            if (request('action') == 'load') {
                $html = view('pages/reports/sales/comparison-table', compact('report'))->render();
                $jsondata['dom_html'][] = [
                    'selector' => '#report-results-container',
                    'action' => 'replace-with',
                    'value' => $html,
                ];
            }

            if (request('action') == 'sort') {
                $html = view('pages/reports/sales/comparison-ajax', compact('report'))->render();
                $jsondata['dom_html'][] = [
                    'selector' => '#report-results-ajax-container',
                    'action' => 'replace',
                    'value' => $html,
                ];
            }

            //skip don update
            $jsondata['skip_dom_reset'] = true;

        } else {
            $html = view('pages/reports/sales/comparison-wrapper', compact('report'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#embed-content-container',
                'action' => 'replace',
                'value' => $html,
            ];
        }

        //crummbs
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
            'value' => $page['breadcrumbs-heading'],
        ];
        $jsondata['dom_html'][] = [
            'selector' => '#reports-breadcrumbs-sub-heading',
            'action' => 'replace',
            'value' => $page['breadcrumbs-sub-heading'],
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
