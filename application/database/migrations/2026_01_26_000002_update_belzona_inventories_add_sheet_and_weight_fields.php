<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBelzonaInventoriesAddSheetAndWeightFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belzona_inventories', function (Blueprint $table) {
            // source/sheet metadata
            $table->string('sheet_name')->nullable()->after('product_name');
            $table->unsignedInteger('sheet_row_number')->nullable()->after('sheet_name');

            // product weight parsed from sheet name
            $table->decimal('product_weight_value', 10, 3)->nullable()->after('product_name');
            $table->string('product_weight_unit', 20)->nullable()->after('product_weight_value'); // kg|gr|lit|...
            $table->string('product_weight_raw', 50)->nullable()->after('product_weight_unit');

            // keep raw date text (Jalali or mixed formats in the xlsx)
            $table->string('date_raw', 50)->nullable()->after('date');

            // optional notes / extra columns
            $table->text('notes')->nullable()->after('customer_name');

            // helpful indexes for filtering/summary
            $table->index(['product_name', 'product_weight_raw'], 'belzona_inv_product_weight_idx');
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
            $table->dropIndex('belzona_inv_product_weight_idx');
            $table->dropColumn([
                'sheet_name',
                'sheet_row_number',
                'product_weight_value',
                'product_weight_unit',
                'product_weight_raw',
                'date_raw',
                'notes',
            ]);
        });
    }
}

