<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCustomCategory extends Model
{
    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'inventory_custom_categories';
    protected $primaryKey = 'category_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['category_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Fillable fields for mass assignment
    protected $fillable = [
        'category_name',
        'category_type',
        'category_color',
        'category_icon',
        'category_image',
        'description',
        'start_date',
        'end_date',
        'user_id'
    ];

    // Cast dates
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\InventoryCustomCategoryItem', 'custom_category_id', 'category_id');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\InventoryCustomCategoryClient', 'custom_category_id', 'category_id');
    }

    public function entryItems()
    {
        return $this->hasManyThrough(
            InventoryEntry::class,
            InventoryCustomCategoryItem::class,
            'custom_category_id',
            'entry_id',
            'category_id',
            'inventory_entry_id'
        );
    }

    public function inventories()
    {
        return $this->belongsToMany(
            'App\Models\Inventory',
            'inventory_custom_category_items',
            'custom_category_id',
            'inventory_id',
            'category_id',
            'inventory_id'
        )->withPivot('alias_name')->withTimestamps();
    }

    /**
     * Check if category is active (within date range)
     * @return bool
     */
    public function isActive()
    {
        $now = now();
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }
        
        return true;
    }
}

