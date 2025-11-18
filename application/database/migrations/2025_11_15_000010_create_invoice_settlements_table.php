<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_settlements', function (Blueprint $table) {
            $table->bigIncrements('invoice_settlement_id');
            $table->string('document_number')->index();
            $table->string('document_date', 32)->nullable();
            $table->string('customer_name');
            $table->decimal('base_net_amount', 20, 2)->default(0);
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->decimal('balance_amount', 20, 2)->default(0);
            $table->string('currency', 3)->default('IRR');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->timestamps();

            $table->index('customer_name');
            $table->index('document_date');
            $table->index('balance_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_settlements');
    }
}

