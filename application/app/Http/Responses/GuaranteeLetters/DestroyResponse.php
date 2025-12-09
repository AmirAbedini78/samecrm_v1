<?php

namespace App\Http\Responses\GuaranteeLetters;

use Illuminate\Contracts\Support\Responsable;

class DestroyResponse implements Responsable {

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

        //remove from table
        $jsondata['dom_visibility'][] = [
            'selector' => '#guarantee_' . $guarantee->guarantee_id,
            'action' => 'slideup-remove',
        ];

        //response
        return response()->json($jsondata);
    }

}

