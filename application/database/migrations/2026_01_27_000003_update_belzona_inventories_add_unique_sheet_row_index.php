<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBelzonaInventoriesAddUniqueSheetRowIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belzona_inventories', function (Blueprint $table) {
            // prevent double-import of the same worksheet row
            $table->unique(['sheet_name', 'sheet_row_number'], 'belzona_inv_sheet_row_unique');

            // extra indexes for filtering/searching
            $table->index('sheet_name', 'belzona_inv_sheet_name_idx');
            $table->index('product_name', 'belzona_inv_product_name_idx');
            $table->index('date', 'belzona_inv_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('belzona_inventories', function (Blueprint $table) {
            $table->dropUnique('belzona_inv_sheet_row_unique');
            $table->dropIndex('belzona_inv_sheet_name_idx');
            $table->dropIndex('belzona_inv_product_name_idx');
            $table->dropIndex('belzona_inv_date_idx');
        });
    }
}

