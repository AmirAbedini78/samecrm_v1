<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            // تاریخ ورود به انبار
            $table->date('entry_date')->nullable()->after('inventory_name');
            
            // موجودی فیزیکی (آیا فیزیکی در انبار موجود است)
            $table->boolean('physical_available')->default(true)->after('entry_date');
            
            // بازه پیش‌فرض انقضا (روز)
            $table->integer('auto_expiry_default_days')->nullable()->after('physical_available');
            
            // Indexes
            $table->index('entry_date');
            $table->index('physical_available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn(['entry_date', 'physical_available', 'auto_expiry_default_days']);
        });
    }
}



