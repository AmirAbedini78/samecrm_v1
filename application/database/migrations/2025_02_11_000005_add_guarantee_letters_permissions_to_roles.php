<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuaranteeLettersPermissionsToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->integer('role_guarantee_letters')->default(0)->after('role_sales');
            $table->string('role_guarantee_letters_scope', 20)->default('own')->after('role_guarantee_letters'); // own, global
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['role_guarantee_letters', 'role_guarantee_letters_scope']);
        });
    }
}

