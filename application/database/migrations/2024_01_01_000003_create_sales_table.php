<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('sales_id');
            
            // Document Information
            $table->string('document_type')->default('sale'); // نوع سند
            $table->string('document_number')->unique(); // شماره
            $table->date('document_date'); // تاريخ
            
            // Customer Information
            $table->string('customer_code')->nullable(); // كد مشتري
            $table->string('customer_name'); // مشتري
            $table->string('customer_full_name')->nullable(); // نام مشتري
            $table->string('sales_type')->default('sale'); // نوع فروش
            
            // Product/Service Information
            $table->string('product_code')->nullable(); // كد كالا/خدمت
            $table->string('product_name'); // كالا/خدمت
            $table->string('product_barcode')->nullable(); // بار كد كالا
            $table->string('tracking_code')->nullable(); // رديابي
            $table->string('main_unit')->default('pcs'); // واحد اصلي
            $table->decimal('main_quantity', 15, 2)->default(0.00); // مقدار-اصلي
            $table->string('warehouse')->nullable(); // انبار
            
            // Pricing (Base Currency)
            $table->decimal('base_price', 15, 2)->default(0.00); // في به ارز پايه
            $table->decimal('base_sales_amount', 15, 2)->default(0.00); // مبلغ فروش به ارز پايه
            $table->decimal('base_tax_amount', 15, 2)->default(0.00); // ماليات به ارز پايه
            $table->decimal('base_duty_amount', 15, 2)->default(0.00); // عوارض به ارز پايه
            $table->decimal('base_additional_amount', 15, 2)->default(0.00); // اضافات به ارز پايه
            $table->decimal('base_increasing_factors', 15, 2)->default(0.00); // عوامل افزاينده به ارز پايه
            $table->decimal('base_net_amount', 15, 2)->default(0.00); // خالص به ارز پايه
            
            // Additional Information
            $table->string('month')->nullable(); // ماه
            $table->text('description')->nullable(); // توضيحات
            
            // Quantities
            $table->decimal('issued_main_quantity', 15, 2)->default(0.00); // مقدار خارج شده اصلي
            $table->decimal('issued_sub_quantity', 15, 2)->default(0.00); // مقدار خارج شده فرعي
            $table->decimal('remaining_main_quantity', 15, 2)->default(0.00); // مانده خارج نشده اصلي
            $table->decimal('remaining_sub_quantity', 15, 2)->default(0.00); // مانده خارج نشده فرعي
            
            // Currency
            $table->string('currency', 3)->default('IRR'); // ارز
            
            // System Fields
            $table->string('sales_status')->default('pending');
            $table->unsignedBigInteger('sales_creatorid');
            $table->timestamps();

            // Foreign key constraints will be added later if needed
            // $table->foreign('sales_creatorid')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('sales_clientid')->references('client_id')->on('clients')->onDelete('set null');
            // $table->foreign('sales_projectid')->references('project_id')->on('projects')->onDelete('set null');
            // $table->foreign('sales_categoryid')->references('category_id')->on('categories')->onDelete('set null');
            // $table->foreign('sales_inventory_id')->references('inventory_id')->on('inventory')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
