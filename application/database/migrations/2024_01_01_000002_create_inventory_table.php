<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->bigIncrements('inventory_id');
            
            // Basic Information
            $table->string('inventory_code')->unique(); // کد کالا
            $table->string('inventory_name'); // عنوان کالا
            
            // First Period (اول دوره)
            $table->decimal('first_period_quantity', 15, 2)->default(0.00); // اول دوره-مقدار
            $table->decimal('first_period_sub_quantity', 15, 2)->default(0.00); // اول دوره-مقدار واحد فرعي
            $table->decimal('first_period_amount', 15, 2)->default(0.00); // اول دوره-مبلغ
            $table->decimal('first_period_avg_price', 15, 2)->default(0.00); // اول دوره-في متوسط
            
            // Input (ورودي)
            $table->decimal('input_quantity', 15, 2)->default(0.00); // ورودي-مقدار
            $table->decimal('input_sub_quantity', 15, 2)->default(0.00); // ورودي-مقدار واحد فرعي
            $table->decimal('input_amount', 15, 2)->default(0.00); // ورودي-مبلغ
            $table->decimal('input_avg_price', 15, 2)->default(0.00); // ورودي-في متوسط
            
            // Output (خروجي)
            $table->decimal('output_quantity', 15, 2)->default(0.00); // خروجي-مقدار
            $table->decimal('output_sub_quantity', 15, 2)->default(0.00); // خروجي-مقدار واحد فرعي
            $table->decimal('output_amount', 15, 2)->default(0.00); // خروجي-مبلغ
            $table->decimal('output_avg_price', 15, 2)->default(0.00); // خروجي-في متوسط
            
            // Current Stock (موجودي)
            $table->decimal('current_quantity', 15, 2)->default(0.00); // موجودي-مقدار
            $table->decimal('current_sub_quantity', 15, 2)->default(0.00); // موجودي-مقدار واحد فرعي
            $table->decimal('current_amount', 15, 2)->default(0.00); // موجودي-مبلغ
            $table->decimal('current_avg_price', 15, 2)->default(0.00); // موجودي-في متوسط
            
            // Weighing (توزين)
            $table->decimal('weighing_input', 15, 2)->default(0.00); // توزين - ورود
            $table->decimal('weighing_output', 15, 2)->default(0.00); // توزين - خروج
            
            // Stock Limits
            $table->decimal('minimum_stock', 15, 2)->default(0.00); // حداقل موجودي
            $table->decimal('maximum_stock', 15, 2)->nullable(); // حداکثر موجودي
            $table->decimal('discrepancy', 15, 2)->default(0.00); // مغايرت
            
            // Units
            $table->string('main_unit')->default('pcs'); // واحد اصلي
            $table->string('sub_unit')->nullable(); // واحد فرعي
            
            // System Fields
            $table->string('inventory_status')->default('active');
            $table->unsignedBigInteger('inventory_creatorid');
            $table->unsignedBigInteger('inventory_categoryid')->nullable();
            $table->timestamps();

            // Foreign key constraints will be added later if needed
            // $table->foreign('inventory_creatorid')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('inventory_categoryid')->references('category_id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory');
    }
}
