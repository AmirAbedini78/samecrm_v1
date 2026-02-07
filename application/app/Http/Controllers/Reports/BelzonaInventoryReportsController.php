<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\BelzonaInventory\BelzonaInventoryReportsResponse;
use App\Models\BelzonaInventory;

class BelzonaInventoryReportsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['index']);
    }

    /**
     * Belzona inventory reporting page (ajax-loaded in reports area)
     */
    public function index()
    {
        $page = [
            'title' => 'گزارشگیری انبار بلزونا',
            'heading' => 'گزارشگیری انبار بلزونا',
            'dynamic_url' => url('/report/belzona-inventory'),
        ];

        // lightweight stats (same as BelzonaInventoryController@index)
        $stats = [
            'total_items' => BelzonaInventory::count(),
            'total_input' => BelzonaInventory::sum('input') ?? 0,
            'total_output' => BelzonaInventory::sum('output') ?? 0,
            'total_balance' => BelzonaInventory::sum('balance') ?? 0,
            'distinct_products' => BelzonaInventory::distinct('sheet_name')->count('sheet_name'),
            'distinct_customers' => BelzonaInventory::whereNotNull('customer_name')->distinct('customer_name')->count('customer_name'),
            'last_import_at' => optional(BelzonaInventory::orderBy('created_at', 'desc')->first())->created_at,
        ];

        $payload = [
            'page' => $page,
            'stats' => $stats,
        ];

        return new BelzonaInventoryReportsResponse($payload);
    }
}

