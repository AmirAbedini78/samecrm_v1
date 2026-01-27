<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBelzonaInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belzona_inventories', function (Blueprint $table) {
            $table->bigIncrements('belzona_inventory_id');

            // Based on current BelzonaInventory model fields
            $table->string('product_name')->nullable();
            $table->dateTime('date')->nullable();

            // amounts
            $table->decimal('input', 15, 2)->default(0.00);
            $table->decimal('output', 15, 2)->default(0.00);
            $table->decimal('balance', 15, 2)->default(0.00);

            // reference fields
            $table->string('invoice_number')->nullable();
            $table->string('customer_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belzona_inventories');
    }
}

