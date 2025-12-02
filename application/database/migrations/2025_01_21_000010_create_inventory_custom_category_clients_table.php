<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryCustomCategoryClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_custom_category_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('custom_category_id');
            $table->unsignedBigInteger('client_id');
            $table->string('alias_name')->nullable();
            $table->string('alias_color', 20)->nullable();
            $table->string('alias_image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('custom_category_id');
            $table->index('client_id');
            $table->unique(['custom_category_id', 'client_id'], 'category_client_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_custom_category_clients');
    }
}

