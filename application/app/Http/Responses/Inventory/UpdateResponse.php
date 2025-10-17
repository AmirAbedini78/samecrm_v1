<?php

namespace App\Http\Responses\Inventory;

use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

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
        $payload = $this->payload;

        //success
        request()->session()->flash('success-notification', __('lang.inventory_updated_successfully'));

        return response()->json([
            'notification' => [
                'type' => 'success',
                'value' => __('lang.inventory_updated_successfully'),
            ],
            'payload' => $payload,
        ]);

    }

}
