<?php

namespace App\Http\Responses\Inventory;

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
                $template = 'pages.inventory.components.table.ajax';
                $dom_container = '#inventory-td-container';
                $dom_action = 'append';
                break;

            //from the sorting links
            case 'sort':
                $template = 'pages.inventory.components.table.ajax';
                $dom_container = '#inventory-td-container';
                $dom_action = 'replace';
                break;

            //from search box or filter panel
            case 'search':
                $template = 'pages.inventory.components.table.table';
                $dom_container = '#inventory-table-wrapper';
                $dom_action = 'replace-with';
                break;

            //template and dom - for ajax initial loading
            default:
                $template = 'pages.inventory.tabswrapper';
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
            $html = view($template, compact('page', 'inventory', 'stats', 'categories', 'tags'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html);

            //ajax response
            return response()->json($jsondata);

        } else {
            //standard view
            return response()->view('pages.inventory.index', $this->payload);
        }
    }

}
