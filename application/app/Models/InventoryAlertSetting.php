<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAlertSetting extends Model
{
    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'inventory_alert_settings';
    protected $primaryKey = 'alert_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['alert_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Fillable fields for mass assignment
    protected $fillable = [
        'inventory_id', 'inventory_entry_id', 'alert_type', 'threshold_value', 'threshold_days',
        'alert_email', 'alert_sms', 'alert_email_addresses', 'alert_phone_numbers', 'is_active'
    ];

    // Cast types
    protected $casts = [
        'alert_email' => 'boolean',
        'alert_sms' => 'boolean',
        'is_active' => 'boolean',
        'threshold_value' => 'decimal:2',
        'threshold_days' => 'integer',
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory', 'inventory_id', 'inventory_id');
    }

    public function entry()
    {
        return $this->belongsTo(InventoryEntry::class, 'inventory_entry_id', 'entry_id');
    }

    /**
     * Get email addresses as array
     * @return array
     */
    public function getEmailAddressesArrayAttribute()
    {
        if (!$this->alert_email_addresses) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->alert_email_addresses)));
    }

    /**
     * Get phone numbers as array
     * @return array
     */
    public function getPhoneNumbersArrayAttribute()
    {
        if (!$this->alert_phone_numbers) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->alert_phone_numbers)));
    }

    /**
     * Check if this is a global setting (not tied to specific inventory)
     * @return bool
     */
    public function isGlobal()
    {
        return is_null($this->inventory_id);
    }
}



