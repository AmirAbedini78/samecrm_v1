<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInventoryCustomCategoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_custom_category_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_custom_category_items', 'alias_color')) {
                $table->string('alias_color', 20)->nullable()->after('alias_name');
            }

            if (!Schema::hasColumn('inventory_custom_category_items', 'alias_image')) {
                $table->string('alias_image')->nullable()->after('alias_color');
            }

            if (!Schema::hasColumn('inventory_custom_category_items', 'start_date')) {
                $table->date('start_date')->nullable()->after('alias_image');
            }

            if (!Schema::hasColumn('inventory_custom_category_items', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('inventory_custom_category_items', 'notes')) {
                $table->string('notes', 500)->nullable()->after('end_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_custom_category_items', function (Blueprint $table) {
            $columns = ['notes', 'end_date', 'start_date', 'alias_image', 'alias_color'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('inventory_custom_category_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}


