<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'sales';
    protected $primaryKey = 'sales_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['sales_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relationships
    public function creator() {
        return $this->belongsTo('App\Models\User', 'sales_creatorid', 'id');
    }

    public function client() {
        return $this->belongsTo('App\Models\Client', 'sales_clientid', 'client_id');
    }

    public function project() {
        return $this->belongsTo('App\Models\Project', 'sales_projectid', 'project_id');
    }

    public function category() {
        return $this->belongsTo('App\Models\Category', 'sales_categoryid', 'category_id');
    }

    public function inventory() {
        return $this->belongsTo('App\Models\Inventory', 'sales_inventory_id', 'inventory_id');
    }

    /**
     * relatioship business rules:
     *         - the Sales can have many Tags
     *         - the Tags belongs to one Sales
     *         - other tags can belong to other tables
     */
    public function tags() {
        return $this->morphMany('App\Models\Tag', 'tagresource');
    }

    /**
     * display format for sales id - adding leading zeros & with any set prefix
     * formatted_sales_id
     * e.g. SAL-000001
     */
    public function getFormattedIdAttribute() {
        return runtimeSalesIdFormat($this->sales_id);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedSalesCreatedAttribute() {
        return runtimeDate($this->created_at);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedSalesUpdatedAttribute() {
        return runtimeDate($this->updated_at);
    }

}
