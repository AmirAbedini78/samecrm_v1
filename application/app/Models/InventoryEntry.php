<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryEntry extends Model
{
    protected $table = 'inventory_entries';
    protected $primaryKey = 'entry_id';
    protected $guarded = ['entry_id'];
    protected $casts = [
        'entry_date' => 'date',
        'quantity' => 'float',
        'unit_price' => 'float',
        'total_amount' => 'float',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'inventory_id');
    }

    public function customCategories()
    {
        return $this->belongsToMany(
            InventoryCustomCategory::class,
            'inventory_custom_category_items',
            'inventory_entry_id',
            'custom_category_id',
            'entry_id',
            'category_id'
        );
    }

    public function alertSettings()
    {
        return $this->hasMany(InventoryAlertSetting::class, 'inventory_entry_id', 'entry_id');
    }
}
