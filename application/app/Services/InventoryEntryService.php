<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class InventoryEntryService
{
    /**
     * Create a new inventory entry and refresh aggregate balances.
     */
    public function create(array $attributes): ?InventoryEntry
    {
        $inventoryId = Arr::get($attributes, 'inventory_id');
        if (!$inventoryId || !$this->inventoryExists($inventoryId)) {
            return null;
        }

        $quantity = (float) Arr::get($attributes, 'quantity', 0);
        $unitPrice = (float) Arr::get($attributes, 'unit_price', 0);
        $entryType = Arr::get($attributes, 'entry_type', 'ورودی'); // پیش‌فرض: ورودی

        try {
            $entry = InventoryEntry::create([
                'inventory_id' => $inventoryId,
                'entry_date' => Arr::get($attributes, 'entry_date'),
                'entry_code' => Arr::get($attributes, 'entry_code'),
                'entry_type' => $entryType,
                'document_number' => Arr::get($attributes, 'document_number'),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => Arr::get($attributes, 'total_amount', $quantity * $unitPrice),
                'import_batch' => Arr::get($attributes, 'import_batch'),
            ]);

            $this->syncInventoryBalances($inventoryId);

            return $entry;
        } catch (\Exception $e) {
            Log::error('Create inventory entry failed', [
                'inventory_id' => $inventoryId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Recalculate inventory summary values from entries.
     * محاسبه موجودی کل بر اساس ورودی‌ها و خروجی‌ها
     */
    public function syncInventoryBalances(int $inventoryId): void
    {
        $inventory = Inventory::find($inventoryId);
        if (!$inventory) {
            return;
        }

        // محاسبه مجموع ورودی‌ها
        $totalInput = $inventory->entries()
            ->whereIn('entry_type', ['ورودی', 'input', 'IN', 'ورود'])
            ->sum('quantity');

        // محاسبه مجموع خروجی‌ها
        $totalOutput = $inventory->entries()
            ->whereIn('entry_type', ['خروجی', 'output', 'OUT', 'خروج'])
            ->sum('quantity');

        // موجودی فعلی = ورودی‌ها - خروجی‌ها
        $currentQuantity = $totalInput - $totalOutput;

        // محاسبه میانگین قیمت از ورودی‌ها
        $totalInputAmount = $inventory->entries()
            ->where(function($query) {
                $query->where('entry_type', 'ورودی')
                    ->orWhere('entry_type', 'input')
                    ->orWhere('entry_type', 'IN');
            })
            ->sum('total_amount');

        $avgPrice = $totalInput > 0 ? ($totalInputAmount / $totalInput) : 0;

        $inventory->current_quantity = $currentQuantity;
        $inventory->current_avg_price = $avgPrice;
        $inventory->save();
    }

    private function inventoryExists(int $inventoryId): bool
    {
        return Inventory::where('inventory_id', $inventoryId)->exists();
    }
}
