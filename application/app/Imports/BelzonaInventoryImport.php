<?php

namespace App\Imports;

use App\Models\BelzonaInventory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Illuminate\Support\Facades\Log;

class BelzonaInventoryImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, WithChunkReading, WithBatchInserts, WithProgressBar
{
    use Importable, SkipsFailures;

    private $rows = 0;
    private $skipped = 0;

    /**
     * Expected columns (by position):
     * 0 product_name
     * 1 date
     * 2 input
     * 3 output
     * 4 balance
     * 5 invoice_number
     * 6 customer_name
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row[0]) && empty($row[1]) && empty($row[5]) && empty($row[6])) {
            $this->skipped++;
            return null;
        }

        ++$this->rows;

        try {
            return new BelzonaInventory([
                'product_name' => $row[0] ?? null,
                'date' => $this->parseDate($row[1] ?? null),
                'input' => $this->parseDecimal($row[2] ?? 0),
                'output' => $this->parseDecimal($row[3] ?? 0),
                'balance' => $this->parseDecimal($row[4] ?? 0),
                'invoice_number' => $row[5] ?? null,
                'customer_name' => $row[6] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error("BelzonaInventory import error: " . $e->getMessage(), ['row' => $row]);
            $this->skipped++;
            return null;
        }
    }

    public function startRow(): int
    {
        return 2; // Skip header row
    }

    public function rules(): array
    {
        return [
            '0' => 'nullable|string|max:255',
            '5' => 'nullable|string|max:255',
            '6' => 'nullable|string|max:255',
        ];
    }

    private function parseDecimal($value)
    {
        if (empty($value) || $value === null) {
            return 0;
        }

        $value = preg_replace('/[^0-9.-]/', '', (string) $value);
        return (float) $value;
    }

    private function parseDate($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel may provide a formatted string; let DB cast handle if parsable
        return $value;
    }

    public function getRowCount()
    {
        return $this->rows;
    }

    public function getSkippedCount()
    {
        return $this->skipped;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}

