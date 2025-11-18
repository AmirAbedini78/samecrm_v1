<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSettlement extends Model
{
    protected $table = 'invoice_settlements';
    protected $primaryKey = 'invoice_settlement_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['invoice_settlement_id'];

    protected $fillable = [
        'document_number',
        'document_date',
        'customer_name',
        'base_net_amount',
        'paid_amount',
        'balance_amount',
        'currency',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}

