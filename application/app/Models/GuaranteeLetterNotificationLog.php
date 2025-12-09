<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeLetterNotificationLog extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'guarantee_letter_notification_logs';
    protected $primaryKey = 'log_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['log_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Fillable fields for mass assignment
    protected $fillable = [
        'guarantee_id', 'notification_id', 'sent_at',
        'sent_to', 'sent_type', 'message', 'status', 'error_message'
    ];

    // Cast types
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function guaranteeLetter() {
        return $this->belongsTo('App\Models\GuaranteeLetter', 'guarantee_id', 'guarantee_id');
    }

    public function notification() {
        return $this->belongsTo('App\Models\GuaranteeLetterNotification', 'notification_id', 'notification_id');
    }

}

