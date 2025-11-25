<?php

namespace App\Http\Responses\Reports\Warehouse;

use Illuminate\Contracts\Support\Responsable;

class WarehouseReportsResponse implements Responsable
{
    private $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view for warehouse reports
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $categories = $categories ?? collect();
        $customCategories = $customCategories ?? collect();

        // For normal page load, return the view directly
        if (!request()->ajax() && !request('action')) {
            return response()->view('pages.reports.warehouse.index', compact('page', 'categories', 'customCategories'));
        }

        // For AJAX requests, return the wrapper
        $html = view('pages.reports.warehouse.wrapper', compact('categories', 'customCategories'))->render();
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
            'value' => 'گزارش انبار',
        ];
        $jsondata['dom_classes'][] = [
            'selector' => '#reports-breadcrumbs-heading',
            'action' => 'add',
            'value' => 'active',
        ];

        return response()->json($jsondata);
    }
}

