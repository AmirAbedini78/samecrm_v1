<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeLetter extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'guarantee_letters';
    protected $primaryKey = 'guarantee_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['guarantee_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Fillable fields for mass assignment
    protected $fillable = [
        'guarantee_number', 'guarantee_type', 'industrial_type',
        'issue_date', 'expiry_date', 'renewal_date', 'settlement_date',
        'amount', 'currency', 'issuing_bank', 'beneficiary',
        'status', 'assigned_user_id', 'guarantee_creatorid', 'description'
    ];

    // Cast dates
    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'renewal_date' => 'date',
        'settlement_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function creator() {
        return $this->belongsTo('App\Models\User', 'guarantee_creatorid', 'id');
    }

    public function assignedUser() {
        return $this->belongsTo('App\Models\User', 'assigned_user_id', 'id');
    }

    public function assignments() {
        return $this->hasMany('App\Models\GuaranteeLetterAssignment', 'guarantee_id', 'guarantee_id');
    }

    public function notifications() {
        return $this->hasMany('App\Models\GuaranteeLetterNotification', 'guarantee_id', 'guarantee_id');
    }

    public function notificationLogs() {
        return $this->hasMany('App\Models\GuaranteeLetterNotificationLog', 'guarantee_id', 'guarantee_id');
    }

    /**
     * relatioship business rules:
     *         - the GuaranteeLetter can have many Tags
     *         - the Tags belongs to one GuaranteeLetter
     *         - other tags can belong to other tables
     */
    public function tags() {
        return $this->morphMany('App\Models\Tag', 'tagresource');
    }

    /**
     * relatioship business rules:
     *         - the GuaranteeLetter can have many Notes
     *         - the Note belongs to one GuaranteeLetter
     *         - other Note can belong to other tables
     */
    public function notes() {
        return $this->morphMany('App\Models\Note', 'noteresource');
    }

    /**
     * display format for guarantee id - adding leading zeros & with any set prefix
     * formatted_guarantee_id
     * e.g. GL-000001
     */
    public function getFormattedIdAttribute() {
        return runtimeGuaranteeLetterIdFormat($this->guarantee_id);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedGuaranteeCreatedAttribute() {
        return runtimeDate($this->created_at);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedGuaranteeUpdatedAttribute() {
        return runtimeDate($this->updated_at);
    }

    /**
     * Check if guarantee letter is expired
     */
    public function isExpired() {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute() {
        if (!$this->expiry_date) {
            return null;
        }
        return now()->diffInDays($this->expiry_date, false);
    }

}

