<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model
{
    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'sms_queue';
    protected $primaryKey = 'sms_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['sms_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Fillable fields for mass assignment
    protected $fillable = [
        'sms_to', 'sms_message', 'sms_type', 'sms_status', 'sms_sent_at', 'sms_error'
    ];

    // Cast types
    protected $casts = [
        'sms_sent_at' => 'datetime',
    ];

    /**
     * Scope for new SMS messages
     */
    public function scopeNew($query)
    {
        return $query->where('sms_status', 'new');
    }

    /**
     * Scope for sent SMS messages
     */
    public function scopeSent($query)
    {
        return $query->where('sms_status', 'sent');
    }

    /**
     * Scope for failed SMS messages
     */
    public function scopeFailed($query)
    {
        return $query->where('sms_status', 'failed');
    }

    /**
     * Mark as sent
     */
    public function markAsSent()
    {
        $this->sms_status = 'sent';
        $this->sms_sent_at = now();
        $this->save();
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($error = null)
    {
        $this->sms_status = 'failed';
        $this->sms_error = $error;
        $this->save();
    }
}


