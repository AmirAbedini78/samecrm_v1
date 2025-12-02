<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryAlertSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_alert_settings', function (Blueprint $table) {
            $table->bigIncrements('alert_id');
            
            // Inventory Reference (nullable for global settings)
            $table->unsignedBigInteger('inventory_id')->nullable();
            
            // Alert Type: expiry, quantity, minimum, maximum
            $table->string('alert_type', 50); // expiry/quantity/minimum/maximum
            
            // Threshold Value
            $table->decimal('threshold_value', 15, 2)->nullable(); // مقدار آستانه (برای quantity/minimum/maximum)
            $table->integer('threshold_days')->nullable(); // تعداد روز (برای expiry)
            
            // Alert Channels
            $table->boolean('alert_email')->default(true); // ارسال ایمیل
            $table->boolean('alert_sms')->default(false); // ارسال SMS
            
            // Email addresses (comma separated)
            $table->text('alert_email_addresses')->nullable();
            
            // Phone numbers (comma separated)
            $table->text('alert_phone_numbers')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true); // فعال/غیرفعال
            
            $table->timestamps();
            
            // Indexes
            $table->index('inventory_id');
            $table->index('alert_type');
            $table->index('is_active');
            
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
        Schema::dropIfExists('inventory_alert_settings');
    }
}


