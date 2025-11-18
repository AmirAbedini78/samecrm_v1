<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\InvoiceSettlements\IndexResponse;
use App\Models\InvoiceSettlement;
use App\Repositories\InvoiceSettlementRepository;

class InvoiceSettlementController extends Controller
{
    protected $repo;

    public function __construct(InvoiceSettlementRepository $repo)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('invoice-settlements.index')->only(['index']);

        $this->repo = $repo;
    }

    public function index()
    {
        if (request()->get('action') === 'unique_values' && request()->has('column')) {
            $values = $this->repo->getUniqueValues(request('column'));
            return response()->json([
                'success' => true,
                'data' => $values,
            ]);
        }

        if (request()->get('action') === 'datatables') {
            return $this->getDataTablesData();
        }

        $page = $this->pageSettings();
        $settlements = $this->repo->search();

        $stats = [
            'total_records' => $settlements->total(),
            'total_net' => InvoiceSettlement::sum('base_net_amount'),
            'total_paid' => InvoiceSettlement::sum('paid_amount'),
            'total_balance' => InvoiceSettlement::sum('balance_amount'),
        ];

        $payload = [
            'page' => $page,
            'settlements' => $settlements,
            'stats' => $stats,
            'settlementrepo' => $this->repo,
        ];

        return new IndexResponse($payload);
    }

    protected function getDataTablesData()
    {
        $settlements = $this->repo->search();

        $data = [];
        foreach ($settlements->items() as $item) {
            $data[] = [
                'invoice_settlement_id' => $item->invoice_settlement_id,
                'document_number' => $item->document_number,
                'document_date' => $item->document_date,
                'customer_name' => $item->customer_name,
                'base_net_amount' => $item->base_net_amount,
                'paid_amount' => $item->paid_amount,
                'balance_amount' => $item->balance_amount,
                'currency' => $item->currency,
                'creator' => optional($item->creator)->first_name . ' ' . optional($item->creator)->last_name,
            ];
        }

        return response()->json([
            'draw' => request('draw'),
            'recordsTotal' => $settlements->total(),
            'recordsFiltered' => $settlements->total(),
            'data' => $data,
        ]);
    }

    protected function pageSettings()
    {
        return [
            'page_title' => __('lang.invoice_settlements'),
            'heading' => __('lang.invoice_settlements'),
            'crumbs' => [
                __('lang.accounting'),
                __('lang.invoice_settlements'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'mainmenu_accounting' => 'active',
            'submenu_invoice_settlements' => 'active',
        ];
    }
}

