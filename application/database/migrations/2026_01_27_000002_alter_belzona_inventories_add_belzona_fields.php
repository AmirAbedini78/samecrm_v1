<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBelzonaInventoriesAddBelzonaFields extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('belzona_inventories')) {
            return;
        }

        Schema::table('belzona_inventories', function (Blueprint $table) {
            // These columns are used by the XLSX multi-sheet import (sheet meta + row identity + raw date + notes)
            if (!Schema::hasColumn('belzona_inventories', 'product_weight_value')) {
                $table->decimal('product_weight_value', 15, 2)->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('belzona_inventories', 'product_weight_unit')) {
                $table->string('product_weight_unit')->nullable()->after('product_weight_value');
            }
            if (!Schema::hasColumn('belzona_inventories', 'product_weight_raw')) {
                $table->string('product_weight_raw')->nullable()->after('product_weight_unit');
            }
            if (!Schema::hasColumn('belzona_inventories', 'sheet_name')) {
                $table->string('sheet_name')->nullable()->after('product_weight_raw');
            }
            if (!Schema::hasColumn('belzona_inventories', 'sheet_row_number')) {
                $table->unsignedInteger('sheet_row_number')->nullable()->after('sheet_name');
            }
            if (!Schema::hasColumn('belzona_inventories', 'date_raw')) {
                $table->string('date_raw')->nullable()->after('date');
            }
            if (!Schema::hasColumn('belzona_inventories', 'notes')) {
                $table->text('notes')->nullable()->after('customer_name');
            }
        });

        // add unique index for idempotent import (guard against duplicates)
        if (
            Schema::hasColumn('belzona_inventories', 'sheet_name') &&
            Schema::hasColumn('belzona_inventories', 'sheet_row_number')
        ) {
            // MySQL/MariaDB require explicit index name handling; try/catch keeps migration idempotent.
            try {
                Schema::table('belzona_inventories', function (Blueprint $table) {
                    $table->unique(['sheet_name', 'sheet_row_number'], 'belzona_sheet_row_unique');
                });
            } catch (\Throwable $e) {
                // ignore if index already exists
            }
        }
    }

    public function down()
    {
        if (!Schema::hasTable('belzona_inventories')) {
            return;
        }

        // safest rollback: only drop the unique index if it exists; keep columns to avoid data loss
        try {
            Schema::table('belzona_inventories', function (Blueprint $table) {
                $table->dropUnique('belzona_sheet_row_unique');
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }
}

