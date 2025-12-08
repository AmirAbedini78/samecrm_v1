<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventoryEntryIdToCustomItemsAndAlerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_custom_category_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_custom_category_items', 'inventory_entry_id')) {
                $table->unsignedBigInteger('inventory_entry_id')->nullable()->after('inventory_id');
                $table->index('inventory_entry_id');
            }
            $table->unique(
                ['custom_category_id', 'inventory_id', 'inventory_entry_id'],
                'custom_category_inventory_entry_unique'
            );
        });

        Schema::table('inventory_alert_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_alert_settings', 'inventory_entry_id')) {
                $table->unsignedBigInteger('inventory_entry_id')->nullable()->after('inventory_id');
                $table->index('inventory_entry_id');
            }
            $table->unique(
                ['inventory_id', 'inventory_entry_id', 'alert_type'],
                'inventory_alert_entry_unique'
            );
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
            if (Schema::hasColumn('inventory_custom_category_items', 'inventory_entry_id')) {
                $table->dropUnique('custom_category_inventory_entry_unique');
                $table->dropIndex(['inventory_entry_id']);
                $table->dropColumn('inventory_entry_id');
            }
        });

        Schema::table('inventory_alert_settings', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_alert_settings', 'inventory_entry_id')) {
                $table->dropUnique('inventory_alert_entry_unique');
                $table->dropIndex(['inventory_entry_id']);
                $table->dropColumn('inventory_entry_id');
            }
        });
    }
}



