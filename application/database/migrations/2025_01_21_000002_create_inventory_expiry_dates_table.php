<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryExpiryDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_expiry_dates', function (Blueprint $table) {
            $table->bigIncrements('expiry_id');
            
            // Inventory Reference
            $table->unsignedBigInteger('inventory_id');
            
            // Expiry Date
            $table->date('expiry_date')->nullable(); // تاریخ انقضا
            
            // Auto Expiry Settings
            $table->integer('auto_expiry_days')->nullable(); // بازه پیش‌فرض (روز)
            $table->integer('alert_days_before')->default(7); // تعداد روز قبل از انقضا برای هشدار
            
            // Status
            $table->boolean('is_expired')->default(false); // آیا منقضی شده است
            
            $table->timestamps();
            
            // Indexes
            $table->index('inventory_id');
            $table->index('expiry_date');
            $table->index('is_expired');
            
            // Foreign key
            // $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_expiry_dates');
    }
}



