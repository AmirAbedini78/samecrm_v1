<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Services\InventoryCalculationService;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryTransactionImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure, WithChunkReading, WithBatchInserts
{
    use Importable, SkipsFailures;

    private $rows = 0;
    private $skipped = 0;
    private $calculationService;
    private $processedInventoryIds = [];

    public function __construct()
    {
        $this->calculationService = new InventoryCalculationService();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            $this->skipped++;
            return null;
        }

        try {
            // Parse Excel columns based on template:
            // تاریخ (0), سند (1), نوع (2), شماره سند مبنا (3), مقدار (4), فی (5), مبلغ تمام شده (6)
            
            $transactionDate = $this->parseDate($row[0] ?? null);
            $documentNumber = $this->parseString($row[1] ?? null);
            $transactionType = $this->parseTransactionType($row[2] ?? null);
            $baseDocumentNumber = $this->parseString($row[3] ?? null);
            $quantity = $this->parseDecimal($row[4] ?? 0);
            $unitPrice = $this->parseDecimal($row[5] ?? 0);
            $totalAmount = $this->parseDecimal($row[6] ?? 0);
            
            // If total amount is not provided, calculate from quantity * unit_price
            if ($totalAmount == 0 && $quantity > 0 && $unitPrice > 0) {
                $totalAmount = $quantity * $unitPrice;
            }

            // Validate required fields
            if (!$transactionDate || !$documentNumber || !$transactionType || $quantity <= 0) {
                $this->skipped++;
                Log::warning('Skipping transaction row - missing required fields', ['row' => $row]);
                return null;
            }

            // Find inventory by code or name (assuming inventory_code is in a separate column or needs to be matched)
            // For now, we'll need inventory_id - this might need to be adjusted based on actual Excel structure
            // If Excel has inventory code/name, it should be in another column
            // For this implementation, we'll assume inventory_id needs to be provided or matched
            
            // Note: The Excel template might need inventory_code/name column
            // If not present, we'll need to handle it differently
            // For now, assuming we need to find inventory somehow
            
            // This is a placeholder - actual implementation depends on Excel structure
            // You may need to add inventory_code column to Excel or match by other means
            $inventoryId = $this->findInventoryId($row);
            
            if (!$inventoryId) {
                $this->skipped++;
                Log::warning('Skipping transaction row - inventory not found', ['row' => $row]);
                return null;
            }

            // Track inventory IDs that need recalculation
            if (!in_array($inventoryId, $this->processedInventoryIds)) {
                $this->processedInventoryIds[] = $inventoryId;
            }

            ++$this->rows;

            return new InventoryTransaction([
                'inventory_id' => $inventoryId,
                'transaction_type' => $transactionType,
                'quantity' => $quantity,
                'sub_quantity' => 0, // Can be extended if needed
                'amount' => $totalAmount,
                'unit_price' => $unitPrice,
                'transaction_date' => $transactionDate,
                'document_number' => $documentNumber,
                'base_document_number' => $baseDocumentNumber,
                'warehouse' => null, // Can be extended if Excel has warehouse column
                'notes' => null, // Can be extended if Excel has notes column
                'user_id' => auth()->id() ?? 1,
            ]);
        } catch (\Exception $e) {
            Log::error("Transaction import error: " . $e->getMessage(), ['row' => $row, 'trace' => $e->getTraceAsString()]);
            $this->skipped++;
            return null;
        }
    }

    /**
     * Find inventory ID from row data
     * This method needs to be customized based on actual Excel structure
     * 
     * @param array $row
     * @return int|null
     */
    private function findInventoryId($row)
    {
        // Option 1: If Excel has inventory_code column (e.g., column 7)
        if (isset($row[7]) && !empty($row[7])) {
            $inventory = Inventory::where('inventory_code', $row[7])->first();
            if ($inventory) {
                return $inventory->inventory_id;
            }
        }

        // Option 2: If Excel has inventory_name column (e.g., column 8)
        if (isset($row[8]) && !empty($row[8])) {
            $inventory = Inventory::where('inventory_name', 'LIKE', '%' . $row[8] . '%')->first();
            if ($inventory) {
                return $inventory->inventory_id;
            }
        }

        // Option 3: If request has inventory_id parameter (for bulk import of one inventory)
        if (request()->has('inventory_id')) {
            return request('inventory_id');
        }

        return null;
    }

    /**
     * Parse transaction type from Persian text
     * 
     * @param mixed $value
     * @return string
     */
    private function parseTransactionType($value)
    {
        if (empty($value)) {
            return 'input'; // Default
        }

        $value = strtolower(trim($value));
        
        // Persian/Farsi text
        if (strpos($value, 'ورود') !== false || strpos($value, 'ورودی') !== false) {
            return 'input';
        }
        
        if (strpos($value, 'خروج') !== false || strpos($value, 'خروجی') !== false) {
            return 'output';
        }

        // English text
        if (in_array($value, ['input', 'in', 'entry', 'purchase', 'buy'])) {
            return 'input';
        }
        
        if (in_array($value, ['output', 'out', 'exit', 'sale', 'sell'])) {
            return 'output';
        }

        // Default to input
        return 'input';
    }

    /**
     * Parse date from various formats
     * 
     * @param mixed $value
     * @return string|null
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // If it's already a Carbon instance or DateTime
            if ($value instanceof \DateTime) {
                return $value->format('Y-m-d');
            }

            // If it's a timestamp
            if (is_numeric($value)) {
                return Carbon::createFromTimestamp($value)->format('Y-m-d');
            }

            // Try to parse as date string
            $date = Carbon::parse($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Date parse error', ['value' => $value, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Parse string value
     * 
     * @param mixed $value
     * @return string|null
     */
    private function parseString($value)
    {
        if (empty($value)) {
            return null;
        }
        return trim((string) $value);
    }

    /**
     * Parse decimal values
     * 
     * @param mixed $value
     * @return float
     */
    private function parseDecimal($value)
    {
        if (empty($value) || $value === null) {
            return 0;
        }

        // Remove any non-numeric characters except decimal point and minus
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);
        
        return (float) $value;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Skip header row
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '0' => 'required', // تاریخ
            '1' => 'required', // سند
            '2' => 'required', // نوع
            '4' => 'required|numeric|min:0', // مقدار
        ];
    }

    /**
     * Get row count
     */
    public function getRowCount()
    {
        return $this->rows;
    }

    /**
     * Get skipped count
     */
    public function getSkippedCount()
    {
        return $this->skipped;
    }

    /**
     * Chunk size for processing
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Batch size for database inserts
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Called after import is complete
     * Recalculate inventory for all affected items
     */
    public function __destruct()
    {
        // Recalculate inventory for all processed items
        foreach ($this->processedInventoryIds as $inventoryId) {
            try {
                $this->calculationService->recalculateInventory($inventoryId);
            } catch (\Exception $e) {
                Log::error('Failed to recalculate inventory after import', [
                    'inventory_id' => $inventoryId,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

