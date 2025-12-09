<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuaranteeLetterAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_letter_assignments', function (Blueprint $table) {
            $table->bigIncrements('assignment_id');
            
            // References
            $table->unsignedBigInteger('guarantee_id');
            $table->unsignedBigInteger('user_id');
            
            // Assignment Info
            $table->timestamp('assigned_at');
            $table->unsignedBigInteger('assigned_by'); // تخصیص‌دهنده
            
            $table->timestamps();
            
            // Indexes
            $table->index('guarantee_id');
            $table->index('user_id');
            $table->unique(['guarantee_id', 'user_id']); // یک ضمانت نامه نمی‌تواند به یک کاربر دو بار تخصیص شود
            
            // Foreign keys
            // $table->foreign('guarantee_id')->references('guarantee_id')->on('guarantee_letters')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantee_letter_assignments');
    }
}

