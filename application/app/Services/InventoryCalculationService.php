<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryCalculationService
{
    /**
     * Recalculate inventory quantities from transactions
     *
     * @param int $inventory_id
     * @return bool
     */
    public function recalculateInventory($inventory_id)
    {
        try {
            $inventory = Inventory::find($inventory_id);
            if (!$inventory) {
                return false;
            }

            // Get all transactions for this inventory
            $transactions = InventoryTransaction::where('inventory_id', $inventory_id)
                ->orderBy('transaction_date', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            // Initialize totals
            $inputQuantity = 0;
            $inputSubQuantity = 0;
            $inputAmount = 0;
            $outputQuantity = 0;
            $outputSubQuantity = 0;
            $outputAmount = 0;

            // Calculate totals from transactions
            foreach ($transactions as $transaction) {
                if ($transaction->transaction_type === 'input') {
                    $inputQuantity += $transaction->quantity ?? 0;
                    $inputSubQuantity += $transaction->sub_quantity ?? 0;
                    $inputAmount += $transaction->amount ?? 0;
                } else {
                    $outputQuantity += $transaction->quantity ?? 0;
                    $outputSubQuantity += $transaction->sub_quantity ?? 0;
                    $outputAmount += $transaction->amount ?? 0;
                }
            }

            // Calculate current quantities
            $currentQuantity = ($inventory->first_period_quantity ?? 0) + $inputQuantity - $outputQuantity;
            $currentSubQuantity = ($inventory->first_period_sub_quantity ?? 0) + $inputSubQuantity - $outputSubQuantity;
            $currentAmount = ($inventory->first_period_amount ?? 0) + $inputAmount - $outputAmount;

            // Calculate average price
            $currentAvgPrice = 0;
            if ($currentQuantity > 0) {
                $currentAvgPrice = $currentAmount / $currentQuantity;
            }

            // Calculate input average price
            $inputAvgPrice = 0;
            if ($inputQuantity > 0) {
                $inputAvgPrice = $inputAmount / $inputQuantity;
            }

            // Calculate output average price
            $outputAvgPrice = 0;
            if ($outputQuantity > 0) {
                $outputAvgPrice = $outputAmount / $outputQuantity;
            }

            // Update inventory
            $inventory->input_quantity = $inputQuantity;
            $inventory->input_sub_quantity = $inputSubQuantity;
            $inventory->input_amount = $inputAmount;
            $inventory->input_avg_price = $inputAvgPrice;

            $inventory->output_quantity = $outputQuantity;
            $inventory->output_sub_quantity = $outputSubQuantity;
            $inventory->output_amount = $outputAmount;
            $inventory->output_avg_price = $outputAvgPrice;

            $inventory->current_quantity = $currentQuantity;
            $inventory->current_sub_quantity = $currentSubQuantity;
            $inventory->current_amount = $currentAmount;
            $inventory->current_avg_price = $currentAvgPrice;

            $inventory->save();

            return true;
        } catch (\Exception $e) {
            Log::error('Inventory calculation failed: ' . $e->getMessage(), [
                'inventory_id' => $inventory_id,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Recalculate all inventories
     *
     * @return int Number of inventories recalculated
     */
    public function recalculateAll()
    {
        $inventories = Inventory::where('inventory_status', 'active')->get();
        $count = 0;

        foreach ($inventories as $inventory) {
            if ($this->recalculateInventory($inventory->inventory_id)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Calculate amount from quantity and unit price
     *
     * @param float $quantity
     * @param float|null $unitPrice
     * @return float
     */
    public function calculateAmount($quantity, $unitPrice = null)
    {
        if ($unitPrice === null || $unitPrice == 0) {
            return 0;
        }
        return $quantity * $unitPrice;
    }

    /**
     * Update inventory after transaction created
     *
     * @param InventoryTransaction $transaction
     * @return bool
     */
    public function updateInventoryAfterTransaction($transaction)
    {
        return $this->recalculateInventory($transaction->inventory_id);
    }

    /**
     * Update inventory after transaction deleted
     *
     * @param int $inventory_id
     * @return bool
     */
    public function updateInventoryAfterTransactionDeleted($inventory_id)
    {
        return $this->recalculateInventory($inventory_id);
    }
}

