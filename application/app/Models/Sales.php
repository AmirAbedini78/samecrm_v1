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
    
    // Fillable fields for mass assignment
    protected $fillable = [
        'document_type', 'document_number', 'document_date',
        'customer_code', 'customer_name', 'customer_full_name', 'sales_type',
        'product_code', 'product_name', 'product_barcode', 'tracking_code',
        'main_unit', 'main_quantity', 'warehouse',
        'base_price', 'base_sales_amount', 'base_tax_amount', 'base_duty_amount',
        'base_additional_amount', 'base_increasing_factors', 'base_net_amount',
        'month', 'description',
        'issued_main_quantity', 'issued_sub_quantity', 'remaining_main_quantity', 'remaining_sub_quantity',
        'currency', 'sales_status', 'sales_creatorid'
    ];

    // Relationships
    public function creator() {
        return $this->belongsTo('App\Models\User', 'sales_creatorid', 'id');
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
