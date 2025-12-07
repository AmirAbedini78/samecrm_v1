<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            // شماره سند مبنا (Base Document Number)
            $table->string('base_document_number')->nullable()->after('document_number');
            
            // فی (Unit Price)
            $table->decimal('unit_price', 15, 2)->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn(['base_document_number', 'unit_price']);
        });
    }
}

