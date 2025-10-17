<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounting extends Model {

    /**
     * @primaryKey string - primary key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */

    protected $table = 'accounting';
    protected $primaryKey = 'accounting_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['accounting_id'];
    const CREATED_AT = 'accounting_created';
    const UPDATED_AT = 'accounting_updated';

    /**
     * relatioship business rules:
     *         - the Creator (user) can have many Accounting records
     *         - the Accounting belongs to one Creator (user)
     */
    public function creator() {
        return $this->belongsTo('App\Models\User', 'accounting_creatorid', 'id');
    }

    /**
     * relatioship business rules:
     *         - the Category can have many Accounting records
     *         - the Accounting belongs to one Category
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'accounting_categoryid', 'category_id');
    }

    /**
     * relatioship business rules:
     *         - the Accounting can have many Tags
     *         - the Tags belongs to one Accounting
     *         - other tags can belong to other tables
     */
    public function tags() {
        return $this->morphMany('App\Models\Tag', 'tagresource');
    }

    /**
     * display format for accounting id - adding leading zeros & with any set prefix
     * formatted_accounting_id
     * e.g. ACC-000001
     */
    public function getFormattedIdAttribute() {
        return runtimeAccountingIdFormat($this->accounting_id);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedAccountingCreatedAttribute() {
        return runtimeDate($this->accounting_created);
    }

    /**
     * pre-formatted data
     * @return string
     */
    public function getFormattedAccountingUpdatedAttribute() {
        return runtimeDate($this->accounting_updated);
    }

}

