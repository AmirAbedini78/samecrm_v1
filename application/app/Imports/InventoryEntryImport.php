<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Services\InventoryEntryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InventoryEntryImport implements
    ToModel,
    WithHeadingRow,
    WithStartRow,
    WithChunkReading,
    WithBatchInserts,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    protected InventoryEntryService $entryService;
    protected string $importBatch;
    protected ?string $inventoryCode = null; // کد کالا که باید از فایل anbar.xlsx گرفته شود
    protected int $rowCount = 0;

    public function __construct(InventoryEntryService $entryService, ?string $batch = null, ?string $inventoryCode = null)
    {
        $this->entryService = $entryService;
        $this->importBatch = $batch ?: now()->format('Ymd-His');
        $this->inventoryCode = $inventoryCode;
    }

    public function model(array $row)
    {
        // خواندن ستون‌های موردنیاز از فایل Excel
        // ستون‌ها: تاريخ، سند، نوع، شماره سند مبنا، مقدار، في، مبلغ تمام شده
        
        $entryDate = $this->parseDate($this->getValue($row, ['تاريخ', 'entry_date', 'date', 'تاریخ']));
        $entryCode = $this->getString($row, ['سند', 'entry_code', 'document', 'سند ورود']);
        $entryType = $this->getString($row, ['نوع', 'entry_type', 'type', 'transaction_type']);
        $documentNumber = $this->getString($row, ['شماره سند مبنا', 'document_number', 'base_document', 'سند مبنا']);
        $quantity = $this->parseDecimal($this->getValue($row, ['مقدار', 'quantity', 'qty', 'amount']));
        $unitPrice = $this->parseDecimal($this->getValue($row, ['في', 'unit_price', 'price', 'فی']));
        $totalAmount = $this->parseDecimal($this->getValue($row, ['مبلغ تمام شده', 'total_amount', 'total_cost', 'مبلغ']));

        // اگر مقدار صفر یا منفی باشد، رکورد را نادیده بگیر
        if ($quantity <= 0) {
            return null;
        }

        // اگر کد کالا از قبل تنظیم نشده، سعی کن از ردیف بخوان (اگر در فایل باشد)
        $code = $this->inventoryCode ?: $this->getString($row, ['کد کالا', 'inventory_code', 'code', 'کد']);
        
        if (!$code) {
            Log::warning('Inventory entry import skipped - no inventory code', ['row' => $row]);
            return null;
        }

        $inventory = Inventory::where('inventory_code', $code)->first();
        if (!$inventory) {
            Log::warning('Inventory entry import skipped - inventory not found', ['code' => $code]);
            return null;
        }

        $result = $this->entryService->create([
            'inventory_id' => $inventory->inventory_id,
            'entry_date' => $entryDate,
            'entry_code' => $entryCode,
            'entry_type' => $entryType,
            'document_number' => $documentNumber,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount ?: ($quantity * $unitPrice), // اگر مبلغ تمام شده نبود، محاسبه کن
            'import_batch' => $this->importBatch,
        ]);

        if ($result) {
            $this->rowCount++;
        }

        return null;
    }

    public function startRow(): int
    {
        return 2; // ردیف اول header است
    }

    public function headingRow(): int
    {
        return 1; // ردیف header
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    private function getValue(array $row, array $keys)
    {
        foreach ($keys as $key) {
            // جستجو با کلید اصلی
            if (Arr::has($row, $key) && $row[$key] !== null) {
                return $row[$key];
            }
            // جستجو با کلید به صورت case-insensitive
            foreach ($row as $rowKey => $value) {
                if (strtolower(trim((string)$rowKey)) === strtolower(trim((string)$key)) && $value !== null) {
                    return $value;
                }
            }
        }
        return null;
    }

    private function getString(array $row, array $keys): ?string
    {
        $value = $this->getValue($row, $keys);
        return $value !== null ? trim((string) $value) : null;
    }

    private function parseDecimal($value): float
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        // حذف کاراکترهای غیرعددی (به جز نقطه اعشار و منفی)
        $value = preg_replace('/[^0-9\-.]/', '', (string) $value);

        return (float) $value;
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            if ($value instanceof \DateTime) {
                return $value->format('Y-m-d');
            }

            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // تلاش برای پارس کردن تاریخ شمسی یا میلادی
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Inventory entry import date parse failed', [
                'value' => $value,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get row count
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
