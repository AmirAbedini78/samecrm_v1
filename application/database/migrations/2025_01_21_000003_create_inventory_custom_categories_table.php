<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCustomCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_custom_categories', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            
            // Category Information
            $table->string('category_name'); // نام دسته‌بندی
            $table->string('category_color', 20)->nullable(); // رنگ (hex code)
            $table->string('category_icon')->nullable(); // آیکون
            $table->string('category_image')->nullable(); // تصویر
            
            // Date Range (optional)
            $table->date('start_date')->nullable(); // تاریخ شروع
            $table->date('end_date')->nullable(); // تاریخ پایان
            
            // User who created this category
            $table->unsignedBigInteger('user_id');
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('start_date');
            $table->index('end_date');
            
            // Foreign key
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_custom_categories');
    }
}


