<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        if (!Schema::hasColumn('settings2', 'settings2_font_settings')) {
            Schema::table('settings2', function (Blueprint $table) {
                $table->text('settings2_font_settings')->nullable()->after('settings2_theme_css');
            });
        }
    }

    public function down(): void {
        if (Schema::hasColumn('settings2', 'settings2_font_settings')) {
            Schema::table('settings2', function (Blueprint $table) {
                $table->dropColumn('settings2_font_settings');
            });
        }
    }
};
