<?php

namespace App\Imports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithProgressBar;
// use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class SalesImport implements ToModel, WithStartRow, SkipsOnFailure, WithChunkReading, WithBatchInserts, WithProgressBar {

    use Importable, SkipsFailures;

    private $rows = 0;
    private $skipped = 0;
    private $rowIndex = 0; // counts every row seen by model()
    private $skippedDetails = [];
 
    public function __construct() {
        // No import limit - process all records
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        // track current excel row number (considering startRow)
        $this->rowIndex++;

        // Debug information (reduced logging for performance)
        if ($this->rows % 1000 == 0) {
            \Log::info("SalesImport Progress", [
                'processed_rows' => $this->rows,
                'first_cell' => $row[0] ?? 'empty',
                'second_cell' => $row[1] ?? 'empty'
            ]);
        }

        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            $this->skipped++;
            $this->recordSkip('empty_row', $row);
            return null;
        }

        // Skip duplicate check for now to allow all imports
        // if ($this->isDuplicate($row)) {
        //     $this->skipped++;
        //     return null;
        // }

        ++$this->rows;

        try {
            // Create new record directly
            return new Sales([
                'document_type' => $row[0] ?? 'sale', // نوع سند
                'document_number' => $row[1] ?? '', // شماره
                'document_date' => $this->parseDate($row[2] ?? date('Y-m-d')), // تاريخ
                'customer_code' => $row[3] ?? null, // كد مشتري
                'customer_name' => $row[4] ?? '', // مشتري
                'customer_full_name' => $row[5] ?? null, // نام مشتري
                'sales_type' => $row[6] ?? 'sale', // نوع فروش
                'product_code' => $row[7] ?? null, // كد كالا/خدمت
                'product_name' => $row[8] ?? '', // كالا/خدمت
                'product_barcode' => $row[9] ?? null, // بار كد كالا
                'tracking_code' => $row[10] ?? null, // رديابي
                'main_unit' => $row[11] ?? 'pcs', // واحد اصلي
                'main_quantity' => $this->parseDecimal($row[12] ?? 0), // مقدار-اصلي
                'warehouse' => $row[13] ?? null, // انبار
                'base_price' => $this->parseDecimal($row[14] ?? 0), // في به ارز پايه
                'base_sales_amount' => $this->parseDecimal($row[15] ?? 0), // مبلغ فروش به ارز پايه
                'base_tax_amount' => $this->parseDecimal($row[16] ?? 0), // ماليات به ارز پايه
                'base_duty_amount' => $this->parseDecimal($row[17] ?? 0), // عوارض به ارز پايه
                'base_additional_amount' => $this->parseDecimal($row[18] ?? 0), // اضافات به ارز پايه
                'base_increasing_factors' => $this->parseDecimal($row[19] ?? 0), // عوامل افزاينده به ارز پايه
                'base_net_amount' => $this->parseDecimal($row[20] ?? 0), // خالص به ارز پايه
                'month' => $row[21] ?? null, // ماه
                'description' => $row[22] ?? null, // توضيحات
                'issued_main_quantity' => $this->parseDecimal($row[23] ?? 0), // مقدار خارج شده اصلي
                'issued_sub_quantity' => $this->parseDecimal($row[24] ?? 0), // مقدار خارج شده فرعي
                'remaining_main_quantity' => $this->parseDecimal($row[25] ?? 0), // مانده خارج نشده اصلي
                'remaining_sub_quantity' => $this->parseDecimal($row[26] ?? 0), // مانده خارج نشده فرعي
                'currency' => $row[27] ?? 'IRR', // ارز
                'sales_status' => $row[28] ?? 'pending', // وضعیت
                'sales_creatorid' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            Log::error("Sales import error: " . $e->getMessage(), ['row' => $row]);
            $this->skipped++;
            $this->recordSkip('exception: ' . $e->getMessage(), $row);
            return null;
        }
    }

    /**
     * @return int
     */
    public function startRow(): int {
        return 1; // Start from first row
    }

    /**
     * @return array
     */
    public function rules(): array {
        return [
            // No validation rules for now to allow flexible import
        ];
    }

    /**
     * Check for duplicates
     */
    private function isDuplicate($row) {
        $document_number = $row[1] ?? '';
        
        if (empty($document_number)) {
            return true; // Skip empty document numbers
        }
        
        // Check if document_number already exists
        return Sales::where('document_number', $document_number)->exists();
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
     * Parse date values
     */
    private function parseDate($value) {
        if (empty($value)) {
            return date('Y-m-d');
        }

        // Try to parse different date formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        // If all formats fail, return current date
        return date('Y-m-d');
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
     * Chunk size for processing
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Batch size for database inserts
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * Return detailed skipped rows
     */
    public function getSkippedDetails(): array
    {
        return $this->skippedDetails;
    }

    /**
     * Record a skipped row with details
     */
    private function recordSkip(string $reason, array $row): void
    {
        $excelRowNumber = $this->startRow() + $this->rowIndex - 1;
        $this->skippedDetails[] = [
            'row_number' => $excelRowNumber,
            'reason' => $reason,
            'document_number' => $row[1] ?? null,
            'product_code' => $row[7] ?? null,
            'product_name' => $row[8] ?? null,
        ];
    }
}
