<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuaranteeLetterAssignment extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'guarantee_letter_assignments';
    protected $primaryKey = 'assignment_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['assignment_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Fillable fields for mass assignment
    protected $fillable = [
        'guarantee_id', 'user_id', 'assigned_at', 'assigned_by'
    ];

    // Cast types
    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Relationships
    public function guaranteeLetter() {
        return $this->belongsTo('App\Models\GuaranteeLetter', 'guarantee_id', 'guarantee_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function assignedBy() {
        return $this->belongsTo('App\Models\User', 'assigned_by', 'id');
    }

}

