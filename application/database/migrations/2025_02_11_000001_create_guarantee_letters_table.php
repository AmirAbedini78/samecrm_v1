<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuaranteeLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_letters', function (Blueprint $table) {
            $table->bigIncrements('guarantee_id');
            
            // Basic Information
            $table->string('guarantee_number')->unique(); // شماره ضمانت نامه
            $table->string('guarantee_type', 50); // نوع: tender_participation, performance, advance_payment
            $table->string('industrial_type', 50); // نوع صنعتی: bearing, belzona, pipe
            
            // Dates
            $table->date('issue_date'); // تاریخ صدور
            $table->date('expiry_date')->nullable(); // تاریخ انقضا
            $table->date('renewal_date')->nullable(); // تاریخ تمدید
            $table->date('settlement_date')->nullable(); // تاریخ تسویه
            
            // Financial Information
            $table->decimal('amount', 15, 2)->default(0.00); // مبلغ
            $table->string('currency', 10)->default('IRR'); // ارز
            
            // Bank and Beneficiary
            $table->string('issuing_bank')->nullable(); // بانک صادرکننده
            $table->string('beneficiary')->nullable(); // ذینفع
            
            // Status
            $table->string('status', 20)->default('active'); // active, expired, settled, renewed
            
            // Assignment
            $table->unsignedBigInteger('assigned_user_id')->nullable(); // کاربر مسئول
            
            // System Fields
            $table->unsignedBigInteger('guarantee_creatorid'); // ایجادکننده
            $table->text('description')->nullable(); // توضیحات
            
            $table->timestamps();
            
            // Indexes
            $table->index('guarantee_type');
            $table->index('industrial_type');
            $table->index('status');
            $table->index('assigned_user_id');
            $table->index('issue_date');
            $table->index('expiry_date');
            
            // Foreign keys (commented out - add if needed)
            // $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('guarantee_creatorid')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantee_letters');
    }
}

