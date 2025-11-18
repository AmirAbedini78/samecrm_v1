<?php

namespace App\Http\Responses\InvoiceSettlements;

use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable
{
    private $payload;

    public function __construct($payload = [])
    {
        $this->payload = $payload;
    }

    public function toResponse($request)
    {
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {
            switch (request('action')) {
                case 'load':
                    $template = 'pages.invoice-settlements.components.table.ajax';
                    $dom_container = '#invoice-settlements-td-container';
                    $dom_action = 'append';
                    break;
                case 'sort':
                    $template = 'pages.invoice-settlements.components.table.ajax';
                    $dom_container = '#invoice-settlements-td-container';
                    $dom_action = 'replace';
                    break;
                case 'search':
                    $template = 'pages.invoice-settlements.components.table.table';
                    $dom_container = '#invoice-settlements-table-wrapper';
                    $dom_action = 'replace-with';
                    if (isset($settlementrepo)) {
                        $filteredStats = $settlementrepo->calculateStats();
                        $jsondata['stats'] = $filteredStats;
                    } else {
                        $jsondata['stats'] = $stats ?? [];
                    }
                    break;
                default:
                    $template = 'pages.invoice-settlements.components.table.datatables-wrapper';
                    $dom_container = '#embed-content-container';
                    $dom_action = 'replace';
                    break;
            }

            if (request('action') == 'sort') {
                $sort_url = flipSortingUrl(request()->fullUrl(), request('sortorder'));
                $element_id = '#sort_' . request('orderby');
                $jsondata['dom_attributes'][] = [
                    'selector' => $element_id,
                    'attr' => 'data-url',
                    'value' => $sort_url,
                ];
            }

            $html = view($template, compact('page', 'settlements', 'stats', 'settlementrepo'))->render();
            $jsondata['dom_html'][] = [
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html,
            ];

            return response()->json($jsondata);
        }

        return response()->view('pages.invoice-settlements.index', $this->payload);
    }
}

