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
            $table->string('sales_code')->unique(); // کد فروش - Sales Code
            $table->string('sales_title'); // عنوان فروش - Sales Title
            $table->text('sales_description')->nullable(); // توضیحات - Description
            $table->string('sales_type')->default('sale'); // نوع - Type (sale, return, refund)
            $table->decimal('sales_quantity', 15, 2)->default(1.00); // تعداد - Quantity
            $table->decimal('sales_unit_price', 15, 2)->default(0.00); // قیمت واحد - Unit Price
            $table->decimal('sales_total_amount', 15, 2)->default(0.00); // مبلغ کل - Total Amount
            $table->decimal('sales_discount_amount', 15, 2)->default(0.00); // مبلغ تخفیف - Discount Amount
            $table->decimal('sales_discount_percentage', 5, 2)->default(0.00); // درصد تخفیف - Discount Percentage
            $table->decimal('sales_tax_amount', 15, 2)->default(0.00); // مبلغ مالیات - Tax Amount
            $table->decimal('sales_tax_percentage', 5, 2)->default(0.00); // درصد مالیات - Tax Percentage
            $table->decimal('sales_final_amount', 15, 2)->default(0.00); // مبلغ نهایی - Final Amount
            $table->string('sales_currency', 3)->default('IRR'); // ارز - Currency
            $table->string('sales_status')->default('pending'); // وضعیت - Status (pending, completed, cancelled, refunded)
            $table->string('sales_payment_status')->default('unpaid'); // وضعیت پرداخت - Payment Status (unpaid, paid, partially_paid, overdue)
            $table->string('sales_payment_method')->nullable(); // روش پرداخت - Payment Method
            $table->date('sales_date'); // تاریخ فروش - Sales Date
            $table->date('sales_due_date')->nullable(); // تاریخ سررسید - Due Date
            $table->unsignedBigInteger('sales_creatorid');
            $table->unsignedBigInteger('sales_clientid')->nullable();
            $table->unsignedBigInteger('sales_projectid')->nullable();
            $table->unsignedBigInteger('sales_categoryid')->nullable();
            $table->unsignedBigInteger('sales_inventory_id')->nullable(); // ارتباط با موجودی - Inventory Link
            $table->string('sales_reference')->nullable(); // مرجع - Reference
            $table->text('sales_notes')->nullable(); // یادداشت - Notes
            $table->string('sales_salesperson')->nullable(); // فروشنده - Salesperson
            $table->string('sales_customer_name')->nullable(); // نام مشتری - Customer Name
            $table->string('sales_customer_phone')->nullable(); // تلفن مشتری - Customer Phone
            $table->string('sales_customer_address')->nullable(); // آدرس مشتری - Customer Address
            $table->string('sales_invoice_number')->nullable(); // شماره فاکتور - Invoice Number
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
