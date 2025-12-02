<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->bigIncrements('transaction_id');
            
            // Inventory Reference
            $table->unsignedBigInteger('inventory_id');
            
            // Transaction Type: input (ورود) or output (خروج)
            $table->string('transaction_type', 20); // input/output
            
            // Quantities
            $table->decimal('quantity', 15, 2)->default(0.00); // مقدار اصلی
            $table->decimal('sub_quantity', 15, 2)->default(0.00); // مقدار واحد فرعی
            $table->decimal('amount', 15, 2)->default(0.00); // مبلغ
            
            // Transaction Details
            $table->date('transaction_date'); // تاریخ تراکنش
            $table->string('document_number')->nullable(); // شماره سند
            $table->string('warehouse')->nullable(); // انبار
            $table->text('notes')->nullable(); // توضیحات
            
            // User who created this transaction
            $table->unsignedBigInteger('user_id');
            
            $table->timestamps();
            
            // Indexes
            $table->index('inventory_id');
            $table->index('transaction_type');
            $table->index('transaction_date');
            $table->index('user_id');
            
            // Foreign keys (commented out for now, can be enabled later)
            // $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
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
        Schema::dropIfExists('inventory_transactions');
    }
}


