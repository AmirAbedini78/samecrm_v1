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
use App\Models\Invoice;
use App\Models\InvoiceSettlement;
use App\Repositories\BelzonaInventoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

        // Resolve invoice number from Belzona to system invoice id
        if (request()->get('action') === 'resolve_invoice') {
            return $this->resolveInvoiceByNumber();
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

        // Inbound list (all products) for the sales manager view
        if (request()->get('action') === 'inbound_datatables') {
            return $this->getInboundDataTables();
        }

        // Inbound summary (totals + latest inbound)
        if (request()->get('action') === 'inbound_summary') {
            return $this->getInboundSummary();
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
     * Resolve an invoice (bill_invoiceid) from a Belzona invoice_number string.
     * Returns URLs suitable for opening the standard invoice modal (/invoices/{id}/edit).
     */
    private function resolveInvoiceByNumber() {
        $raw = trim((string) request('invoice_number', ''));
        if ($raw === '') {
            return response()->json([
                'success' => false,
                'message' => 'شماره فاکتور ارسال نشده است.',
            ], 422);
        }

        // Attempt to extract numeric id (same approach used in InvoiceRepository search)
        $candidate = $raw;
        $prefix = (string) config('system.settings_invoices_prefix');
        if ($prefix !== '') {
            $candidate = str_replace($prefix, '', $candidate);
        }
        $candidate = preg_replace("/[^0-9]/", "", (string) $candidate);
        $candidate = ltrim((string) $candidate, '0');

        $invoice = null;
        if ($candidate !== '' && is_numeric($candidate)) {
            $invoice = Invoice::where('bill_invoiceid', (int) $candidate)->first();
        }

        // Fallback: if raw is numeric
        if (!$invoice && is_numeric($raw)) {
            $invoice = Invoice::where('bill_invoiceid', (int) $raw)->first();
        }

        if ($invoice) {
            $id = (int) $invoice->bill_invoiceid;

            return response()->json([
                'success' => true,
                'data' => [
                    'type' => 'invoice',
                    'bill_invoiceid' => $id,
                    'formatted_bill_invoiceid' => $invoice->formatted_bill_invoiceid,
                    // modal edit endpoint (returns JSON for commonModal)
                    'edit_url' => urlResource('/invoices/' . $id . '/edit'),
                    // standard view page
                    'view_url' => url('/invoices/' . $id),
                ],
            ]);
        }

        // Fallback 2: invoice settlements (accounting "فاکتور و تسویه")
        $settlement = InvoiceSettlement::where('document_number', $raw)->first();
        if (!$settlement && $candidate !== '') {
            $settlement = InvoiceSettlement::where('document_number', $candidate)->first();
        }

        if ($settlement) {
            return response()->json([
                'success' => true,
                'data' => [
                    'type' => 'settlement',
                    'invoice_settlement_id' => $settlement->invoice_settlement_id,
                    'document_number' => $settlement->document_number,
                    'document_date' => $settlement->document_date,
                    'customer_name' => $settlement->customer_name,
                    'base_net_amount' => $settlement->base_net_amount,
                    'paid_amount' => $settlement->paid_amount,
                    'balance_amount' => $settlement->balance_amount,
                    'currency' => $settlement->currency,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'فاکتور/تسویه با این شماره پیدا نشد.',
        ], 404);
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
        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $baseQuery->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $baseQuery->whereDate('date', '<=', $toDate);
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
            10 => 'shelf_life_years',
            11 => 'expiry_date',
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
            $expiryDate = $item->expiry_date;
            if ($expiryDate === null && $item->date && $item->shelf_life_years) {
                $d = $item->date instanceof \DateTimeInterface ? $item->date : \Carbon\Carbon::parse($item->date);
                $expiryDate = $d->copy()->addYears((int) round((float) $item->shelf_life_years));
            }
            $expiryFormatted = $expiryDate ? (\Carbon\Carbon::parse($expiryDate)->format('Y-m-d')) : null;
            $remainingText = $this->remainingShelfLifeText($expiryDate);

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
                'shelf_life_years' => $item->shelf_life_years,
                'expiry_date' => $expiryFormatted,
                'remaining_shelf_life' => $remainingText,
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

    /**
     * Set shelf life (years) for all rows of a product (sheet_name). Updates expiry_date = date + years.
     */
    public function setShelfLife()
    {
        $sheetName = trim((string) request('sheet_name', ''));
        $years = request('shelf_life_years');
        if ($sheetName === '') {
            return response()->json(['success' => false, 'message' => 'sheet_name is required'], 422);
        }
        $years = is_numeric($years) ? (float) $years : null;
        if ($years === null || $years <= 0) {
            return response()->json(['success' => false, 'message' => 'shelf_life_years must be a positive number'], 422);
        }

        $rows = BelzonaInventory::where('sheet_name', $sheetName)->get();
        $updated = 0;
        foreach ($rows as $row) {
            $row->shelf_life_years = $years;
            if ($row->date) {
                $d = $row->date instanceof \DateTimeInterface ? $row->date : \Carbon\Carbon::parse($row->date);
                $row->expiry_date = $d->copy()->addYears((int) round($years))->format('Y-m-d');
            }
            $row->save();
            $updated++;
        }

        return response()->json([
            'success' => true,
            'message' => 'مدت ماندگاری برای ' . $updated . ' ردیف به‌روز شد.',
            'updated' => $updated,
        ]);
    }

    private function getProductSummary()
    {
        $sheetName = trim((string) request('sheet_name'));
        if ($sheetName === '') {
            return response()->json(['success' => false, 'message' => 'missing sheet_name'], 422);
        }

        $query = BelzonaInventory::query()->where('sheet_name', $sheetName);

        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('date', '<=', $toDate);
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

        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('date', '<=', $toDate);
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

        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('date', '<=', $toDate);
        }

        $batchSelect = [
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
        ];
        if (Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
            $batchSelect[] = 'shelf_life_years';
            $batchSelect[] = 'expiry_date';
        }
        $rows = $query->get($batchSelect);

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

                $expiry = $r->expiry_date ?? null;
                if ($expiry === null && $r->date && isset($r->shelf_life_years) && $r->shelf_life_years) {
                    $d = $r->date instanceof \DateTimeInterface ? $r->date : \Carbon\Carbon::parse($r->date);
                    $expiry = $d->copy()->addYears((int) round((float) $r->shelf_life_years));
                }
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
                if (Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
                    $current['shelf_life_years'] = $r->shelf_life_years ?? null;
                    $current['expiry_date'] = $expiry ? \Carbon\Carbon::parse($expiry)->format('Y-m-d') : null;
                    $current['remaining_shelf_life'] = $expiry ? $this->remainingShelfLifeText($expiry) : null;
                }
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
        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $outQuery->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $outQuery->whereDate('date', '<=', $toDate);
        }

        $outbounds = $outQuery
            ->where('output', '>', 0)
            ->orderBy('sheet_row_number', 'asc')
            ->limit(500)
            ->get();

        $label = $this->extractInboundLabel($inbound);

        $outTotal = (float) $outbounds->sum('output');

        $inboundExpiry = $inbound->expiry_date;
        if ($inboundExpiry === null && $inbound->date && $inbound->shelf_life_years) {
            $d = $inbound->date instanceof \DateTimeInterface ? $inbound->date : \Carbon\Carbon::parse($inbound->date);
            $inboundExpiry = $d->copy()->addYears((int) round((float) $inbound->shelf_life_years));
        }
        $inboundRemainingText = $inboundExpiry ? $this->remainingShelfLifeText($inboundExpiry) : null;

        $inboundPayload = [
            'inbound_id' => $inbound->belzona_inventory_id,
            'inbound_row_number' => (int) $inbound->sheet_row_number,
            'date' => $this->formatDateYmd($inbound->date),
            'date_raw' => $inbound->date_raw,
            'invoice_number' => $inbound->invoice_number,
            'label' => $label,
            'input' => (float) $inbound->input,
            'out_total' => $outTotal,
            'remaining' => (float) $inbound->input - $outTotal,
        ];
        if (Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
            $inboundPayload['shelf_life_years'] = $inbound->shelf_life_years;
            $inboundPayload['expiry_date'] = $inboundExpiry ? \Carbon\Carbon::parse($inboundExpiry)->format('Y-m-d') : null;
            $inboundPayload['remaining_shelf_life'] = $inboundRemainingText;
        }

        $outboundsPayload = $outbounds->map(function ($r) {
            $expiry = $r->expiry_date;
            if ($expiry === null && $r->date && $r->shelf_life_years) {
                $d = $r->date instanceof \DateTimeInterface ? $r->date : \Carbon\Carbon::parse($r->date);
                $expiry = $d->copy()->addYears((int) round((float) $r->shelf_life_years));
            }
            $remText = $expiry ? $this->remainingShelfLifeText($expiry) : null;
            $row = [
                'row_number' => (int) $r->sheet_row_number,
                'date_raw' => $r->date_raw,
                'date' => $this->formatDateYmd($r->date),
                'output' => (float) $r->output,
                'invoice_number' => $r->invoice_number,
                'customer_name' => $r->customer_name,
                'notes' => $r->notes,
                'balance' => (float) $r->balance,
            ];
            if (Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
                $row['shelf_life_years'] = $r->shelf_life_years;
                $row['expiry_date'] = $expiry ? \Carbon\Carbon::parse($expiry)->format('Y-m-d') : null;
                $row['remaining_shelf_life'] = $remText;
            }
            return $row;
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'sheet_name' => $sheetName,
                'inbound' => $inboundPayload,
                'outbounds' => $outboundsPayload,
            ],
        ]);
    }

    /**
     * DataTables JSON for "inbound batches" across all products.
     * An inbound batch is any row where input > 0.
     * Outbounds are summed from rows after inbound until next inbound within same sheet.
     */
    private function getInboundDataTables()
    {
        // If required columns are missing (migration not run yet), return safe empty dataset
        if (
            !Schema::hasTable('belzona_inventories') ||
            !Schema::hasColumn('belzona_inventories', 'sheet_name') ||
            !Schema::hasColumn('belzona_inventories', 'sheet_row_number') ||
            !Schema::hasColumn('belzona_inventories', 'input') ||
            !Schema::hasColumn('belzona_inventories', 'output')
        ) {
            return response()->json([
                'draw' => (int) request('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }

        try {
        $nextInboundSub = "(SELECT MIN(n.sheet_row_number)
            FROM belzona_inventories n
            WHERE n.sheet_name = i.sheet_name
              AND n.sheet_row_number > i.sheet_row_number
              AND n.input > 0)";

        $windowUpper = "COALESCE($nextInboundSub, 2147483647)";

        $outTotalSub = "(SELECT COALESCE(SUM(o.output), 0)
            FROM belzona_inventories o
            WHERE o.sheet_name = i.sheet_name
              AND o.sheet_row_number > i.sheet_row_number
              AND o.sheet_row_number < $windowUpper
              AND o.output > 0)";

        $outCountSub = "(SELECT COUNT(*)
            FROM belzona_inventories o
            WHERE o.sheet_name = i.sheet_name
              AND o.sheet_row_number > i.sheet_row_number
              AND o.sheet_row_number < $windowUpper
              AND o.output > 0)";

        // crude label extraction in SQL (best-effort)
        $labelSql = "CASE
            WHEN i.notes IS NOT NULL AND (i.notes LIKE '%واردات%' OR i.notes LIKE '%پرونده%' OR i.notes LIKE '%پارت%') THEN i.notes
            WHEN i.customer_name IS NOT NULL AND (i.customer_name LIKE '%واردات%' OR i.customer_name LIKE '%پرونده%' OR i.customer_name LIKE '%پارت%') THEN i.customer_name
            WHEN i.invoice_number IS NOT NULL AND (i.invoice_number LIKE '%واردات%' OR i.invoice_number LIKE '%پرونده%' OR i.invoice_number LIKE '%پارت%') THEN i.invoice_number
            WHEN i.notes IS NOT NULL AND i.notes != '' THEN i.notes
            ELSE 'ورود'
        END";

        $selectColumns = [
            'i.belzona_inventory_id',
            'i.sheet_name',
            'i.product_name',
            'i.product_weight_raw',
            'i.sheet_row_number',
            'i.date_raw',
            'i.date',
            'i.input',
            DB::raw("$labelSql as inbound_label"),
            DB::raw("$outTotalSub as out_total"),
            DB::raw("$outCountSub as out_count"),
            DB::raw("(i.input - $outTotalSub) as remaining"),
        ];
        if (Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
            $selectColumns[] = 'i.shelf_life_years';
        }
        if (Schema::hasColumn('belzona_inventories', 'expiry_date')) {
            $selectColumns[] = 'i.expiry_date';
        }
        $baseQuery = DB::table('belzona_inventories as i')
            ->where('i.input', '>', 0)
            ->select($selectColumns);

        // optional product filter
        if (request()->filled('sheet_name')) {
            $baseQuery->where('i.sheet_name', 'LIKE', '%' . request('sheet_name') . '%');
        }

        // optional date range on parsed date
        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $baseQuery->whereDate('i.date', '>=', $fromDate);
        }
        if ($toDate) {
            $baseQuery->whereDate('i.date', '<=', $toDate);
        }

        $recordsTotal = (clone $baseQuery)->count();

        // global search
        $searchValue = request('search.value');
        if (!empty($searchValue)) {
            $sv = trim((string) $searchValue);
            $baseQuery->where(function ($q) use ($sv) {
                $q->where('i.sheet_name', 'LIKE', "%{$sv}%")
                    ->orWhere('i.product_name', 'LIKE', "%{$sv}%")
                    ->orWhere('i.product_weight_raw', 'LIKE', "%{$sv}%")
                    ->orWhere('i.date_raw', 'LIKE', "%{$sv}%")
                    ->orWhere('i.invoice_number', 'LIKE', "%{$sv}%")
                    ->orWhere('i.customer_name', 'LIKE', "%{$sv}%")
                    ->orWhere('i.notes', 'LIKE', "%{$sv}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        // ordering
        $orderColIndex = (int) request('order.0.column', 0);
        $orderDir = request('order.0.dir', 'desc');
        $orderDir = in_array($orderDir, ['asc', 'desc'], true) ? $orderDir : 'desc';

        // map datatable columns to DB fields
        // 0 date_raw, 1 sheet_name, 2 inbound_label, 3 input, 4 out_total, 5 remaining, 6 out_count
        switch ($orderColIndex) {
            case 0:
                // best-effort: order by parsed date then row number
                $baseQuery->orderByRaw('CASE WHEN i.date IS NULL THEN 1 ELSE 0 END asc')
                    ->orderBy('i.date', $orderDir)
                    ->orderBy('i.belzona_inventory_id', $orderDir);
                break;
            case 1:
                $baseQuery->orderBy('i.sheet_name', $orderDir);
                break;
            case 3:
                $baseQuery->orderBy('i.input', $orderDir);
                break;
            default:
                $baseQuery->orderByRaw('CASE WHEN i.date IS NULL THEN 1 ELSE 0 END asc')
                    ->orderBy('i.date', 'desc')
                    ->orderBy('i.belzona_inventory_id', 'desc');
                break;
        }

        $start = (int) request('start', 0);
        $length = (int) request('length', 25);
        if ($length <= 0) {
            $length = 25;
        }

        $rows = $baseQuery->skip($start)->take($length)->get();
        $hasShelfLife = Schema::hasColumn('belzona_inventories', 'shelf_life_years');

        $data = [];
        foreach ($rows as $r) {
            $expiryFormatted = null;
            $remainingText = '—';
            if ($hasShelfLife) {
                $expiryDate = isset($r->expiry_date) ? $r->expiry_date : null;
                if ($expiryDate === null && !empty($r->date) && isset($r->shelf_life_years) && $r->shelf_life_years !== null && $r->shelf_life_years !== '') {
                    $d = \Carbon\Carbon::parse($r->date);
                    $expiryDate = $d->copy()->addYears((int) round((float) $r->shelf_life_years));
                }
                if ($expiryDate) {
                    $remainingText = $this->remainingShelfLifeText($expiryDate);
                    $expiryFormatted = \Carbon\Carbon::parse($expiryDate)->format('Y-m-d');
                }
            }

            $row = [
                'inbound_id' => $r->belzona_inventory_id,
                'sheet_name' => $r->sheet_name,
                'product_weight_raw' => $r->product_weight_raw ?? null,
                'date_raw' => $r->date_raw,
                'inbound_label' => $r->inbound_label,
                'input' => (float) $r->input,
                'out_total' => (float) $r->out_total,
                'remaining' => (float) $r->remaining,
                'out_count' => (int) $r->out_count,
                'inbound_row_number' => (int) $r->sheet_row_number,
                'shelf_life_years' => $hasShelfLife && isset($r->shelf_life_years) ? $r->shelf_life_years : null,
                'expiry_date' => $expiryFormatted,
                'remaining_shelf_life' => $remainingText,
            ];
            $data[] = $row;
        }

        return response()->json([
            'draw' => (int) request('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Belzona inbound_datatables error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'draw' => (int) request('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Inbound DataTables failed (check DB columns/migrations).',
            ]);
        }
    }

    /**
     * Summary for inbound list: total inbound count/sum + latest inbound.
     */
    private function getInboundSummary()
    {
        try {
        $query = BelzonaInventory::query()->where('input', '>', 0);

        $fromDate = $this->normalizeFilterDate(request('filter_date_from'));
        $toDate = $this->normalizeFilterDate(request('filter_date_to'));
        if ($fromDate) {
            $query->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('date', '<=', $toDate);
        }
        if (request()->filled('sheet_name')) {
            $query->where('sheet_name', 'LIKE', '%' . request('sheet_name') . '%');
        }

        $count = (clone $query)->count();
        $sumInput = (float) ((clone $query)->sum('input') ?? 0);

        $latest = (clone $query)
            ->orderByRaw('CASE WHEN date IS NULL THEN 1 ELSE 0 END asc')
            ->orderBy('date', 'desc')
            ->orderBy('sheet_row_number', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'inbound_count' => $count,
                'inbound_sum' => $sumInput,
                'latest' => $latest ? [
                    'sheet_name' => $latest->sheet_name,
                    'product_weight_raw' => $latest->product_weight_raw,
                    'date_raw' => $latest->date_raw,
                    'input' => (float) $latest->input,
                    'label' => $this->extractInboundLabel($latest),
                    'inbound_row_number' => (int) $latest->sheet_row_number,
                ] : null,
            ],
        ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'data' => [
                    'inbound_count' => 0,
                    'inbound_sum' => 0,
                    'latest' => null,
                ],
            ]);
        }
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
     * Normalize filter date values.
     * Accepts:
     * - Gregorian YYYY-MM-DD
     * - Jalali YYYY/M/D or YYYY/MM/DD (e.g. 1403/1/1)
     *
     * Returns Gregorian YYYY-MM-DD or null.
     */
    private function normalizeFilterDate($value): ?string
    {
        $value = trim((string) ($value ?? ''));
        if ($value === '') {
            return null;
        }

        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^[0-9]{4}\\/[0-9]{1,2}\\/[0-9]{1,2}$/', $value)) {
            $parts = explode('/', $value);
            $jy = (int) $parts[0];
            $jm = (int) $parts[1];
            $jd = (int) $parts[2];

            if ($jy < 1300 || $jm < 1 || $jm > 12 || $jd < 1 || $jd > 31) {
                return null;
            }

            [$gy, $gm, $gd] = $this->jalaliToGregorian($jy, $jm, $jd);
            return sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
        }

        return null;
    }

    /**
     * Return remaining shelf life text: سال و ماه (و روز اگر کم باشد). اعداد به فارسی.
     */
    private function remainingShelfLifeText($expiryDate): string
    {
        if ($expiryDate === null) {
            return '—';
        }
        $expiry = $expiryDate instanceof \DateTimeInterface ? $expiryDate : \Carbon\Carbon::parse($expiryDate);
        $today = \Carbon\Carbon::today();
        $days = (int) $today->diffInDays($expiry, false);
        if ($days < 0) {
            return 'منقضی شده';
        }
        if ($days === 0) {
            return 'امروز';
        }
        $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $toPersian = function ($n) use ($persianDigits) {
            return str_replace(range('0', '9'), $persianDigits, (string) (int) $n);
        };
        if ($days <= 31) {
            return $toPersian($days) . ' روز مانده';
        }
        $years = (int) floor($days / 365);
        $remainingDays = $days % 365;
        $months = (int) round($remainingDays / 30);
        if ($months >= 12) {
            $years += 1;
            $months = 0;
        }
        if ($years > 0 && $months > 0) {
            return $toPersian($years) . ' سال و ' . $toPersian($months) . ' ماه مانده';
        }
        if ($years > 0) {
            return $toPersian($years) . ' سال مانده';
        }
        return $toPersian($months) . ' ماه مانده';
    }

    /**
     * Jalali to Gregorian conversion (pure PHP, no deps).
     * Returns array [gy, gm, gd].
     */
    private function jalaliToGregorian($jy, $jm, $jd)
    {
        $jy = (int) $jy - 979;
        $jm = (int) $jm - 1;
        $jd = (int) $jd - 1;

        $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];
        $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];

        $j_day_no = 365 * $jy + $this->div($jy, 33) * 8 + $this->div(($jy % 33) + 3, 4);
        for ($i = 0; $i < $jm; $i++) {
            $j_day_no += $j_days_in_month[$i];
        }
        $j_day_no += $jd;

        $g_day_no = $j_day_no + 79;

        $gy = 1600 + 400 * $this->div($g_day_no, 146097);
        $g_day_no = $g_day_no % 146097;

        $leap = true;
        if ($g_day_no >= 36525) {
            $g_day_no--;
            $gy += 100 * $this->div($g_day_no, 36524);
            $g_day_no = $g_day_no % 36524;

            if ($g_day_no >= 365) {
                $g_day_no++;
            } else {
                $leap = false;
            }
        }

        $gy += 4 * $this->div($g_day_no, 1461);
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;
            $g_day_no--;
            $gy += $this->div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }

        for ($i = 0; $g_day_no >= ($g_days_in_month[$i] + (($i == 1 && $leap) ? 1 : 0)); $i++) {
            $g_day_no -= $g_days_in_month[$i] + (($i == 1 && $leap) ? 1 : 0);
        }

        $gm = $i + 1;
        $gd = $g_day_no + 1;

        return [$gy, $gm, $gd];
    }

    /**
     * Integer division with PHP < 7 compatibility.
     */
    private function div($a, $b)
    {
        if (function_exists('intdiv')) {
            return intdiv($a, $b);
        }
        return (int) floor($a / $b);
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
            'page_route' => 'belzona-inventory',
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
