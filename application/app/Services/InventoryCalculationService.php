<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Exceptions\Inventory\InventoryCalculationException;
use App\Exceptions\Inventory\InventoryNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryCalculationService
{
    /**
     * Recalculate inventory quantities from transactions
     *
     * @param int $inventory_id
     * @return bool
     * @throws InventoryCalculationException
     * @throws InventoryNotFoundException
     */
    public function recalculateInventory($inventory_id)
    {
        try {
            // Lock inventory row to prevent race conditions
            $inventory = Inventory::lockForUpdate()->find($inventory_id);
            if (!$inventory) {
                throw new InventoryNotFoundException("کالا با شناسه {$inventory_id} یافت نشد");
            }

            // Use aggregation for better performance
            $aggregates = InventoryTransaction::where('inventory_id', $inventory_id)
                ->selectRaw('
                    SUM(CASE WHEN transaction_type = "input" THEN quantity ELSE 0 END) as input_quantity,
                    SUM(CASE WHEN transaction_type = "input" THEN sub_quantity ELSE 0 END) as input_sub_quantity,
                    SUM(CASE WHEN transaction_type = "input" THEN amount ELSE 0 END) as input_amount,
                    SUM(CASE WHEN transaction_type = "output" THEN quantity ELSE 0 END) as output_quantity,
                    SUM(CASE WHEN transaction_type = "output" THEN sub_quantity ELSE 0 END) as output_sub_quantity,
                    SUM(CASE WHEN transaction_type = "output" THEN amount ELSE 0 END) as output_amount
                ')
                ->first();

            // Initialize totals
            $inputQuantity = (float) ($aggregates->input_quantity ?? 0);
            $inputSubQuantity = (float) ($aggregates->input_sub_quantity ?? 0);
            $inputAmount = (float) ($aggregates->input_amount ?? 0);
            $outputQuantity = (float) ($aggregates->output_quantity ?? 0);
            $outputSubQuantity = (float) ($aggregates->output_sub_quantity ?? 0);
            $outputAmount = (float) ($aggregates->output_amount ?? 0);

            // Calculate current quantities
            $currentQuantity = (float) (($inventory->first_period_quantity ?? 0) + $inputQuantity - $outputQuantity);
            $currentSubQuantity = (float) (($inventory->first_period_sub_quantity ?? 0) + $inputSubQuantity - $outputSubQuantity);
            $currentAmount = (float) (($inventory->first_period_amount ?? 0) + $inputAmount - $outputAmount);

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

            if (!$inventory->save()) {
                throw new InventoryCalculationException('خطا در ذخیره اطلاعات موجودی');
            }

            return true;
        } catch (InventoryNotFoundException | InventoryCalculationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Inventory calculation failed: ' . $e->getMessage(), [
                'inventory_id' => $inventory_id,
                'trace' => $e->getTraceAsString()
            ]);
            throw new InventoryCalculationException('خطا در محاسبه موجودی: ' . $e->getMessage());
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

