<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateInventoryEntriesStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // بررسی وجود جدول
        if (!Schema::hasTable('inventory_entries')) {
            return;
        }

        // حذف ایندکس‌های قدیمی با استفاده از SQL مستقیم (اگر وجود داشته باشند)
        $indexes = [
            'inventory_entries_lot_number_index',
            'inventory_entries_serial_number_index',
            'inventory_entries_warehouse_id_index',
            'inventory_entries_expiry_date_index',
        ];

        foreach ($indexes as $indexName) {
            try {
                // بررسی وجود ایندکس قبل از حذف
                $indexExists = DB::select("SHOW INDEX FROM `inventory_entries` WHERE Key_name = ?", [$indexName]);
                if (!empty($indexExists)) {
                    DB::statement("ALTER TABLE `inventory_entries` DROP INDEX `{$indexName}`");
                }
            } catch (\Exception $e) {
                // اگر خطایی رخ داد، ادامه بده
            }
        }

        Schema::table('inventory_entries', function (Blueprint $table) {
            // حذف فیلدهای غیرضروری (اگر وجود داشته باشند)
            $columnsToDrop = [
                'lot_number',
                'serial_number',
                'warehouse_id',
                'warehouse_name',
                'expiry_date',
                'initial_quantity',
                'remaining_quantity',
                'unit_cost',
                'total_cost',
                'meta',
                'notes',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('inventory_entries', $column)) {
                    try {
                        $table->dropColumn($column);
                    } catch (\Exception $e) {
                        // اگر خطایی در حذف ستون رخ داد، ادامه بده
                    }
                }
            }

            // اضافه کردن فیلدهای جدید
            if (!Schema::hasColumn('inventory_entries', 'entry_type')) {
                $table->string('entry_type', 50)->nullable()->after('entry_code');
            }
            if (!Schema::hasColumn('inventory_entries', 'document_number')) {
                $table->string('document_number', 120)->nullable()->after('entry_type');
            }
            if (!Schema::hasColumn('inventory_entries', 'quantity')) {
                $table->decimal('quantity', 18, 4)->default(0)->after('document_number');
            }
            if (!Schema::hasColumn('inventory_entries', 'unit_price')) {
                $table->decimal('unit_price', 18, 4)->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('inventory_entries', 'total_amount')) {
                $table->decimal('total_amount', 18, 4)->default(0)->after('unit_price');
            }
        });

        // اضافه کردن ایندکس جدید (اگر وجود نداشته باشد)
        try {
            Schema::table('inventory_entries', function (Blueprint $table) {
                $table->index('entry_type');
            });
        } catch (\Exception $e) {
            // اگر ایندکس از قبل وجود دارد، خطا را نادیده بگیر
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_entries', function (Blueprint $table) {
            // حذف ایندکس جدید
            try {
                $table->dropIndex(['entry_type']);
            } catch (\Exception $e) {
                // نادیده گرفتن خطا
            }

            // حذف فیلدهای جدید
            $columnsToDrop = ['entry_type', 'document_number', 'quantity', 'unit_price', 'total_amount'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('inventory_entries', $column)) {
                    try {
                        $table->dropColumn($column);
                    } catch (\Exception $e) {
                        // نادیده گرفتن خطا
                    }
                }
            }

            // برگرداندن فیلدهای قدیمی
            if (!Schema::hasColumn('inventory_entries', 'lot_number')) {
                $table->string('lot_number', 120)->nullable();
            }
            if (!Schema::hasColumn('inventory_entries', 'serial_number')) {
                $table->string('serial_number', 120)->nullable();
            }
            if (!Schema::hasColumn('inventory_entries', 'warehouse_id')) {
                $table->unsignedBigInteger('warehouse_id')->nullable();
            }
            if (!Schema::hasColumn('inventory_entries', 'warehouse_name')) {
                $table->string('warehouse_name')->nullable();
            }
            if (!Schema::hasColumn('inventory_entries', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
            if (!Schema::hasColumn('inventory_entries', 'initial_quantity')) {
                $table->decimal('initial_quantity', 18, 4)->default(0);
            }
            if (!Schema::hasColumn('inventory_entries', 'remaining_quantity')) {
                $table->decimal('remaining_quantity', 18, 4)->default(0);
            }
            if (!Schema::hasColumn('inventory_entries', 'unit_cost')) {
                $table->decimal('unit_cost', 18, 4)->default(0);
            }
            if (!Schema::hasColumn('inventory_entries', 'total_cost')) {
                $table->decimal('total_cost', 18, 4)->default(0);
            }
            if (!Schema::hasColumn('inventory_entries', 'meta')) {
                $table->json('meta')->nullable();
            }
            if (!Schema::hasColumn('inventory_entries', 'notes')) {
                $table->text('notes')->nullable();
            }

            // برگرداندن ایندکس‌ها
            try {
                $table->index('lot_number');
                $table->index('serial_number');
                $table->index('warehouse_id');
                $table->index('expiry_date');
            } catch (\Exception $e) {
                // نادیده گرفتن خطا
            }
        });
    }
}
