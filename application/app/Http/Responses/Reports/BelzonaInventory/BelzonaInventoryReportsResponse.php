<?php

namespace App\Http\Responses\Reports\BelzonaInventory;

use Illuminate\Contracts\Support\Responsable;

class BelzonaInventoryReportsResponse implements Responsable
{
    private $payload;

    public function __construct($payload = [])
    {
        $this->payload = $payload;
    }

    /**
     * Render the view for Belzona Inventory reports
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $page = $page ?? [];
        $stats = $stats ?? [];

        // Normal page load
        if (!request()->ajax() && !request('action')) {
            return response()->view('pages.reports.belzona-inventory.index', compact('page', 'stats'));
        }

        // AJAX requests (reports wrapper)
        $html = view('pages.reports.belzona-inventory.wrapper', compact('stats'))->render();
        $jsondata['dom_html'][] = [
            'selector' => '#embed-content-container',
            'action' => 'replace',
            'value' => $html,
        ];

        // breadcrumbs
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
            'value' => 'گزارشگیری انبار بلزونا',
        ];
        $jsondata['dom_classes'][] = [
            'selector' => '#reports-breadcrumbs-heading',
            'action' => 'add',
            'value' => 'active',
        ];

        return response()->json($jsondata);
    }
}

