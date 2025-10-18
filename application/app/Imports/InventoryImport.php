<?php

namespace App\Imports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class InventoryImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure {

    use Importable, SkipsFailures;

    private $rows = 0;
    private $skipped = 0;
    private $import_limit;
    private $max_limit_reached = false;

    public function __construct($import_limit = 1000) {
        $this->import_limit = $import_limit;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        // Check if we've reached the import limit
        if ($this->rows >= $this->import_limit) {
            $this->max_limit_reached = true;
            $this->skipped++;
            return null;
        }

        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            $this->skipped++;
            return null;
        }

        // Check for duplicates based on inventory_code
        if ($this->isDuplicate($row)) {
            $this->skipped++;
            return null;
        }

        ++$this->rows;

        try {
            return new Inventory([
                'inventory_code' => $row[0] ?? '', // کد کالا
                'inventory_name' => $row[1] ?? '', // عنوان کالا
                'first_period_quantity' => $this->parseDecimal($row[2] ?? 0), // اول دوره-مقدار
                'first_period_sub_quantity' => $this->parseDecimal($row[3] ?? 0), // اول دوره-مقدار واحد فرعي
                'first_period_amount' => $this->parseDecimal($row[4] ?? 0), // اول دوره-مبلغ
                'first_period_avg_price' => $this->parseDecimal($row[5] ?? 0), // اول دوره-في متوسط
                'input_quantity' => $this->parseDecimal($row[6] ?? 0), // ورودي-مقدار
                'input_sub_quantity' => $this->parseDecimal($row[7] ?? 0), // ورودي-مقدار واحد فرعي
                'input_amount' => $this->parseDecimal($row[8] ?? 0), // ورودي-مبلغ
                'input_avg_price' => $this->parseDecimal($row[9] ?? 0), // ورودي-في متوسط
                'output_quantity' => $this->parseDecimal($row[10] ?? 0), // خروجي-مقدار
                'output_sub_quantity' => $this->parseDecimal($row[11] ?? 0), // خروجي-مقدار واحد فرعي
                'output_amount' => $this->parseDecimal($row[12] ?? 0), // خروجي-مبلغ
                'output_avg_price' => $this->parseDecimal($row[13] ?? 0), // خروجي-في متوسط
                'current_quantity' => $this->parseDecimal($row[14] ?? 0), // موجودي-مقدار
                'current_sub_quantity' => $this->parseDecimal($row[15] ?? 0), // موجودي-مقدار واحد فرعي
                'current_amount' => $this->parseDecimal($row[16] ?? 0), // موجودي-مبلغ
                'current_avg_price' => $this->parseDecimal($row[17] ?? 0), // موجودي-في متوسط
                'weighing_input' => $this->parseDecimal($row[18] ?? 0), // توزين - ورود
                'weighing_output' => $this->parseDecimal($row[19] ?? 0), // توزين - خروج
                'minimum_stock' => $this->parseDecimal($row[20] ?? 0), // حداقل موجودي
                'maximum_stock' => $this->parseDecimal($row[21] ?? null), // حداکثر موجودي
                'discrepancy' => $this->parseDecimal($row[22] ?? 0), // مغايرت
                'main_unit' => $row[23] ?? 'pcs', // واحد اصلي
                'sub_unit' => $row[24] ?? null, // واحد فرعي
                'inventory_status' => $row[25] ?? 'active', // وضعیت
                'inventory_creatorid' => auth()->id(),
                'inventory_categoryid' => null, // می‌تواند بعداً تنظیم شود
            ]);
        } catch (\Exception $e) {
            Log::error("Inventory import error: " . $e->getMessage(), ['row' => $row]);
            $this->skipped++;
            return null;
        }
    }

    /**
     * @return int
     */
    public function startRow(): int {
        return 2; // Skip header row
    }

    /**
     * @return array
     */
    public function rules(): array {
        return [
            '0' => 'required|string|max:255', // inventory_code
            '1' => 'required|string|max:255', // inventory_name
        ];
    }

    /**
     * Check for duplicates
     */
    private function isDuplicate($row) {
        if (empty($row[0])) {
            return true;
        }

        return Inventory::where('inventory_code', $row[0])->exists();
    }

    /**
     * Parse decimal values
     */
    private function parseDecimal($value) {
        if (empty($value) || $value === null) {
            return 0;
        }

        // Remove any non-numeric characters except decimal point and minus
        $value = preg_replace('/[^0-9.-]/', '', $value);
        
        return (float) $value;
    }

    /**
     * Get row count
     */
    public function getRowCount() {
        return $this->rows;
    }

    /**
     * Get skipped count
     */
    public function getSkippedCount() {
        return $this->skipped;
    }

    /**
     * Check if max limit reached
     */
    public function maxLimitReached() {
        return $this->max_limit_reached;
    }

    /**
     * Get max items
     */
    public function getMaxItems() {
        return $this->import_limit;
    }
}
