<?php

namespace App\Imports;

use App\Models\InvoiceSettlement;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InvoiceSettlementsImport implements
    ToModel,
    WithStartRow,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts,
    WithProgressBar
{
    use Importable, SkipsFailures;

    protected $rows = 0;
    protected $skipped = 0;
    protected $rowIndex = 0;
    protected $skippedDetails = [];

    public function model(array $row)
    {
        $this->rowIndex++;

        if ($this->isEmptyRow($row)) {
            $this->recordSkip('empty_row', $row);
            return null;
        }

        ++$this->rows;

        try {
            return new InvoiceSettlement([
                'document_number' => $this->normalizeDocumentNumber($row[0] ?? null),
                'document_date' => $this->normalizeDate($row[1] ?? null),
                'customer_name' => trim($row[2] ?? ''),
                'base_net_amount' => $this->parseDecimal($row[3] ?? 0),
                'paid_amount' => $this->parseDecimal($row[4] ?? 0),
                'balance_amount' => $this->parseDecimal($row[5] ?? 0),
                'currency' => 'IRR',
                'creator_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice settlement import failed', ['error' => $e->getMessage()]);
            $this->recordSkip('exception: ' . $e->getMessage(), $row);
            return null;
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }

    public function getSkippedDetails(): array
    {
        return $this->skippedDetails;
    }

    protected function isEmptyRow(array $row): bool
    {
        return empty(array_filter($row));
    }

    protected function normalizeDocumentNumber($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $value = trim((string) $value);

        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                $value = rtrim(rtrim($value, '0'), '.');
            }
            return $value;
        }

        return $value;
    }

    protected function normalizeDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = str_replace('\\', '', trim((string) $value));
        return $value;
    }

    protected function parseDecimal($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = preg_replace('/[^0-9E\.\-]/', '', (string) $value);
        return (float) $value;
    }

    protected function recordSkip(string $reason, array $row): void
    {
        $this->skipped++;
        $excelRowNumber = $this->startRow() + $this->rowIndex - 1;

        $this->skippedDetails[] = [
            'row_number' => $excelRowNumber,
            'reason' => $reason,
            'document_number' => $row[0] ?? null,
            'customer_name' => $row[2] ?? null,
        ];
    }
}

