<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryCustomCategoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_custom_category_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // References
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('custom_category_id');
            
            // Alias Name (نام مستعار)
            $table->string('alias_name')->nullable(); // نام مستعار برای این کالا در این دسته
            
            $table->timestamps();
            
            // Indexes
            $table->index('inventory_id');
            $table->index('custom_category_id');
            
            // Unique constraint: one inventory can only be in a category once
            $table->unique(['inventory_id', 'custom_category_id'], 'inventory_category_unique');
            
            // Foreign keys
            // $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
            // $table->foreign('custom_category_id')->references('category_id')->on('inventory_custom_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_custom_category_items');
    }
}





