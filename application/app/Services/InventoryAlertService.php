<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryExpiryDate;
use App\Models\InventoryAlertSetting;
use App\Models\EmailQueue;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InventoryAlertService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Check expiry alerts for all inventory items
     *
     * @return array Alerts sent
     */
    public function checkExpiryAlerts()
    {
        $alertsSent = [];
        
        // Get all active expiry dates
        $expiryDates = InventoryExpiryDate::with('inventory')
            ->whereNotNull('expiry_date')
            ->where('is_expired', false)
            ->get();

        foreach ($expiryDates as $expiry) {
            $daysUntilExpiry = $expiry->days_until_expiry;
            
            // Check if expired
            if ($daysUntilExpiry < 0) {
                $expiry->is_expired = true;
                $expiry->save();
                $this->sendExpiryAlert($expiry, 'expired');
                $alertsSent[] = [
                    'inventory_id' => $expiry->inventory_id,
                    'type' => 'expired',
                    'expiry_date' => $expiry->expiry_date
                ];
                continue;
            }

            // Check if approaching expiry
            if ($daysUntilExpiry <= $expiry->alert_days_before) {
                $this->sendExpiryAlert($expiry, 'approaching');
                $alertsSent[] = [
                    'inventory_id' => $expiry->inventory_id,
                    'type' => 'approaching',
                    'days_until' => $daysUntilExpiry,
                    'expiry_date' => $expiry->expiry_date
                ];
            }
        }

        // Check alert settings for expiry
        $expiryAlertSettings = InventoryAlertSetting::where('alert_type', 'expiry')
            ->where('is_active', true)
            ->get();

        foreach ($expiryAlertSettings as $setting) {
            if ($setting->inventory_id) {
                // Specific inventory alert
                $expiry = InventoryExpiryDate::where('inventory_id', $setting->inventory_id)->first();
                if ($expiry && $expiry->expiry_date) {
                    $daysUntil = Carbon::now()->diffInDays($expiry->expiry_date, false);
                    if ($daysUntil <= ($setting->threshold_days ?? 7)) {
                        $this->sendCustomExpiryAlert($setting, $expiry);
                    }
                }
            } else {
                // Global expiry alert - check all inventories
                $this->checkGlobalExpiryAlerts($setting);
            }
        }

        return $alertsSent;
    }

    /**
     * Check quantity alerts for all inventory items
     *
     * @return array Alerts sent
     */
    public function checkQuantityAlerts()
    {
        $alertsSent = [];

        // Check minimum stock alerts
        $lowStockItems = Inventory::whereColumn('current_quantity', '<=', 'minimum_stock')
            ->where('current_quantity', '>', 0)
            ->where('inventory_status', 'active')
            ->get();

        foreach ($lowStockItems as $inventory) {
            $this->sendQuantityAlert($inventory, 'minimum');
            $alertsSent[] = [
                'inventory_id' => $inventory->inventory_id,
                'type' => 'minimum',
                'current_quantity' => $inventory->current_quantity,
                'minimum_stock' => $inventory->minimum_stock
            ];
        }

        // Check maximum stock alerts
        $highStockItems = Inventory::whereColumn('current_quantity', '>=', 'maximum_stock')
            ->whereNotNull('maximum_stock')
            ->where('inventory_status', 'active')
            ->get();

        foreach ($highStockItems as $inventory) {
            $this->sendQuantityAlert($inventory, 'maximum');
            $alertsSent[] = [
                'inventory_id' => $inventory->inventory_id,
                'type' => 'maximum',
                'current_quantity' => $inventory->current_quantity,
                'maximum_stock' => $inventory->maximum_stock
            ];
        }

        // Check custom quantity alert settings
        $quantityAlertSettings = InventoryAlertSetting::whereIn('alert_type', ['quantity', 'minimum', 'maximum'])
            ->where('is_active', true)
            ->get();

        foreach ($quantityAlertSettings as $setting) {
            if ($setting->inventory_id) {
                // Specific inventory alert
                $inventory = Inventory::find($setting->inventory_id);
                if ($inventory) {
                    $this->checkCustomQuantityAlert($setting, $inventory);
                }
            } else {
                // Global quantity alert
                $this->checkGlobalQuantityAlerts($setting);
            }
        }

        return $alertsSent;
    }

    /**
     * Process all alerts
     *
     * @return array
     */
    public function processAlerts()
    {
        $expiryAlerts = $this->checkExpiryAlerts();
        $quantityAlerts = $this->checkQuantityAlerts();

        return [
            'expiry' => $expiryAlerts,
            'quantity' => $quantityAlerts,
            'total' => count($expiryAlerts) + count($quantityAlerts)
        ];
    }

    /**
     * Send expiry alert
     *
     * @param InventoryExpiryDate $expiry
     * @param string $type (expired/approaching)
     */
    protected function sendExpiryAlert($expiry, $type = 'approaching')
    {
        $inventory = $expiry->inventory;
        if (!$inventory) {
            return;
        }

        $daysUntil = $expiry->days_until_expiry;
        $message = $this->buildExpiryMessage($inventory, $expiry, $type, $daysUntil);

        // Get alert settings for this inventory
        $alertSettings = InventoryAlertSetting::where('inventory_id', $inventory->inventory_id)
            ->where('alert_type', 'expiry')
            ->where('is_active', true)
            ->get();

        if ($alertSettings->isEmpty()) {
            // Use default settings or global settings
            $alertSettings = InventoryAlertSetting::whereNull('inventory_id')
                ->where('alert_type', 'expiry')
                ->where('is_active', true)
                ->get();
        }

        foreach ($alertSettings as $setting) {
            $this->sendAlert($setting, $message, 'expiry_alert');
        }
    }

    /**
     * Send quantity alert
     *
     * @param Inventory $inventory
     * @param string $type (minimum/maximum)
     */
    protected function sendQuantityAlert($inventory, $type = 'minimum')
    {
        $message = $this->buildQuantityMessage($inventory, $type);

        // Get alert settings for this inventory
        $alertSettings = InventoryAlertSetting::where('inventory_id', $inventory->inventory_id)
            ->whereIn('alert_type', ['quantity', $type])
            ->where('is_active', true)
            ->get();

        if ($alertSettings->isEmpty()) {
            // Use global settings
            $alertSettings = InventoryAlertSetting::whereNull('inventory_id')
                ->whereIn('alert_type', ['quantity', $type])
                ->where('is_active', true)
                ->get();
        }

        foreach ($alertSettings as $setting) {
            $this->sendAlert($setting, $message, 'quantity_alert');
        }
    }

    /**
     * Send alert via email and/or SMS
     *
     * @param InventoryAlertSetting $setting
     * @param string $message
     * @param string $alertType
     */
    protected function sendAlert($setting, $message, $alertType = 'general')
    {
        // Send email
        if ($setting->alert_email && $setting->alert_email_addresses) {
            $emails = $setting->email_addresses_array;
            foreach ($emails as $email) {
                $this->queueEmail($email, $message['subject'], $message['body'], $alertType);
            }
        }

        // Send SMS
        if ($setting->alert_sms && $setting->alert_phone_numbers) {
            $phones = $setting->phone_numbers_array;
            foreach ($phones as $phone) {
                $this->smsService->queueSms($phone, $message['sms_text'], $alertType);
            }
        }
    }

    /**
     * Queue email for sending
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $type
     */
    protected function queueEmail($to, $subject, $body, $type = 'general')
    {
        try {
            EmailQueue::create([
                'emailqueue_to' => $to,
                'emailqueue_subject' => $subject,
                'emailqueue_message' => $body,
                'emailqueue_type' => $type,
                'emailqueue_status' => 'new',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue email', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Build expiry alert message
     *
     * @param Inventory $inventory
     * @param InventoryExpiryDate $expiry
     * @param string $type
     * @param int|null $daysUntil
     * @return array
     */
    protected function buildExpiryMessage($inventory, $expiry, $type, $daysUntil = null)
    {
        if ($daysUntil === null) {
            $daysUntil = $expiry->days_until_expiry ?? 0;
        }
        
        $expiryDate = runtimeDate($expiry->expiry_date);
        
        if ($type === 'expired') {
            $subject = "هشدار: کالای {$inventory->inventory_name} منقضی شده است";
            $body = "کالای {$inventory->inventory_name} (کد: {$inventory->inventory_code}) در تاریخ {$expiryDate} منقضی شده است.";
            $smsText = "کالای {$inventory->inventory_name} منقضی شده است. تاریخ انقضا: {$expiryDate}";
        } else {
            $subject = "هشدار: کالای {$inventory->inventory_name} نزدیک به انقضا است";
            $body = "کالای {$inventory->inventory_name} (کد: {$inventory->inventory_code}) تا {$daysUntil} روز دیگر منقضی می‌شود. تاریخ انقضا: {$expiryDate}";
            $smsText = "کالای {$inventory->inventory_name} تا {$daysUntil} روز دیگر منقضی می‌شود. تاریخ: {$expiryDate}";
        }

        return [
            'subject' => $subject,
            'body' => $body,
            'sms_text' => $smsText
        ];
    }

    /**
     * Build quantity alert message
     *
     * @param Inventory $inventory
     * @param string $type
     * @return array
     */
    protected function buildQuantityMessage($inventory, $type)
    {
        if ($type === 'minimum') {
            $subject = "هشدار: موجودی کالای {$inventory->inventory_name} به حداقل رسیده است";
            $body = "موجودی کالای {$inventory->inventory_name} (کد: {$inventory->inventory_code}) به {$inventory->current_quantity} {$inventory->main_unit} رسیده است که کمتر از حداقل موجودی ({$inventory->minimum_stock} {$inventory->main_unit}) است.";
            $smsText = "موجودی کالای {$inventory->inventory_name} به حداقل رسیده: {$inventory->current_quantity} {$inventory->main_unit}";
        } else {
            $subject = "هشدار: موجودی کالای {$inventory->inventory_name} به حداکثر رسیده است";
            $body = "موجودی کالای {$inventory->inventory_name} (کد: {$inventory->inventory_code}) به {$inventory->current_quantity} {$inventory->main_unit} رسیده است که بیشتر از حداکثر موجودی ({$inventory->maximum_stock} {$inventory->main_unit}) است.";
            $smsText = "موجودی کالای {$inventory->inventory_name} به حداکثر رسیده: {$inventory->current_quantity} {$inventory->main_unit}";
        }

        return [
            'subject' => $subject,
            'body' => $body,
            'sms_text' => $smsText
        ];
    }

    /**
     * Check custom expiry alerts
     *
     * @param InventoryAlertSetting $setting
     * @param InventoryExpiryDate $expiry
     */
    protected function sendCustomExpiryAlert($setting, $expiry)
    {
        $inventory = $expiry->inventory;
        if (!$inventory) {
            return;
        }

        $daysUntil = Carbon::now()->diffInDays($expiry->expiry_date, false);
        $message = $this->buildExpiryMessage($inventory, $expiry, $daysUntil < 0 ? 'expired' : 'approaching', $daysUntil);
        $this->sendAlert($setting, $message, 'expiry_alert');
    }

    /**
     * Check global expiry alerts
     *
     * @param InventoryAlertSetting $setting
     */
    protected function checkGlobalExpiryAlerts($setting)
    {
        $thresholdDays = $setting->threshold_days ?? 7;
        $expiryDates = InventoryExpiryDate::with('inventory')
            ->whereNotNull('expiry_date')
            ->where('is_expired', false)
            ->get();

        foreach ($expiryDates as $expiry) {
            $daysUntil = Carbon::now()->diffInDays($expiry->expiry_date, false);
            if ($daysUntil <= $thresholdDays) {
                $this->sendCustomExpiryAlert($setting, $expiry);
            }
        }
    }

    /**
     * Check custom quantity alert
     *
     * @param InventoryAlertSetting $setting
     * @param Inventory $inventory
     */
    protected function checkCustomQuantityAlert($setting, $inventory)
    {
        if (!$setting->threshold_value) {
            return;
        }

        $shouldAlert = false;
        
        if ($setting->alert_type === 'minimum' && $inventory->current_quantity <= $setting->threshold_value) {
            $shouldAlert = true;
        } elseif ($setting->alert_type === 'maximum' && $inventory->current_quantity >= $setting->threshold_value) {
            $shouldAlert = true;
        } elseif ($setting->alert_type === 'quantity' && $inventory->current_quantity >= $setting->threshold_value) {
            $shouldAlert = true;
        }

        if ($shouldAlert) {
            $type = $setting->alert_type === 'maximum' ? 'maximum' : 'minimum';
            $this->sendQuantityAlert($inventory, $type);
        }
    }

    /**
     * Check global quantity alerts
     *
     * @param InventoryAlertSetting $setting
     */
    protected function checkGlobalQuantityAlerts($setting)
    {
        if (!$setting->threshold_value) {
            return;
        }

        $query = Inventory::where('inventory_status', 'active');

        if ($setting->alert_type === 'minimum') {
            $query->where('current_quantity', '<=', $setting->threshold_value);
        } elseif ($setting->alert_type === 'maximum') {
            $query->where('current_quantity', '>=', $setting->threshold_value);
        } else {
            $query->where('current_quantity', '>=', $setting->threshold_value);
        }

        $inventories = $query->get();

        foreach ($inventories as $inventory) {
            $type = $setting->alert_type === 'maximum' ? 'maximum' : 'minimum';
            $this->sendQuantityAlert($inventory, $type);
        }
    }
}

