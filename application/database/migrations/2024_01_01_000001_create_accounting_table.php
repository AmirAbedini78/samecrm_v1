<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting', function (Blueprint $table) {
            $table->bigIncrements('accounting_id');
            $table->string('accounting_title');
            $table->text('accounting_description')->nullable();
            $table->string('accounting_type')->default('general'); // general, income, expense
            $table->decimal('accounting_amount', 15, 2)->default(0.00);
            $table->string('accounting_currency', 3)->default('USD');
            $table->date('accounting_date');
            $table->string('accounting_status')->default('active'); // active, inactive, archived
            $table->unsignedBigInteger('accounting_creatorid');
            $table->unsignedBigInteger('accounting_categoryid')->nullable();
            $table->string('accounting_reference')->nullable();
            $table->text('accounting_notes')->nullable();
            $table->string('accounting_payment_method')->nullable();
            $table->string('accounting_payment_status')->default('pending'); // pending, paid, overdue
            $table->date('accounting_due_date')->nullable();
            $table->timestamps();
            
            $table->foreign('accounting_creatorid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('accounting_categoryid')->references('category_id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting');
    }
}

