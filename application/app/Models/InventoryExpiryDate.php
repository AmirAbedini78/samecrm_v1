<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InventoryExpiryDate extends Model
{
    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'inventory_expiry_dates';
    protected $primaryKey = 'expiry_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['expiry_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Fillable fields for mass assignment
    protected $fillable = [
        'inventory_id', 'expiry_date', 'auto_expiry_days', 'alert_days_before', 'is_expired'
    ];

    // Cast dates
    protected $casts = [
        'expiry_date' => 'date',
        'is_expired' => 'boolean',
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory', 'inventory_id', 'inventory_id');
    }

    /**
     * Check if expiry date is approaching
     * @return bool
     */
    public function isApproaching()
    {
        if (!$this->expiry_date) {
            return false;
        }

        $daysUntilExpiry = Carbon::now()->diffInDays($this->expiry_date, false);
        return $daysUntilExpiry > 0 && $daysUntilExpiry <= $this->alert_days_before;
    }

    /**
     * Get days until expiry
     * @return int|null
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Get formatted expiry date
     * @return string
     */
    public function getFormattedExpiryDateAttribute()
    {
        return $this->expiry_date ? runtimeDate($this->expiry_date) : '-';
    }
}


