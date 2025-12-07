<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_entries', function (Blueprint $table) {
            $table->bigIncrements('entry_id');
            $table->unsignedBigInteger('inventory_id');
            $table->date('entry_date')->nullable(); // تاريخ
            $table->string('entry_code', 120)->nullable(); // سند
            $table->string('entry_type', 50)->nullable(); // نوع (ورودی/خروجی)
            $table->string('document_number', 120)->nullable(); // شماره سند مبنا
            $table->decimal('quantity', 18, 4)->default(0); // مقدار
            $table->decimal('unit_price', 18, 4)->default(0); // في
            $table->decimal('total_amount', 18, 4)->default(0); // مبلغ تمام شده
            $table->string('import_batch')->nullable();
            $table->timestamps();

            $table->index('inventory_id');
            $table->index('entry_date');
            $table->index('entry_code');
            $table->index('entry_type');

            $table->foreign('inventory_id')
                ->references('inventory_id')
                ->on('inventory')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_entries');
    }
}
