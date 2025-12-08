<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInventoryCustomCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_custom_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_custom_categories', 'category_type')) {
                $table->string('category_type', 20)->default('item')->after('category_name');
            }

            if (!Schema::hasColumn('inventory_custom_categories', 'description')) {
                $table->text('description')->nullable()->after('category_image');
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
        Schema::table('inventory_custom_categories', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_custom_categories', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('inventory_custom_categories', 'category_type')) {
                $table->dropColumn('category_type');
            }
        });
    }
}





