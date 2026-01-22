<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelzonaInventory extends Model
{
 /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */



    protected $table = 'belzona_inventories';
    protected $primaryKey = 'belzona_inventory_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['belzona_inventory_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    // Fillable fields for mass assignment

protected $fillable = [
    'product_name',  
    'date',  
    'input',  
    'output',  
    'balance',  
    'invoice_number',  
    'customer_name',  
];






}

