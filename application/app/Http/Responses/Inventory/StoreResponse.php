<?php

namespace App\Http\Responses\Inventory;

use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

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

        //prepend content on top of list or show full table
        if ($count == 1) {
            $html = view('pages/inventory/components/table/table', compact('inventory'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#inventory-table-wrapper',
                'action' => 'replace',
                'value' => $html);
        } else {
            //prepend use on top of list
            $html = view('pages/inventory/components/table/ajax', compact('inventory'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#inventory-td-container',
                'action' => 'prepend',
                'value' => $html);
        }
        
        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.inventory_created_successfully'));

        //response
        return response()->json($jsondata);

    }

}
