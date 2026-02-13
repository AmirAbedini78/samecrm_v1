<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\BelzonaInventory as BelzonaInventoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;

class BelzonaInventory extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the import form
     */
    public function index()
    {
        $page = [
            'page' => 'import',
            'crumbs' => [
                __('lang.accounting'),
                'انبار بلزونا',
                __('lang.import'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => 'ایمپورت انبار بلزونا',
            'heading' => 'ایمپورت انبار بلزونا',
            'mainmenu_accounting' => 'active',
            'submenu_belzona_inventory' => 'active',
        ];

        return view('pages.import.belzona-inventory', compact('page'));
    }

    /**
     * Process the import
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'attachments' => 'required|array',
                'attachments.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        if (!$request->hasFile('attachments') || !$request->file('attachments')[0]) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded',
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        $file = $request->file('attachments')[0];
        $file_path = $file->getPathname();

        Log::info("BelzonaInventory Import Debug", [
            'file_path' => $file_path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        if (!file_exists($file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found: ' . $file_path,
                'imported' => 0,
                'skipped' => 0,
            ], 404);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $allowed_extensions = ['xlsx', 'xls', 'csv'];

        if (!in_array($extension, $allowed_extensions)) {
            abort(409, __('lang.invalid_file_type'));
        }

        $import_results = [
            'success' => false,
            'imported' => 0,
            'skipped' => 0,
            'message' => '',
        ];

        try {
            // If XLSX/XLS, import ALL sheets and parse weight from sheet title
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Optional: avoids ZipArchive issues on some hosts
                // Use string-based constant to keep static analyzers happy.
                if (method_exists('PhpOffice\\PhpSpreadsheet\\Settings', 'setZipClass')) {
                    $const = 'PhpOffice\\PhpSpreadsheet\\Settings::PCLZIP';
                    if (defined($const)) {
                        call_user_func(['PhpOffice\\PhpSpreadsheet\\Settings', 'setZipClass'], constant($const));
                    }
                }

                $spreadsheet = IOFactory::load($file_path);

                $imported = 0;
                $skipped = 0;
                $sheetCount = 0;

                foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                    $sheetCount++;
                    $sheetName = trim($worksheet->getTitle());

                    $sheetMeta = $this->parseSheetTitle($sheetName);
                    $rows = $worksheet->toArray(null, true, true, true);

                    // header row is expected on first row
                    $header = isset($rows[1]) ? $rows[1] : [];
                    $map = $this->mapHeaderColumns($header);

                    // iterate from 2nd row
                    foreach ($rows as $rowNumber => $row) {
                        if ((int)$rowNumber === 1) {
                            continue;
                        }

                        $rowPayload = $this->buildRowPayload($row, $map, $sheetMeta, $sheetName, $rowNumber);
                        if ($rowPayload === null) {
                            $skipped++;
                            continue;
                        }

                        // idempotency: skip if this sheet row was already imported
                        if (!empty($rowPayload['sheet_name']) && !empty($rowPayload['sheet_row_number'])) {
                            $exists = BelzonaInventoryModel::where('sheet_name', $rowPayload['sheet_name'])
                                ->where('sheet_row_number', $rowPayload['sheet_row_number'])
                                ->exists();
                            if ($exists) {
                                $skipped++;
                                continue;
                            }
                        }

                        BelzonaInventoryModel::create($rowPayload);
                        $imported++;
                    }
                }

                $import_results = [
                    'success' => true,
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'message' => "Successfully imported {$imported} rows from {$sheetCount} sheets",
                ];
            } else {
                // CSV: keep existing behavior (single-sheet like structure)
                // CSV parsing can be added later if needed
                $import_results = [
                    'success' => false,
                    'imported' => 0,
                    'skipped' => 0,
                    'message' => 'CSV import is not supported for Belzona inventory (please upload XLSX).',
                ];
            }
        } catch (\Exception $e) {
            $import_results = [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => 'Import failed: ' . $e->getMessage(),
            ];
            Log::error("BelzonaInventory import failed: " . $e->getMessage(), ['belzona-inventory.import']);
        }

        if ($request->ajax()) {
            return response()->json($import_results);
        }

        return redirect()->back()->with('import_results', $import_results);
    }

    /**
     * Parse sheet title like "1111 (1Kg)" or "1341 N (750 Gr)".
     */
    private function parseSheetTitle($sheetTitle)
    {
        $sheetTitle = trim((string) $sheetTitle);

        $product = trim(preg_replace('/\\s*\\(.*\\)\\s*$/u', '', $sheetTitle));
        $weightRaw = null;

        if (preg_match('/\\((.*?)\\)/u', $sheetTitle, $m)) {
            $weightRaw = trim($m[1]);
        }

        $weight = $this->parseWeight($weightRaw);

        return [
            'product_name' => $product ?: $sheetTitle,
            'product_weight_raw' => $weightRaw,
            'product_weight_value' => $weight['value'],
            'product_weight_unit' => $weight['unit'],
        ];
    }

    private function parseWeight($weightRaw)
    {
        if ($weightRaw === null || $weightRaw === '') {
            return ['value' => null, 'unit' => null];
        }

        $raw = strtolower(trim((string) $weightRaw));
        $raw = str_replace(['كيلو', 'کیلو', 'گرم', 'لیتر'], ['kg', 'kg', 'gr', 'lit'], $raw);
        $raw = str_replace([' ', "\t"], [' ', ' '], $raw);

        if (preg_match('/([0-9]+(?:\\.[0-9]+)?)\\s*(kg|gr|g|lit|l)/i', $raw, $m)) {
            $value = (float) $m[1];
            $unit = strtolower($m[2]);
            if ($unit === 'g') $unit = 'gr';
            if ($unit === 'l') $unit = 'lit';
            return ['value' => $value, 'unit' => $unit];
        }

        return ['value' => null, 'unit' => $raw];
    }

    /**
     * Map Persian headers to column letters.
     */
    private function mapHeaderColumns($headerRow)
    {
        $map = [];
        foreach ($headerRow as $col => $val) {
            $key = trim((string) $val);
            if ($key === '') continue;

            $map[$key] = $col;
        }
        return $map;
    }

    /**
     * Build a single DB row from Excel row.
     */
    private function buildRowPayload($row, $headerMap, $sheetMeta, $sheetName, $rowNumber)
    {
        // expected headers in this workbook
        $colRow = $headerMap['ردیف'] ?? 'A';
        $colDate = $headerMap['تاریخ'] ?? 'C';
        $colInput = $headerMap['ورودی'] ?? 'D';
        $colOutput = $headerMap['خروجی'] ?? 'E';
        $colBalance = $headerMap['مانده'] ?? 'F';
        $colInvoice = $headerMap['شماره فاکتور'] ?? 'G';
        $colCustomer = $headerMap['نام مشتری'] ?? 'H';
        $colProduct = $headerMap['نام محصول'] ?? 'B';

        $dateRaw = trim((string) ($row[$colDate] ?? ''));
        $dateParsed = $this->parseJalaliDateToGregorianDateTime($dateRaw);
        $input = $this->parseDecimal($row[$colInput] ?? null);
        $output = $this->parseDecimal($row[$colOutput] ?? null);
        $balance = $this->parseDecimal($row[$colBalance] ?? null);
        $invoice = trim((string) ($row[$colInvoice] ?? ''));
        $customer = trim((string) ($row[$colCustomer] ?? ''));

        // extra columns (I, J, ...) as notes
        $notes = '';
        foreach ($row as $col => $val) {
            if (in_array($col, [$colRow, $colDate, $colInput, $colOutput, $colBalance, $colInvoice, $colCustomer, $colProduct], true)) {
                continue;
            }
            $t = trim((string) $val);
            if ($t !== '') {
                $notes .= ($notes ? ' | ' : '') . $t;
            }
        }

        // skip empty rows
        $hasAny = ($dateRaw !== '' || $invoice !== '' || $customer !== '' || $notes !== '' || $input != 0 || $output != 0 || $balance != 0);
        if (!$hasAny) {
            return null;
        }

        // product from sheet title; fallback to cell value if sheet title parsing failed
        $productCell = trim((string) ($row[$colProduct] ?? ''));
        $productName = $sheetMeta['product_name'];
        $weightRaw = $sheetMeta['product_weight_raw'];
        $weightValue = $sheetMeta['product_weight_value'];
        $weightUnit = $sheetMeta['product_weight_unit'];

        if (($productName === '' || $productName === $sheetName) && $productCell !== '') {
            $cellMeta = $this->parseSheetTitle($productCell);
            $productName = $cellMeta['product_name'];
            $weightRaw = $cellMeta['product_weight_raw'];
            $weightValue = $cellMeta['product_weight_value'];
            $weightUnit = $cellMeta['product_weight_unit'];
        }

        return [
            'product_name' => $productName ?: $sheetName,
            'product_weight_raw' => $weightRaw,
            'product_weight_value' => $weightValue,
            'product_weight_unit' => $weightUnit,
            'sheet_name' => $sheetName,
            'sheet_row_number' => (int) $rowNumber,
            'date' => $dateParsed,
            'date_raw' => $dateRaw,
            'input' => $input,
            'output' => $output,
            'balance' => $balance,
            'invoice_number' => ($invoice !== '' ? $invoice : null),
            'customer_name' => ($customer !== '' ? $customer : null),
            'notes' => ($notes !== '' ? $notes : null),
        ];
    }

    private function parseDecimal($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = preg_replace('/[^0-9.-]/', '', (string) $value);
        return (float) $value;
    }

    /**
     * Convert Jalali-ish date strings to Gregorian datetime (Y-m-d 00:00:00).
     * Supports patterns seen in SERIES 1000.xlsx:
     * - 15/10/93  (d/m/yy)
     * - 94/12/16  (yy/m/d - year first)
     * - 1400/10/27 (yyyy/m/d - year first)
     *
     * Returns null if not parseable.
     */
    private function parseJalaliDateToGregorianDateTime($dateRaw)
    {
        $dateRaw = trim((string) $dateRaw);
        if ($dateRaw === '') {
            return null;
        }

        // ignore non-date values (e.g. "B-7580")
        if (!preg_match('/^[0-9]{1,4}\\/[0-9]{1,2}\\/[0-9]{1,2}$/', $dateRaw)) {
            return null;
        }

        $parts = explode('/', $dateRaw);
        if (count($parts) !== 3) {
            return null;
        }

        $a = (int) $parts[0];
        $b = (int) $parts[1];
        $c = (int) $parts[2];

        // decide order
        // if first part looks like year (>= 32), treat as Y/M/D; else D/M/Y
        if ($a >= 32) {
            $jy = $a;
            $jm = $b;
            $jd = $c;
        } else {
            $jd = $a;
            $jm = $b;
            $jy = $c;
        }

        // normalize 2-digit year to 13xx
        if ($jy < 100) {
            $jy += 1300;
        }

        // basic validation
        if ($jm < 1 || $jm > 12 || $jd < 1 || $jd > 31) {
            return null;
        }

        list($gy, $gm, $gd) = $this->jalaliToGregorian($jy, $jm, $jd);
        if (!$gy) {
            return null;
        }

        return sprintf('%04d-%02d-%02d 00:00:00', $gy, $gm, $gd);
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
     * Import shelf life (مدت ماندگاری) from Excel: read all sheets, map product code -> years, update belzona_inventories.
     */
    public function storeShelfLife(Request $request)
    {
        try {
            $request->validate([
                'shelf_life_file' => 'required|file|mimes:xlsx,xls|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'updated' => 0,
            ], 422);
        }

        $file = $request->file('shelf_life_file');
        $path = $file->getPathname();

        if (!file_exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found', 'updated' => 0], 404);
        }

        // Possible header names (exact and partial) for product and shelf life columns
        $productColumnKeys = [
            'کد محصول', 'نام محصول', 'محصول', 'کد', 'نام', 'product', 'product code', 'product name',
            'کد کالا', 'نام کالا', 'کالا', 'شماره محصول', 'کد کالا',
        ];
        $shelfLifeColumnKeys = [
            'مدت زمان ماندگاری (سال)', 'مدت زمان ماندگاری', 'مدت زمان ماندگاری محصول', 'مدت ماندگاری (سال)', 'مدت ماندگاری',
            'ماندگاری (سال)', 'ماندگاری', 'سال', 'shelf life', 'shelf life (years)', 'shelf_life', 'shelf_life_years',
            'years', 'مدت (سال)', 'مدت',
        ];

        $normalizeHeader = function ($s) {
            $s = trim(preg_replace('/\s+/u', ' ', (string) $s));
            return $s;
        };

        try {
            if (method_exists('PhpOffice\\PhpSpreadsheet\\Settings', 'setZipClass')) {
                $const = 'PhpOffice\\PhpSpreadsheet\\Settings::PCLZIP';
                if (defined($const)) {
                    call_user_func(['PhpOffice\\PhpSpreadsheet\\Settings', 'setZipClass'], constant($const));
                }
            }

            $spreadsheet = IOFactory::load($path);
            $productToYears = [];

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $rows = $worksheet->toArray(null, true, true, true);
                if (empty($rows)) {
                    continue;
                }

                $sheetName = trim($worksheet->getTitle());

                // Find header row: try rows 1, 2, 3 and pick first that contains a known header
                $headerRowIndex = null;
                $headerMap = [];
                $productCol = null;
                $shelfLifeCol = null;

                for ($hr = 1; $hr <= 3; $hr++) {
                    $header = isset($rows[$hr]) ? $rows[$hr] : [];
                    $headerMap = [];
                    foreach ($header as $col => $val) {
                        $key = $normalizeHeader($val);
                        if ($key !== '') {
                            $headerMap[$key] = $col;
                        }
                    }
                    foreach ($productColumnKeys as $k) {
                        if (isset($headerMap[$k])) {
                            $productCol = $headerMap[$k];
                            break;
                        }
                    }
                    foreach (array_keys($headerMap) as $h) {
                        if (stripos($h, 'محصول') !== false || stripos($h, 'کد') !== false || stripos($h, 'product') !== false) {
                            $productCol = $productCol ?? $headerMap[$h];
                            break;
                        }
                    }
                    foreach ($shelfLifeColumnKeys as $k) {
                        if (isset($headerMap[$k])) {
                            $shelfLifeCol = $headerMap[$k];
                            break;
                        }
                    }
                    foreach (array_keys($headerMap) as $h) {
                        if (preg_match('/(سال|year|ماندگاری|shelf|مدت)/ui', $h)) {
                            $shelfLifeCol = $shelfLifeCol ?? $headerMap[$h];
                            break;
                        }
                    }
                    if ($productCol !== null || $shelfLifeCol !== null) {
                        $headerRowIndex = $hr;
                        break;
                    }
                }

                if ($headerRowIndex === null) {
                    // Fallback: assume first row is header, A=product, find column with number (years)
                    $headerRowIndex = 1;
                    $productCol = 'A';
                    $firstRow = isset($rows[1]) ? $rows[1] : [];
                    $shelfLifeCol = 'B';
                    foreach ($firstRow as $col => $val) {
                        if ($col === 'A') continue;
                        $v = $this->parseDecimal($val);
                        if ($v >= 0.5 && $v <= 20) {
                            $shelfLifeCol = $col;
                            break;
                        }
                    }
                } else {
                    if ($productCol === null) {
                        $productCol = 'A';
                    }
                    if ($shelfLifeCol === null) {
                        foreach (array_keys($headerMap) as $h) {
                            if (preg_match('/(سال|year|ماندگاری|shelf)/ui', $h)) {
                                $shelfLifeCol = $headerMap[$h];
                                break;
                            }
                        }
                        $shelfLifeCol = $shelfLifeCol ?? 'B';
                    }
                }

                $dataStartRow = $headerRowIndex + 1;
                foreach ($rows as $rowNum => $row) {
                    if ((int) $rowNum < $dataStartRow) {
                        continue;
                    }
                    $productKey = $normalizeHeader($row[$productCol] ?? '');
                    $yearsRaw = $row[$shelfLifeCol] ?? null;
                    if ($productKey === '') {
                        continue;
                    }
                    $years = $this->parseDecimal($yearsRaw);
                    if ($years <= 0) {
                        continue;
                    }
                    $productToYears[$productKey] = $years;
                }

                // Fallback: if no data rows found but sheet has a number, use sheet name as product
                if (empty($productToYears) && $sheetName !== '') {
                    for ($r = 1; $r <= min(15, count($rows)); $r++) {
                        $row = $rows[$r] ?? [];
                        foreach ($row as $cell) {
                            $y = $this->parseDecimal($cell);
                            if ($y >= 0.5 && $y <= 20) {
                                $productToYears[$sheetName] = $y;
                                break 2;
                            }
                        }
                    }
                }
            }

            Log::info('Belzona shelf life import: products mapped', ['count' => count($productToYears), 'keys' => array_keys($productToYears)]);

            $updated = 0;
            foreach ($productToYears as $productKey => $years) {
                $q = BelzonaInventoryModel::query()
                    ->where(function ($query) use ($productKey) {
                        $query->where('sheet_name', $productKey)
                            ->orWhere('sheet_name', 'LIKE', $productKey . ' (%')
                            ->orWhere('sheet_name', 'LIKE', $productKey . ' %')
                            ->orWhere('sheet_name', 'LIKE', $productKey . '%');
                    });
                $rows = $q->get();
                foreach ($rows as $row) {
                    $row->shelf_life_years = $years;
                    if ($row->date) {
                        $d = $row->date instanceof \DateTimeInterface ? $row->date : \Carbon\Carbon::parse($row->date);
                        $row->expiry_date = $d->copy()->addYears((int) round($years))->format('Y-m-d');
                    }
                    $row->save();
                    $updated++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'مدت ماندگاری برای ' . $updated . ' ردیف به‌روز شد. تعداد محصولات مپ‌شده: ' . count($productToYears),
                'updated' => $updated,
                'products_mapped' => count($productToYears),
            ]);
        } catch (\Exception $e) {
            Log::error('BelzonaInventory shelf life import failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'updated' => 0,
            ], 500);
        }
    }
}

