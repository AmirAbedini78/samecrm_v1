<?php

namespace App\Services;

use App\Models\Inventory;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Run Python PDF extraction and optionally create inventory entries.
 */
class PdfInventoryExtractService
{
    protected string $projectRoot;
    protected string $pythonPath;
    protected string $scriptPath;

    public function __construct()
    {
        // tools/ is at project root (parent of application/)
        $this->projectRoot = defined('BASE_DIR') ? rtrim(BASE_DIR, DIRECTORY_SEPARATOR) : dirname(base_path());
        $this->pythonPath = config('inventory.python_path', 'python');
        $this->scriptPath = config('inventory.pdf_extract_script', 'tools/pdf_inventory_extract.py');
        if (!str_contains($this->scriptPath, DIRECTORY_SEPARATOR) || !preg_match('#^[A-Za-z]:#', $this->scriptPath)) {
            $this->scriptPath = $this->projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $this->scriptPath);
        }
    }

    /**
     * Extract data from PDF: returns [ 'inventory_code' => ?, 'inventory_name' => ?, 'rows' => [...] ]
     */
    public function extractFromPdf(string $pdfPath): array
    {
        if (!config('inventory.enable_python_ml', true)) {
            return ['success' => false, 'error' => 'استخراج PDF در این سرور غیرفعال است. (ENABLE_PYTHON_ML)', 'inventory_code' => null, 'inventory_name' => null, 'rows' => []];
        }
        if (!file_exists($pdfPath)) {
            return ['success' => false, 'error' => 'فایل PDF یافت نشد', 'inventory_code' => null, 'inventory_name' => null, 'rows' => []];
        }

        $inventoryListPath = $this->writeInventoryListJson();
        $script = $this->scriptPath;
        $cmd = sprintf(
            '%s %s --pdf %s',
            escapeshellarg($this->pythonPath),
            escapeshellarg($script),
            escapeshellarg($pdfPath)
        );
        if ($inventoryListPath) {
            $cmd .= ' --inventory-json ' . escapeshellarg($inventoryListPath);
        }

        $output = [];
        $returnVar = -1;
        @exec($cmd . ' 2>&1', $output, $returnVar);
        if ($inventoryListPath && file_exists($inventoryListPath)) {
            @unlink($inventoryListPath);
        }

        $jsonStr = implode("\n", $output);
        $decoded = json_decode($jsonStr, true);
        if (!is_array($decoded)) {
            Log::warning('PdfInventoryExtract: invalid JSON from script', ['output' => $jsonStr]);
            return ['success' => false, 'error' => 'خروجی اسکریپت نامعتبر است', 'inventory_code' => null, 'inventory_name' => null, 'rows' => []];
        }

        $decoded['rows'] = $decoded['rows'] ?? [];
        return $decoded;
    }

    protected function writeInventoryListJson(): ?string
    {
        $list = Inventory::where('inventory_status', 'active')
            ->select('inventory_code as code', 'inventory_name as name')
            ->get()
            ->toArray();
        $dir = storage_path('app/temp');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $path = $dir . '/inventory_list_' . uniqid() . '.json';
        if (file_put_contents($path, json_encode($list, JSON_UNESCAPED_UNICODE)) !== false) {
            return $path;
        }
        return null;
    }

    /**
     * Normalize date string from PDF (Jalali or Gregorian) to Y-m-d.
     */
    public static function normalizeEntryDate(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $value = preg_replace('/[^\d\/\-.]/', '', trim($value));
        if ($value === '') {
            return null;
        }
        try {
            $sep = strpos($value, '/') !== false ? '/' : (strpos($value, '-') !== false ? '-' : null);
            if ($sep && preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $value, $m)) {
                $y = (int) $m[1];
                $month = (int) $m[2];
                $day = (int) $m[3];
                if ($y >= 1300 && $y <= 1500 && $month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    $persianStr = sprintf('%04d/%02d/%02d', $y, $month, $day);
                    return PersianCalendarHelper::persianToGregorian($persianStr);
                }
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
