<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCustomCategoryClient extends Model
{
    protected $table = 'inventory_custom_category_clients';
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'custom_category_id',
        'client_id',
        'alias_name',
        'alias_color',
        'alias_image',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function customCategory()
    {
        return $this->belongsTo('App\Models\InventoryCustomCategory', 'custom_category_id', 'category_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'client_id');
    }
}

