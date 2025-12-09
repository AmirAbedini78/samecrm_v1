<?php

namespace App\Http\Responses\GuaranteeLetters;

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

        $jsondata = [];

        //success notification
        $jsondata['notification'] = [
            'type' => 'success',
            'value' => __('lang.request_has_been_completed'),
        ];

        //refresh table
        $jsondata['dom_html'][] = [
            'selector' => '#guarantees-table-wrapper',
            'action' => 'replace-with',
            'value' => view('pages.guarantee-letters.components.table.table', compact('guarantee'))->render(),
        ];

        //close modal
        $jsondata['dom_visibility'][] = [
            'selector' => '#commonModal',
            'action' => 'close-modal',
        ];

        //response
        return response()->json($jsondata);
    }

}

