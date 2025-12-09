<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeLetterNotification extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'guarantee_letter_notifications';
    protected $primaryKey = 'notification_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['notification_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Fillable fields for mass assignment
    protected $fillable = [
        'guarantee_id', 'notification_type', 'date_column',
        'alert_days_before', 'alert_days_after',
        'repeat_interval_days', 'max_repeats',
        'is_active', 'custom_message', 'recipient_user_ids',
        'last_sent_at', 'sent_count'
    ];

    // Cast types
    protected $casts = [
        'is_active' => 'boolean',
        'alert_days_before' => 'integer',
        'alert_days_after' => 'integer',
        'repeat_interval_days' => 'integer',
        'max_repeats' => 'integer',
        'sent_count' => 'integer',
        'last_sent_at' => 'datetime',
        'recipient_user_ids' => 'array',
    ];

    // Relationships
    public function guaranteeLetter() {
        return $this->belongsTo('App\Models\GuaranteeLetter', 'guarantee_id', 'guarantee_id');
    }

    public function logs() {
        return $this->hasMany('App\Models\GuaranteeLetterNotificationLog', 'notification_id', 'notification_id');
    }

    /**
     * Get recipient user IDs as array
     */
    public function getRecipientUserIdsArrayAttribute() {
        if (empty($this->recipient_user_ids)) {
            return [];
        }
        if (is_string($this->recipient_user_ids)) {
            return json_decode($this->recipient_user_ids, true) ?: [];
        }
        return $this->recipient_user_ids ?: [];
    }

    /**
     * Set recipient user IDs from array
     */
    public function setRecipientUserIdsAttribute($value) {
        if (is_array($value)) {
            $this->attributes['recipient_user_ids'] = json_encode($value);
        } else {
            $this->attributes['recipient_user_ids'] = $value;
        }
    }

    /**
     * Check if notification should be sent
     */
    public function shouldSend($targetDate) {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_repeats > 0 && $this->sent_count >= $this->max_repeats) {
            return false;
        }

        $now = \Carbon\Carbon::now();
        if (!$targetDate instanceof \Carbon\Carbon) {
            $targetDate = \Carbon\Carbon::parse($targetDate);
        }
        
        $daysDiff = $now->diffInDays($targetDate, false);

        // Check if we're in the alert window (before the date)
        if ($this->alert_days_before > 0 && $daysDiff > 0 && $daysDiff <= $this->alert_days_before) {
            // Check repeat interval
            if ($this->repeat_interval_days > 0 && $this->last_sent_at) {
                $lastSent = \Carbon\Carbon::parse($this->last_sent_at);
                $daysSinceLastSent = $now->diffInDays($lastSent, false);
                if ($daysSinceLastSent < $this->repeat_interval_days) {
                    return false;
                }
            }
            return true;
        }

        // Check for past dates (after the date)
        if ($this->alert_days_after > 0 && $daysDiff < 0 && abs($daysDiff) <= $this->alert_days_after) {
            if ($this->repeat_interval_days > 0 && $this->last_sent_at) {
                $lastSent = \Carbon\Carbon::parse($this->last_sent_at);
                $daysSinceLastSent = $now->diffInDays($lastSent, false);
                if ($daysSinceLastSent < $this->repeat_interval_days) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

}

