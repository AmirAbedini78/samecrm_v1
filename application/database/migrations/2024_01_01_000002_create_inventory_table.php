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
            $table->string('inventory_code')->unique(); // کد کالا - Item Code
            $table->string('inventory_name'); // نام کالا - Item Name
            $table->text('inventory_description')->nullable(); // توضیحات - Description
            $table->string('inventory_sku')->unique(); // SKU
            $table->string('inventory_barcode')->nullable(); // بارکد - Barcode
            $table->decimal('inventory_quantity', 15, 2)->default(0.00); // موجودی - Stock Quantity
            $table->decimal('inventory_minimum_quantity', 15, 2)->default(0.00); // حداقل موجودی - Min Stock
            $table->decimal('inventory_maximum_quantity', 15, 2)->nullable(); // حداکثر موجودی - Max Stock
            $table->decimal('inventory_cost_price', 15, 2)->default(0.00); // قیمت خرید - Cost Price
            $table->decimal('inventory_selling_price', 15, 2)->default(0.00); // قیمت فروش - Selling Price
            $table->decimal('inventory_wholesale_price', 15, 2)->default(0.00); // قیمت عمده فروشی - Wholesale Price
            $table->decimal('inventory_retail_price', 15, 2)->default(0.00); // قیمت خرده فروشی - Retail Price
            $table->string('inventory_currency', 3)->default('IRR'); // ارز - Currency
            $table->string('inventory_unit')->default('pcs'); // واحد - Unit (pcs, kg, liter, etc.)
            $table->string('inventory_status')->default('active'); // وضعیت - Status (active, inactive, discontinued)
            $table->unsignedBigInteger('inventory_creatorid');
            $table->unsignedBigInteger('inventory_categoryid')->nullable();
            $table->string('inventory_supplier')->nullable(); // تامین کننده - Supplier
            $table->text('inventory_notes')->nullable(); // یادداشت - Notes
            $table->string('inventory_location')->nullable(); // مکان - Location
            $table->date('inventory_last_restocked')->nullable(); // آخرین ورود - Last Restocked
            $table->date('inventory_expiry_date')->nullable(); // تاریخ انقضا - Expiry Date
            $table->string('inventory_brand')->nullable(); // برند - Brand
            $table->string('inventory_model')->nullable(); // مدل - Model
            $table->string('inventory_serial_number')->nullable(); // شماره سریال - Serial Number
            $table->decimal('inventory_weight', 10, 3)->nullable(); // وزن - Weight
            $table->string('inventory_dimensions')->nullable(); // ابعاد - Dimensions
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
