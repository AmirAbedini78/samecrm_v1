<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for inventory
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BelzonaInventory;
use App\Repositories\BelzonaInventoryRepository;
use Illuminate\Http\Request;

class BelzonaInventoryController extends Controller {

    /**
     * The inventory repository instance.
     */
    protected $belzonaInventoryRepo;

    public function __construct(BelzonaInventoryRepository $belzonaInventoryRepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        // Module-specific middleware
        $this->middleware('belzona-inventory.index')->only(['index']);

        //dependencies
        $this->belzonaInventoryRepo = $belzonaInventoryRepo;

    }

    /**
     * Display a listing of inventory records
     * @param object CategoryRepository category repository
     * @return blade view | ajax view
     */
    public function index() {

        // Check if requesting unique values for a column
        if (request()->get('action') === 'unique_values' && request()->has('column')) {
            $column = request()->get('column');
            $uniqueValues = $this->belzonaInventoryRepo->getUniqueValues($column);
            
            return response()->json([
                'success' => true,
                'data' => $uniqueValues
            ]);
        }
        
        // Check if requesting DataTables data
        if (request()->get('action') === 'datatables') {
            return $this->getDataTablesData();
        }

        // Product summary (used by index quick lookup)
        if (request()->get('action') === 'product_summary') {
            return $this->getProductSummary();
        }

        // Inbound batches (parts) for a product (used by index quick lookup)
        if (request()->get('action') === 'product_batches') {
            return $this->getProductBatches();
        }

        // Outbound details for a selected inbound batch
        if (request()->get('action') === 'batch_outbounds') {
            return $this->getBatchOutbounds();
        }

        // Recent transactions for a product (used by index quick lookup)
        if (request()->get('action') === 'product_transactions') {
            return $this->getProductTransactions();
        }

        //basic page settings
        $page = $this->pageSettings('index');

        //get inventory records
        $belzonaInventory = $this->belzonaInventoryRepo->search();

        //calculate stats
        $stats = [
            'total_items' => $belzonaInventory->total(),
            'total_input' => BelzonaInventory::sum('input') ?? 0,
            'total_output' => BelzonaInventory::sum('output') ?? 0,
            'total_balance' => BelzonaInventory::sum('balance') ?? 0,
            'distinct_products' => BelzonaInventory::distinct('sheet_name')->count('sheet_name'),
            'distinct_customers' => BelzonaInventory::whereNotNull('customer_name')->distinct('customer_name')->count('customer_name'),
            'last_import_at' => optional(BelzonaInventory::orderBy('created_at', 'desc')->first())->created_at,
        ];

        //reponse payload
        $payload = [
            'page' => $page,
            'belzonaInventory' => $belzonaInventory,
            'stats' => $stats,
        ];

        //show the view
        return response()->view('pages.belzona-inventory.index', $payload);
    }
    
    /**
     * Get DataTables data for inventory
     */
    private function getDataTablesData() {
        $baseQuery = BelzonaInventory::query();
        $recordsTotal = (clone $baseQuery)->count();

        // global search
        $searchValue = request('search.value');
        if (!empty($searchValue)) {
            $baseQuery->where(function ($q) use ($searchValue) {
                $q->where('sheet_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('product_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('product_weight_raw', 'LIKE', "%{$searchValue}%")
                    ->orWhere('date_raw', 'LIKE', "%{$searchValue}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$searchValue}%")
                    ->orWhere('customer_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('notes', 'LIKE', "%{$searchValue}%");
            });
        }

        // column filters (sent by our js)
        $columnSearch = request('column_search', []);
        if (is_array($columnSearch)) {
            foreach ($columnSearch as $col => $val) {
                $val = trim((string) $val);
                if ($val === '') {
                    continue;
                }

                switch ($col) {
                    case 'input_min':
                        $baseQuery->where('input', '>=', (float) $val);
                        break;
                    case 'input_max':
                        $baseQuery->where('input', '<=', (float) $val);
                        break;
                    case 'output_min':
                        $baseQuery->where('output', '>=', (float) $val);
                        break;
                    case 'output_max':
                        $baseQuery->where('output', '<=', (float) $val);
                        break;
                    case 'balance_min':
                        $baseQuery->where('balance', '>=', (float) $val);
                        break;
                    case 'balance_max':
                        $baseQuery->where('balance', '<=', (float) $val);
                        break;
                    default:
                        if (in_array($col, ['sheet_name', 'product_name', 'product_weight_raw', 'date_raw', 'invoice_number', 'customer_name', 'notes'], true)) {
                            $baseQuery->where($col, 'LIKE', "%{$val}%");
                        }
                        break;
                }
            }
        }

        // optional date range based on parsed date column
        if (request()->filled('filter_date_from')) {
            $baseQuery->whereDate('date', '>=', request('filter_date_from'));
        }
        if (request()->filled('filter_date_to')) {
            $baseQuery->whereDate('date', '<=', request('filter_date_to'));
        }

        $recordsFiltered = (clone $baseQuery)->count();

        // ordering (DataTables)
        $columns = [
            0 => 'belzona_inventory_id',
            1 => 'sheet_name',
            2 => 'product_weight_raw',
            3 => 'date_raw',
            4 => 'input',
            5 => 'output',
            6 => 'balance',
            7 => 'invoice_number',
            8 => 'customer_name',
            9 => 'notes',
        ];

        $orderColIndex = (int) request('order.0.column', 0);
        $orderDir = request('order.0.dir', 'desc');
        $orderDir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';
        $orderBy = $columns[$orderColIndex] ?? 'belzona_inventory_id';
        $baseQuery->orderBy($orderBy, $orderDir);

        $start = (int) request('start', 0);
        $length = (int) request('length', 25);
        if ($length <= 0) {
            $length = 25;
        }

        $rows = $baseQuery->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $item) {
            $data[] = [
                'belzona_inventory_id' => $item->belzona_inventory_id,
                'sheet_name' => $item->sheet_name,
                'product_weight_raw' => $item->product_weight_raw,
                'date_raw' => $item->date_raw,
                'input' => $item->input,
                'output' => $item->output,
                'balance' => $item->balance,
                'invoice_number' => $item->invoice_number,
                'customer_name' => $item->customer_name,
                'notes' => $item->notes,
                'actions' => '',
            ];
        }

        return response()->json([
            'draw' => (int) request('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function getProductSummary()
    {
        $sheetName = trim((string) request('sheet_name'));
        if ($sheetName === '') {
            return response()->json(['success' => false, 'message' => 'missing sheet_name'], 422);
        }

        $query = BelzonaInventory::query()->where('sheet_name', $sheetName);

        if (request()->filled('filter_date_from')) {
            $query->whereDate('date', '>=', request('filter_date_from'));
        }
        if (request()->filled('filter_date_to')) {
            $query->whereDate('date', '<=', request('filter_date_to'));
        }

        $totalInput = (clone $query)->sum('input') ?? 0;
        $totalOutput = (clone $query)->sum('output') ?? 0;

        // latest balance by parsed date, fallback to row number
        $latest = (clone $query)
            ->orderByRaw('CASE WHEN date IS NULL THEN 1 ELSE 0 END asc')
            ->orderBy('date', 'desc')
            ->orderBy('sheet_row_number', 'desc')
            ->first();

        $first = (clone $query)
            ->orderByRaw('CASE WHEN date IS NULL THEN 1 ELSE 0 END asc')
            ->orderBy('date', 'asc')
            ->orderBy('sheet_row_number', 'asc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'sheet_name' => $sheetName,
                'product_name' => optional($latest)->product_name,
                'product_weight_raw' => optional($latest)->product_weight_raw,
                'total_input' => $totalInput,
                'total_output' => $totalOutput,
                'net' => $totalInput - $totalOutput,
                'latest_balance' => optional($latest)->balance,
                'first_date_raw' => optional($first)->date_raw,
                'last_date_raw' => optional($latest)->date_raw,
                'rows' => (clone $query)->count(),
            ],
        ]);
    }

    private function getProductTransactions()
    {
        $sheetName = trim((string) request('sheet_name'));
        if ($sheetName === '') {
            return response()->json(['success' => false, 'message' => 'missing sheet_name'], 422);
        }

        $query = BelzonaInventory::query()->where('sheet_name', $sheetName);

        if (request()->filled('filter_date_from')) {
            $query->whereDate('date', '>=', request('filter_date_from'));
        }
        if (request()->filled('filter_date_to')) {
            $query->whereDate('date', '<=', request('filter_date_to'));
        }

        $rows = $query
            ->orderByRaw('CASE WHEN date IS NULL THEN 1 ELSE 0 END asc')
            ->orderBy('date', 'desc')
            ->orderBy('sheet_row_number', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows->map(function ($r) {
                return [
                    'date_raw' => $r->date_raw,
                    'input' => $r->input,
                    'output' => $r->output,
                    'balance' => $r->balance,
                    'invoice_number' => $r->invoice_number,
                    'customer_name' => $r->customer_name,
                    'notes' => $r->notes,
                ];
            })->values(),
        ]);
    }

    /**
     * Return inbound "parts/batches" for a product (sheet).
     * A batch is defined as one inbound row (input > 0), and its outbounds are rows after it
     * until the next inbound row (or end of sheet), where output > 0.
     */
    private function getProductBatches()
    {
        $sheetName = trim((string) request('sheet_name'));
        if ($sheetName === '') {
            return response()->json(['success' => false, 'message' => 'missing sheet_name'], 422);
        }

        $query = BelzonaInventory::query()
            ->where('sheet_name', $sheetName)
            ->orderBy('sheet_row_number', 'asc');

        if (request()->filled('filter_date_from')) {
            $query->whereDate('date', '>=', request('filter_date_from'));
        }
        if (request()->filled('filter_date_to')) {
            $query->whereDate('date', '<=', request('filter_date_to'));
        }

        $rows = $query->get([
            'belzona_inventory_id',
            'sheet_name',
            'sheet_row_number',
            'date',
            'date_raw',
            'input',
            'output',
            'balance',
            'invoice_number',
            'customer_name',
            'notes',
            'product_name',
            'product_weight_raw',
        ]);

        $batches = [];
        $current = null;

        foreach ($rows as $r) {
            $isInbound = ((float) $r->input) > 0;

            if ($isInbound) {
                // close previous batch
                if ($current !== null) {
                    $current['remaining'] = (float) $current['input'] - (float) $current['out_total'];
                    $batches[] = $current;
                }

                $label = $this->extractInboundLabel($r);

                $current = [
                    'inbound_id' => $r->belzona_inventory_id,
                    'inbound_row_number' => (int) $r->sheet_row_number,
                    'sheet_name' => $r->sheet_name,
                    'product_name' => $r->product_name,
                    'product_weight_raw' => $r->product_weight_raw,
                    'label' => $label,
                    'date' => $this->formatDateYmd($r->date),
                    'date_raw' => $r->date_raw,
                    'invoice_number' => $r->invoice_number,
                    'input' => (float) $r->input,
                    'out_total' => 0.0,
                    'out_count' => 0,
                    'remaining' => null,
                ];
                continue;
            }

            // outbound rows belong to current batch (until next inbound)
            if ($current !== null && ((float) $r->output) > 0) {
                $current['out_total'] += (float) $r->output;
                $current['out_count'] += 1;
            }
        }

        if ($current !== null) {
            $current['remaining'] = (float) $current['input'] - (float) $current['out_total'];
            $batches[] = $current;
        }

        $totals = [
            'batches_count' => count($batches),
            'input_total' => array_sum(array_map(fn ($b) => (float) ($b['input'] ?? 0), $batches)),
            'out_total' => array_sum(array_map(fn ($b) => (float) ($b['out_total'] ?? 0), $batches)),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'sheet_name' => $sheetName,
                'totals' => $totals,
                'batches' => $batches,
            ],
        ]);
    }

    /**
     * Return outbound rows for a given inbound batch (identified by inbound_row_number).
     */
    private function getBatchOutbounds()
    {
        $sheetName = trim((string) request('sheet_name'));
        $inboundRowNumber = (int) request('inbound_row_number', 0);

        if ($sheetName === '' || $inboundRowNumber <= 0) {
            return response()->json(['success' => false, 'message' => 'missing sheet_name or inbound_row_number'], 422);
        }

        // find inbound row
        $inbound = BelzonaInventory::query()
            ->where('sheet_name', $sheetName)
            ->where('sheet_row_number', $inboundRowNumber)
            ->first();

        if (!$inbound) {
            return response()->json(['success' => false, 'message' => 'inbound row not found'], 404);
        }

        // find next inbound row number
        $nextInboundRowNumber = (int) BelzonaInventory::query()
            ->where('sheet_name', $sheetName)
            ->where('sheet_row_number', '>', $inboundRowNumber)
            ->where('input', '>', 0)
            ->min('sheet_row_number');

        // outbound window: (inboundRowNumber, nextInboundRowNumber)
        $outQuery = BelzonaInventory::query()
            ->where('sheet_name', $sheetName)
            ->where('sheet_row_number', '>', $inboundRowNumber);

        if ($nextInboundRowNumber > 0) {
            $outQuery->where('sheet_row_number', '<', $nextInboundRowNumber);
        }

        // optional date filters (apply to outbounds)
        if (request()->filled('filter_date_from')) {
            $outQuery->whereDate('date', '>=', request('filter_date_from'));
        }
        if (request()->filled('filter_date_to')) {
            $outQuery->whereDate('date', '<=', request('filter_date_to'));
        }

        $outbounds = $outQuery
            ->where('output', '>', 0)
            ->orderBy('sheet_row_number', 'asc')
            ->limit(500)
            ->get();

        $label = $this->extractInboundLabel($inbound);

        $outTotal = (float) $outbounds->sum('output');

        return response()->json([
            'success' => true,
            'data' => [
                'sheet_name' => $sheetName,
                'inbound' => [
                    'inbound_id' => $inbound->belzona_inventory_id,
                    'inbound_row_number' => (int) $inbound->sheet_row_number,
                    'date' => $this->formatDateYmd($inbound->date),
                    'date_raw' => $inbound->date_raw,
                    'invoice_number' => $inbound->invoice_number,
                    'label' => $label,
                    'input' => (float) $inbound->input,
                    'out_total' => $outTotal,
                    'remaining' => (float) $inbound->input - $outTotal,
                ],
                'outbounds' => $outbounds->map(function ($r) {
                    return [
                        'row_number' => (int) $r->sheet_row_number,
                        'date_raw' => $r->date_raw,
                        'date' => $this->formatDateYmd($r->date),
                        'output' => (float) $r->output,
                        'invoice_number' => $r->invoice_number,
                        'customer_name' => $r->customer_name,
                        'notes' => $r->notes,
                        'balance' => (float) $r->balance,
                    ];
                })->values(),
            ],
        ]);
    }

    /**
     * Try to extract a human-readable inbound label ("واردات پرونده ... پارت ...")
     * from customer_name/notes/invoice_number.
     */
    private function extractInboundLabel($row): string
    {
        $candidates = [
            (string) ($row->notes ?? ''),
            (string) ($row->customer_name ?? ''),
            (string) ($row->invoice_number ?? ''),
        ];

        foreach ($candidates as $t) {
            $t = trim($t);
            if ($t === '') continue;
            if (mb_strpos($t, 'واردات') !== false || mb_strpos($t, 'پرونده') !== false || mb_strpos($t, 'پارت') !== false) {
                return $t;
            }
        }

        return 'ورود';
    }

    /**
     * Date column may be stored as string; return YYYY-mm-dd safely.
     */
    private function formatDateYmd($value): ?string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        if (is_string($value) && $value !== '') {
            // expected formats: "YYYY-mm-dd" or "YYYY-mm-dd HH:ii:ss"
            return substr($value, 0, 10);
        }
        return null;
    }

    /**
     * Show the form for creating a new inventory record
     * @param object CategoryRepository category repository
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //basic page settings
        $page = $this->pageSettings('create');
        return response()->view('pages.belzona-inventory.create', compact('page'));
    }

    /**
     * Store a newly created inventory record in storage.
     * @param object InventoryStoreValidation validation
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'product_name' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'input' => 'nullable',
            'output' => 'nullable',
            'balance' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
        ]);

        //create the inventory record
        if (!$id = $this->belzonaInventoryRepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the inventory record object (friendly for dispatching events)
        return redirect('/belzona-inventory/' . $id);
    }

    /**
     * Display the specified inventory record
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the inventory record
        $query = $this->belzonaInventoryRepo->search($id);

        //not found
        if (!$belzonaInventory = $query->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('show');

        //reponse payload
        return response()->view('pages.belzona-inventory.show', compact('page', 'belzonaInventory'));
    }

    /**
     * Show the form for editing the specified inventory record
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the inventory record
        $query = $this->belzonaInventoryRepo->search($id);

        //not found
        if (!$belzonaInventory = $query->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('edit');

        return response()->view('pages.belzona-inventory.edit', compact('page', 'belzonaInventory'));
    }

    /**
     * Update the specified inventory record in storage.
     * @param object InventoryStoreValidation validation
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->validate([
            'product_name' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'input' => 'nullable',
            'output' => 'nullable',
            'balance' => 'nullable',
            'invoice_number' => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
        ]);

        //update the inventory record
        if (!$this->belzonaInventoryRepo->update($id)) {
            abort(409);
        }

        return redirect('/belzona-inventory/' . $id);
    }

    /**
     * Remove the specified inventory record from storage.
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the inventory record
        $query = $this->belzonaInventoryRepo->search($id);

        //not found
        if (!$belzonaInventory = $query->first()) {
            abort(404);
        }

        //remove the inventory record
        $belzonaInventory->delete();

        return redirect('/belzona-inventory');
    }

    /**
     * Show the form for editing the specified inventory record
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    /**
     * basic page settings for this section of the app
     * @param string $section name
     * @param array $data any other data
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'page' => $section,
            'crumbs' => [
                __('lang.accounting'),
                'انبار بلزونا',
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'انبار بلزونا',
            'heading' => 'انبار بلزونا',
            'mainmenu_accounting' => 'active',
            'submenu_belzona_inventory' => 'active',
        ];

        return $page;
    }

}
