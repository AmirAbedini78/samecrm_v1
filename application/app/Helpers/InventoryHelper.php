<?php

namespace App\Helpers;

use App\Models\Inventory;
use App\Models\InventoryExpiryDate;
use Carbon\Carbon;

class InventoryHelper
{
    /**
     * Calculate days until expiry
     *
     * @param \DateTime|string $expiryDate
     * @return int|null
     */
    public static function daysUntilExpiry($expiryDate)
    {
        if (!$expiryDate) {
            return null;
        }

        try {
            $expiry = $expiryDate instanceof \DateTime ? $expiryDate : Carbon::parse($expiryDate);
            return Carbon::now()->diffInDays($expiry, false);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if inventory item is expired
     *
     * @param Inventory $inventory
     * @return bool
     */
    public static function isExpired($inventory)
    {
        $expiry = $inventory->expiryDate;
        if (!$expiry || !$expiry->expiry_date) {
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($expiry->expiry_date));
    }

    /**
     * Check if inventory item is approaching expiry
     *
     * @param Inventory $inventory
     * @param int $daysBefore
     * @return bool
     */
    public static function isApproachingExpiry($inventory, $daysBefore = 7)
    {
        $expiry = $inventory->expiryDate;
        if (!$expiry || !$expiry->expiry_date) {
            return false;
        }

        $daysUntil = self::daysUntilExpiry($expiry->expiry_date);
        return $daysUntil !== null && $daysUntil > 0 && $daysUntil <= $daysBefore;
    }

    /**
     * Get expiry status color
     *
     * @param Inventory $inventory
     * @return string
     */
    public static function getExpiryStatusColor($inventory)
    {
        if (self::isExpired($inventory)) {
            return 'danger'; // Red
        }

        $expiry = $inventory->expiryDate;
        if (!$expiry || !$expiry->expiry_date) {
            return 'success'; // Green
        }

        $daysUntil = self::daysUntilExpiry($expiry->expiry_date);
        $alertDays = $expiry->alert_days_before ?? 7;

        if ($daysUntil !== null && $daysUntil <= $alertDays) {
            return 'warning'; // Yellow/Orange
        }

        return 'success'; // Green
    }

    /**
     * Get stock status
     *
     * @param Inventory $inventory
     * @return string
     */
    public static function getStockStatus($inventory)
    {
        if ($inventory->current_quantity < 0) {
            return 'negative';
        }

        if ($inventory->current_quantity == 0) {
            return 'empty';
        }

        if ($inventory->minimum_stock > 0 && $inventory->current_quantity <= $inventory->minimum_stock) {
            return 'low';
        }

        if ($inventory->maximum_stock > 0 && $inventory->current_quantity >= $inventory->maximum_stock) {
            return 'high';
        }

        return 'normal';
    }

    /**
     * Get stock status color
     *
     * @param Inventory $inventory
     * @return string
     */
    public static function getStockStatusColor($inventory)
    {
        $status = self::getStockStatus($inventory);

        switch ($status) {
            case 'negative':
                return 'danger';
            case 'empty':
                return 'danger';
            case 'low':
                return 'warning';
            case 'high':
                return 'info';
            default:
                return 'success';
        }
    }

    /**
     * Calculate inventory value
     *
     * @param float $quantity
     * @param float $avgPrice
     * @return float
     */
    public static function calculateInventoryValue($quantity, $avgPrice)
    {
        return $quantity * $avgPrice;
    }

    /**
     * Format inventory quantity with unit
     *
     * @param float $quantity
     * @param string $unit
     * @return string
     */
    public static function formatQuantity($quantity, $unit = 'pcs')
    {
        return number_format($quantity, 2) . ' ' . $unit;
    }

    /**
     * Get entry date for inventory
     *
     * @param Inventory $inventory
     * @return string|null
     */
    public static function getEntryDate($inventory)
    {
        if ($inventory->entry_date) {
            return runtimeDate($inventory->entry_date);
        }

        // Try to get from first transaction
        $firstTransaction = $inventory->transactions()
            ->where('transaction_type', 'input')
            ->orderBy('transaction_date', 'asc')
            ->first();

        if ($firstTransaction) {
            return runtimeDate($firstTransaction->transaction_date);
        }

        // Fallback to created_at
        return runtimeDate($inventory->created_at);
    }

    /**
     * Check if inventory is outside stock (negative or not physically available)
     *
     * @param Inventory $inventory
     * @return bool
     */
    public static function isOutsideInventory($inventory)
    {
        return $inventory->current_quantity < 0 || !$inventory->physical_available;
    }

    /**
     * Get auto expiry date based on entry date and default days
     *
     * @param Inventory $inventory
     * @return \DateTime|null
     */
    public static function calculateAutoExpiryDate($inventory)
    {
        $entryDate = $inventory->entry_date;
        if (!$entryDate) {
            $entryDate = self::getEntryDate($inventory);
            if (!$entryDate) {
                return null;
            }
        }

        $defaultDays = $inventory->auto_expiry_default_days ?? 90; // Default 90 days

        try {
            $entry = Carbon::parse($entryDate);
            return $entry->addDays($defaultDays);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get inventory age in days
     *
     * @param Inventory $inventory
     * @return int|null
     */
    public static function getInventoryAge($inventory)
    {
        $entryDate = $inventory->entry_date;
        if (!$entryDate) {
            $entryDate = self::getEntryDate($inventory);
            if (!$entryDate) {
                return null;
            }
        }

        try {
            $entry = Carbon::parse($entryDate);
            return Carbon::now()->diffInDays($entry);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get inventory status badge HTML
     *
     * @param Inventory $inventory
     * @return string
     */
    public static function getStatusBadge($inventory)
    {
        $expiryColor = self::getExpiryStatusColor($inventory);
        $stockColor = self::getStockStatusColor($inventory);

        $badges = [];

        // Stock status badge
        $stockStatus = self::getStockStatus($inventory);
        if ($stockStatus !== 'normal') {
            $badges[] = '<span class="badge badge-' . $stockColor . '">' . self::getStockStatusLabel($stockStatus) . '</span>';
        }

        // Expiry status badge
        if (self::isExpired($inventory)) {
            $badges[] = '<span class="badge badge-danger">منقضی شده</span>';
        } elseif (self::isApproachingExpiry($inventory)) {
            $badges[] = '<span class="badge badge-warning">نزدیک به انقضا</span>';
        }

        // Physical availability badge
        if (!$inventory->physical_available) {
            $badges[] = '<span class="badge badge-secondary">غیرفیزیکی</span>';
        }

        return implode(' ', $badges);
    }

    /**
     * Get stock status label
     *
     * @param string $status
     * @return string
     */
    protected static function getStockStatusLabel($status)
    {
        $labels = [
            'negative' => 'موجودی منفی',
            'empty' => 'موجودی صفر',
            'low' => 'موجودی کم',
            'high' => 'موجودی زیاد',
            'normal' => 'عادی'
        ];

        return $labels[$status] ?? 'نامشخص';
    }
}

