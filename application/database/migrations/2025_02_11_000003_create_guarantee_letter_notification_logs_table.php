<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuaranteeLetterNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_letter_notification_logs', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            
            // References
            $table->unsignedBigInteger('guarantee_id');
            $table->unsignedBigInteger('notification_id')->nullable();
            
            // Sent Information
            $table->timestamp('sent_at');
            $table->string('sent_to'); // ایمیل یا شماره تلفن
            $table->string('sent_type', 20); // email, sms
            
            // Message
            $table->text('message');
            
            // Status
            $table->string('status', 20)->default('sent'); // sent, failed
            $table->text('error_message')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('guarantee_id');
            $table->index('notification_id');
            $table->index('sent_at');
            $table->index('status');
            
            // Foreign keys
            // $table->foreign('guarantee_id')->references('guarantee_id')->on('guarantee_letters')->onDelete('cascade');
            // $table->foreign('notification_id')->references('notification_id')->on('guarantee_letter_notifications')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantee_letter_notification_logs');
    }
}

