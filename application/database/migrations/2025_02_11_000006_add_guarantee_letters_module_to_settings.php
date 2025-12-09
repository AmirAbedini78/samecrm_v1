<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuaranteeLettersModuleToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'settings_modules_guarantee_letters')) {
                    // Try to add after settings_modules_reports if it exists, otherwise after settings_modules_calendar, or just add at the end
                    if (Schema::hasColumn('settings', 'settings_modules_reports')) {
                        $table->string('settings_modules_guarantee_letters', 20)->default('enabled')->after('settings_modules_reports');
                    } elseif (Schema::hasColumn('settings', 'settings_modules_calendar')) {
                        $table->string('settings_modules_guarantee_letters', 20)->default('enabled')->after('settings_modules_calendar');
                    } else {
                        $table->string('settings_modules_guarantee_letters', 20)->default('enabled');
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (Schema::hasColumn('settings', 'settings_modules_guarantee_letters')) {
                    $table->dropColumn('settings_modules_guarantee_letters');
                }
            });
        }
    }
}

