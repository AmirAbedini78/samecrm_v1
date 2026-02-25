<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBelzonaUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     * Stores per-user Belzona inventory settings (e.g. last batch selection for COC).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belzona_user_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('setting_key', 64)->index();
            $table->text('setting_value')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'setting_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belzona_user_settings');
    }
}
