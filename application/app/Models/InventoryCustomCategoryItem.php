<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCustomCategoryItem extends Model
{
    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'inventory_custom_category_items';
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Fillable fields for mass assignment
    protected $fillable = [
        'inventory_id',
        'inventory_entry_id',
        'custom_category_id',
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

    // Relationships
    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory', 'inventory_id', 'inventory_id');
    }

    public function customCategory()
    {
        return $this->belongsTo('App\Models\InventoryCustomCategory', 'custom_category_id', 'category_id');
    }

    public function entry()
    {
        return $this->belongsTo(InventoryEntry::class, 'inventory_entry_id', 'entry_id');
    }
}

