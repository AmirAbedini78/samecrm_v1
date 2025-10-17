<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['inventory_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relationships
    public function creator() {
        return $this->belongsTo('App\Models\User', 'inventory_creatorid', 'id');
    }

    public function category() {
        return $this->belongsTo('App\Models\Category', 'inventory_categoryid', 'category_id');
    }

    public function sales() {
        return $this->hasMany('App\Models\Sales', 'sales_inventory_id', 'inventory_id');
    }


    /**
     * relatioship business rules:
     *         - the Inventory can have many Tags
     *         - the Tags belongs to one Inventory
     *         - other tags can belong to other tables
     */
    public function tags() {
        return $this->morphMany('App\Models\Tag', 'tagresource');
    }

    /**
     * display format for inventory id - adding leading zeros & with any set prefix
     * formatted_inventory_id
     * e.g. INV-000001
     */
    public function getFormattedIdAttribute() {
        return runtimeInventoryIdFormat($this->inventory_id);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedInventoryCreatedAttribute() {
        return runtimeDate($this->created_at);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedInventoryUpdatedAttribute() {
        return runtimeDate($this->updated_at);
    }

}
