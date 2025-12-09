<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuaranteeLetterNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_letter_notifications', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            
            // Guarantee Letter Reference
            $table->unsignedBigInteger('guarantee_id');
            
            // Notification Type
            $table->string('notification_type', 20)->default('both'); // email, sms, both
            
            // Date Column to Monitor
            $table->string('date_column', 50); // issue_date, expiry_date, renewal_date, settlement_date
            
            // Alert Timing
            $table->integer('alert_days_before')->default(0); // چند روز قبل از تاریخ
            $table->integer('alert_days_after')->default(0); // چند روز بعد از تاریخ (برای تاریخ‌های گذشته)
            
            // Repeat Settings
            $table->integer('repeat_interval_days')->default(0); // هر چند روز یکبار تکرار
            $table->integer('max_repeats')->default(1); // حداکثر تعداد تکرار
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Custom Message
            $table->text('custom_message')->nullable(); // پیام سفارشی
            
            // Recipients (JSON array of user IDs)
            $table->text('recipient_user_ids')->nullable();
            
            // Tracking
            $table->timestamp('last_sent_at')->nullable();
            $table->integer('sent_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('guarantee_id');
            $table->index('is_active');
            $table->index('date_column');
            
            // Foreign key
            // $table->foreign('guarantee_id')->references('guarantee_id')->on('guarantee_letters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantee_letter_notifications');
    }
}

