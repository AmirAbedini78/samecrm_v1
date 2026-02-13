<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShelfLifeToBelzonaInventories extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('belzona_inventories')) {
            return;
        }

        Schema::table('belzona_inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
                $table->decimal('shelf_life_years', 6, 2)->nullable()->after('notes');
            }
            if (!Schema::hasColumn('belzona_inventories', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('shelf_life_years');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('belzona_inventories')) {
            return;
        }

        Schema::table('belzona_inventories', function (Blueprint $table) {
            if (Schema::hasColumn('belzona_inventories', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }
            if (Schema::hasColumn('belzona_inventories', 'shelf_life_years')) {
                $table->dropColumn('shelf_life_years');
            }
        });
    }
}
