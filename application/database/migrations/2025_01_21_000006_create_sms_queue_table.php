<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_queue', function (Blueprint $table) {
            $table->bigIncrements('sms_id');
            
            // SMS Details
            $table->string('sms_to', 20); // شماره تلفن گیرنده
            $table->text('sms_message'); // متن پیامک
            
            // SMS Type
            $table->string('sms_type', 50)->default('general'); // general/inventory_alert/expiry_alert
            
            // Status: new, sent, failed
            $table->string('sms_status', 20)->default('new'); // new/sent/failed
            
            // Timestamps
            $table->timestamp('sms_sent_at')->nullable(); // زمان ارسال
            $table->text('sms_error')->nullable(); // خطا در صورت عدم ارسال
            
            $table->timestamps();
            
            // Indexes
            $table->index('sms_status');
            $table->index('sms_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_queue');
    }
}

