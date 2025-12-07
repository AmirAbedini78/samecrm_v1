<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'inventory_transactions';
    protected $primaryKey = 'transaction_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['transaction_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Fillable fields for mass assignment
    protected $fillable = [
        'inventory_id', 'transaction_type', 'quantity', 'sub_quantity', 'amount',
        'transaction_date', 'document_number', 'base_document_number', 'warehouse', 'notes', 'user_id',
        'unit_price'
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory', 'inventory_id', 'inventory_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * Get formatted transaction date
     * @return string
     */
    public function getFormattedTransactionDateAttribute()
    {
        return runtimeDate($this->transaction_date);
    }

    /**
     * Get transaction type label
     * @return string
     */
    public function getTransactionTypeLabelAttribute()
    {
        return $this->transaction_type === 'input' ? 'ورود' : 'خروج';
    }
}



