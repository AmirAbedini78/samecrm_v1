<?php

namespace App\Console\Commands;

use App\Models\BelzonaInventory as BelzonaInventoryModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;

class BelzonaShelfLifeImportCommand extends Command
{
    protected $signature = 'belzona:import-shelf-life {path? : Excel file path (relative or absolute)}';
    protected $description = 'Import Belzona shelf life (مدت ماندگاری) from Excel and update belzona_inventories';

    public function handle()
    {
        // default path relative to Laravel application base path
        $pathArg = $this->argument('path') ?? '../public/documents/xlsx/مدت زمان ماندگاری مجصول بلزونا   shelf life.xlsx';
        $fullPath = $this->resolvePath($pathArg);

        if (!file_exists($fullPath)) {
            $this->error("Shelf life Excel file not found: {$fullPath}");
            return Command::FAILURE;
        }

        $this->info("Loading shelf life Excel file: {$fullPath}");

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
            $s = preg_replace('/\s+/u', ' ', (string) $s);
            return trim($s);
        };

        try {
            if (method_exists(Settings::class, 'setZipClass')) {
                $const = Settings::PCLZIP ?? null;
                if ($const) {
                    Settings::setZipClass($const);
                }
            }

            $spreadsheet = IOFactory::load($fullPath);
            $productToYears = [];

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $rows = $worksheet->toArray(null, true, true, true);
                if (empty($rows)) {
                    continue;
                }

                $sheetName = trim((string) $worksheet->getTitle());

                // Find header row: try rows 1, 2, 3 and pick first that contains a known header
                $headerRowIndex = null;
                $headerMap = [];
                $productCol = null;
                $shelfLifeCol = null;

                for ($hr = 1; $hr <= 3; $hr++) {
                    $header = $rows[$hr] ?? [];
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
                    if ($productCol === null) {
                        foreach (array_keys($headerMap) as $h) {
                            if (mb_stripos($h, 'محصول') !== false || mb_stripos($h, 'کد') !== false || stripos($h, 'product') !== false) {
                                $productCol = $headerMap[$h];
                                break;
                            }
                        }
                    }
                    foreach ($shelfLifeColumnKeys as $k) {
                        if (isset($headerMap[$k])) {
                            $shelfLifeCol = $headerMap[$k];
                            break;
                        }
                    }
                    if ($shelfLifeCol === null) {
                        foreach (array_keys($headerMap) as $h) {
                            if (preg_match('/(سال|year|ماندگاری|shelf|مدت)/ui', $h)) {
                                $shelfLifeCol = $headerMap[$h];
                                break;
                            }
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
                    $firstRow = $rows[1] ?? [];
                    $shelfLifeCol = 'B';
                    foreach ($firstRow as $col => $val) {
                        if ($col === 'A') {
                            continue;
                        }
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
                        $shelfLifeCol = 'B';
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
                if ($sheetName !== '' && !array_key_exists($sheetName, $productToYears)) {
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

            $this->info('Products mapped from Excel: ' . count($productToYears));
            Log::info('Belzona shelf life CLI import: products mapped', [
                'count' => count($productToYears),
                'keys' => array_keys($productToYears),
            ]);

            $updated = 0;
            foreach ($productToYears as $productKey => $years) {
                // Excel has "Belzona 1111" but DB sheet_name is "1111 (1Kg)" etc. Extract code for matching.
                $searchCode = $this->extractSearchCode($productKey);
                if ($searchCode === '') {
                    $this->line("Skipped (no code): {$productKey}");
                    continue;
                }

                $query = BelzonaInventoryModel::query()
                    ->where(function ($q) use ($searchCode, $productKey) {
                        $q->where('sheet_name', $productKey)
                            ->orWhere('sheet_name', $searchCode)
                            ->orWhere('sheet_name', 'LIKE', $searchCode . ' (%')
                            ->orWhere('sheet_name', 'LIKE', $searchCode . ' %')
                            ->orWhere('sheet_name', 'LIKE', $searchCode . '%');
                    });

                $rows = $query->get();
                if ($rows->isEmpty()) {
                    $this->line("No inventory rows matched: {$productKey} (code: {$searchCode})");
                    continue;
                }

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

            $this->info("Shelf life updated for {$updated} belzona_inventories row(s).");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Error importing shelf life: ' . $e->getMessage());
            Log::error('Belzona shelf life CLI import failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Extract DB-matching code from Excel product name (e.g. "Belzona 1111" -> "1111", "1000 series" -> "1000").
     */
    private function extractSearchCode(string $productKey): string
    {
        $s = trim(preg_replace('/\s+/u', ' ', $productKey));
        $s = preg_replace('/^Belzona\s+/iu', '', $s);
        $s = trim($s);
        if ($s === '') {
            return '';
        }
        // If multiple words (e.g. "1000 series"), take the first token as the product code
        if (strpos($s, ' ') !== false) {
            $s = explode(' ', $s, 2)[0];
        }
        return $s;
    }

    private function parseDecimal($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        $value = preg_replace('/[^0-9\.\-]/', '', (string) $value);
        if ($value === '' || $value === '-' || $value === '.' || $value === '-.') {
            return 0.0;
        }
        return (float) $value;
    }

    private function resolvePath(string $path): string
    {
        // absolute path
        if (file_exists($path)) {
            return $path;
        }

        // relative to storage
        $storagePath = storage_path($path);
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        // relative to Laravel base path (application folder)
        $base = base_path($path);
        if (file_exists($base)) {
            return $base;
        }

        // relative to project root (one level up from application)
        $projectRoot = dirname(base_path());
        $combined = $projectRoot . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
        if (file_exists($combined)) {
            return $combined;
        }

        return $path;
    }
}

