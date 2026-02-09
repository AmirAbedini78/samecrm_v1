<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Runs raw DB queries and writes a full report for smart features analysis.
 */
class SmartFeaturesDbReport extends Command
{
    protected $signature = 'smart-features:db-report {--output= : Path to output file (default: storage/app/smart_features_db_report.txt) }';
    protected $description = 'کوئری به دیتابیس و خروجی کامل برای بررسی فیچرهای هوشمند';

    public function handle()
    {
        $outPath = $this->option('output') ?: storage_path('app/smart_features_db_report.txt');
        $fp = fopen($outPath, 'w');
        if (!$fp) {
            $this->error('Cannot write to ' . $outPath);
            return 1;
        }

        $w = function ($line = '') use ($fp) {
            fwrite($fp, $line . "\n");
        };

        $w('========== گزارش داده‌های دیتابیس برای فیچرهای هوشمند ==========');
        $w(date('Y-m-d H:i:s'));
        $w('');

        try {
            // ---- Counts ----
            $w('--- تعداد رکوردها ---');
            $tables = [
                'sales' => 'sales',
                'inventory' => 'inventory',
                'inventory_entries' => 'inventory_entries',
                'belzona_inventories' => 'belzona_inventories',
                'invoice_settlements' => 'invoice_settlements',
            ];
            foreach ($tables as $name => $table) {
                $c = DB::table($table)->count();
                $w("$name: $c");
            }
            $w('');

            // ---- Sales: columns and date stats ----
            $w('--- جدول sales (فروش) ---');
            $salesCount = DB::table('sales')->count();
            $w("تعداد کل: $salesCount");
            if ($salesCount > 0) {
                $minDate = DB::table('sales')->min('document_date');
                $maxDate = DB::table('sales')->max('document_date');
                $w("document_date MIN: $minDate");
                $w("document_date MAX: $maxDate");
                $w('نمونه ۱۰ رکورد (document_date, product_code, product_name, customer_name, main_quantity, base_sales_amount):');
                $rows = DB::table('sales')->select('document_date', 'product_code', 'product_name', 'customer_name', 'main_quantity', 'base_sales_amount')->limit(10)->get();
                foreach ($rows as $r) {
                    $w(sprintf("  %s | %s | %s | %s | %s | %s", $r->document_date ?? '', $r->product_code ?? '', $r->product_name ?? '', $r->customer_name ?? '', $r->main_quantity ?? '', $r->base_sales_amount ?? ''));
                }
                $w('توزیع تعداد رکورد به ازای سال (از روی ۴ کاراکتر اول document_date):');
                $yearCounts = DB::table('sales')->select(DB::raw('LEFT(CAST(document_date AS CHAR), 4) as y'), DB::raw('COUNT(*) as c'))->groupBy('y')->orderBy('y')->get();
                foreach ($yearCounts as $yc) {
                    $w("  سال $yc->y: $yc->c رکورد");
                }
                $w('تعداد مشتری یکتا (customer_name نه خالی):');
                $custCount = DB::table('sales')->whereNotNull('customer_name')->where('customer_name', '!=', '')->distinct('customer_name')->count('customer_name');
                $w("  $custCount");
                $w('تعداد محصول یکتا (بر اساس product_name):');
                $prodCount = DB::table('sales')->whereNotNull('product_name')->where('product_name', '!=', '')->distinct()->count('product_name');
                $w("  $prodCount");
            }
            $w('');

            // ---- Inventory ----
            $w('--- جدول inventory (انبار) ---');
            $invCount = DB::table('inventory')->count();
            $invActive = DB::table('inventory')->where('inventory_status', 'active')->count();
            $w("تعداد کل: $invCount | فعال: $invActive");
            $withMin = DB::table('inventory')->where('inventory_status', 'active')->where('minimum_stock', '>', 0)->count();
            $w("دارای حداقل موجودی > 0: $withMin");
            if ($invActive > 0) {
                $w('نمونه ۵ رکورد (inventory_code, inventory_name, current_quantity, minimum_stock):');
                $rows = DB::table('inventory')->where('inventory_status', 'active')->select('inventory_code', 'inventory_name', 'current_quantity', 'minimum_stock')->limit(5)->get();
                foreach ($rows as $r) {
                    $w("  " . ($r->inventory_code ?? '') . " | " . ($r->inventory_name ?? '') . " | " . ($r->current_quantity ?? '') . " | " . ($r->minimum_stock ?? ''));
                }
            }
            $w('');

            // ---- inventory_entries ----
            $w('--- جدول inventory_entries ---');
            $entCount = DB::table('inventory_entries')->count();
            $w("تعداد: $entCount");
            if ($entCount > 0) {
                $types = DB::table('inventory_entries')->select('entry_type', DB::raw('COUNT(*) as c'))->groupBy('entry_type')->get();
                foreach ($types as $t) {
                    $w("  نوع $t->entry_type: $t->c");
                }
            }
            $w('');

            // ---- Belzona ----
            $w('--- جدول belzona_inventories ---');
            $belCount = DB::table('belzona_inventories')->count();
            $w("تعداد: $belCount");
            if ($belCount > 0) {
                $minD = DB::table('belzona_inventories')->min('date');
                $maxD = DB::table('belzona_inventories')->max('date');
                $w("بازه date: $minD تا $maxD");
                $w('نمونه ۵ رکورد (product_name, date, input, output, balance, customer_name):');
                $rows = DB::table('belzona_inventories')->select('product_name', 'date', 'input', 'output', 'balance', 'customer_name')->limit(5)->get();
                foreach ($rows as $r) {
                    $d = $r->date ? (is_object($r->date) ? $r->date->format('Y-m-d') : $r->date) : '';
                    $w("  " . ($r->product_name ?? '') . " | $d | " . ($r->input ?? '') . " | " . ($r->output ?? '') . " | " . ($r->balance ?? '') . " | " . ($r->customer_name ?? ''));
                }
                $sumOut = DB::table('belzona_inventories')->sum('output');
                $sumIn = DB::table('belzona_inventories')->sum('input');
                $w("مجموع output: $sumOut | مجموع input: $sumIn");
                $prodCountB = DB::table('belzona_inventories')->whereNotNull('product_name')->where('product_name', '!=', '')->distinct('product_name')->count('product_name');
                $w("تعداد محصول یکتا (product_name): $prodCountB");
            }
            $w('');

            // ---- Invoice settlements ----
            $w('--- جدول invoice_settlements ---');
            $setCount = DB::table('invoice_settlements')->count();
            $w("تعداد: $setCount");
            if ($setCount > 0) {
                $w('نمونه ۵ رکورد (document_date, customer_name, base_net_amount, paid_amount, balance_amount):');
                $rows = DB::table('invoice_settlements')->select('document_date', 'customer_name', 'base_net_amount', 'paid_amount', 'balance_amount')->limit(5)->get();
                foreach ($rows as $r) {
                    $w("  " . ($r->document_date ?? '') . " | " . ($r->customer_name ?? '') . " | " . ($r->base_net_amount ?? '') . " | " . ($r->paid_amount ?? '') . " | " . ($r->balance_amount ?? ''));
                }
                $sumBal = DB::table('invoice_settlements')->sum(DB::raw('COALESCE(balance_amount, 0)'));
                $w("مجموع مانده (balance_amount): $sumBal");
            }
            $w('');
            $w('--- پایان گزارش ---');
        } catch (\Throwable $e) {
            $w('خطا: ' . $e->getMessage());
        }

        fclose($fp);
        $this->info('Report written to: ' . $outPath);
        return 0;
    }
}
