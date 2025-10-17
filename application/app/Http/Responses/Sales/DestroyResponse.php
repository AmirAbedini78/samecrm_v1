<?php

namespace App\Http\Responses\Sales;

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
        $payload = $this->payload;

        //success
        request()->session()->flash('success-notification', __('lang.sales_deleted_successfully'));

        return response()->json([
            'notification' => [
                'type' => 'success',
                'value' => __('lang.sales_deleted_successfully'),
            ],
            'payload' => $payload,
        ]);

    }

}
