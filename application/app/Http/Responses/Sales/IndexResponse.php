<?php

namespace App\Http\Responses\Sales;

use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //was this call made from an embedded page/ajax or directly on temp page
        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {

            //template and dom - for additional ajax loading
            switch (request('action')) {

            //typically from the loadmore button
            case 'load':
                $template = 'pages.sales.components.table.ajax';
                $dom_container = '#sales-td-container';
                $dom_action = 'append';
                break;

            //from the sorting links
            case 'sort':
                $template = 'pages.sales.components.table.ajax';
                $dom_container = '#sales-td-container';
                $dom_action = 'replace';
                break;

            //from search box or filter panel
            case 'search':
                $template = 'pages.sales.components.table.table';
                $dom_container = '#sales-table-wrapper';
                $dom_action = 'replace-with';
                
                // Always calculate filtered stats for search action
                if (isset($salesrepo)) {
                    $filteredStats = $salesrepo->calculateStats();
                    $jsondata['stats'] = $filteredStats;
                } else {
                    // Fallback to original stats if salesrepo not available
                    $jsondata['stats'] = $stats;
                }
                break;

            //template and dom - for ajax initial loading
            default:
                $template = 'pages.sales.components.table.datatables-wrapper';
                $dom_container = '#embed-content-container';
                $dom_action = 'replace';
                break;
            }

            //flip sorting url for this particular link - only is we clicked sort menu links
            if (request('action') == 'sort') {
                $sort_url = flipSortingUrl(request()->fullUrl(), request('sortorder'));
                $element_id = '#sort_' . request('orderby');
                $jsondata['dom_attributes'][] = array(
                    'selector' => $element_id,
                    'attr' => 'data-url',
                    'value' => $sort_url);
            }

            //render the view and save to json
            $html = view($template, compact('page', 'sales', 'stats', 'categories', 'tags', 'salesrepo'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html);

            // Debug: Log the response (only in debug mode)
            if (config('app.debug')) {
                \Log::info('Sales response stats: ' . json_encode($jsondata['stats'] ?? 'No stats'));
            }

            //ajax response
            return response()->json($jsondata);

        } else {
            //standard view
            return response()->view('pages.sales.index', $this->payload);
        }
    }

}
