<?php

namespace App\Console\Commands;

use App\Models\Sales;
use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\BelzonaInventory;
use App\Models\InvoiceSettlement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SmartFeaturesDiagnostic extends Command
{
    protected $signature = 'smart-features:diagnostic';
    protected $description = 'بررسی داده‌های دیتابیس برای فیچرهای هوشمند (انبار، بلزونا، فروش، تسویه)';

    public function handle()
    {
        $this->info('=== تشخیص داده برای فیچرهای جدید ===');
        $this->newLine();

        $salesCount = Sales::count();
        $invCount = Inventory::count();
        $invEntryCount = InventoryEntry::count();
        $belzonaCount = BelzonaInventory::count();
        $settlementCount = InvoiceSettlement::count();

        $this->table(
            ['جدول', 'تعداد رکورد'],
            [
                ['sales (فروش)', $salesCount],
                ['inventory (انبار)', $invCount],
                ['inventory_entries (ورود انبار)', $invEntryCount],
                ['belzona_inventories (انبار بلزونا)', $belzonaCount],
                ['invoice_settlements (تسویه)', $settlementCount],
            ]
        );

        if ($salesCount > 0) {
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            $this->info("فروش: قدیمی‌ترین تاریخ = {$minDate} ، جدیدترین = {$maxDate}");
            $sample = Sales::select('document_date', 'product_code', 'product_name', 'customer_name', 'main_quantity', 'base_sales_amount')
                ->limit(3)->get();
            $rows = $sample->map(function ($r) {
                return [
                    is_object($r->document_date) ? $r->document_date->format('Y-m-d') : (string) $r->document_date,
                    $r->product_code,
                    $r->product_name,
                    $r->customer_name,
                    $r->main_quantity,
                    $r->base_sales_amount,
                ];
            })->toArray();
            $this->table(['document_date', 'product_code', 'product_name', 'customer_name', 'main_quantity', 'base_sales_amount'], $rows);
        } else {
            $this->warn('جدول فروش خالی است.');
        }

        if ($belzonaCount > 0) {
            $belzonaMin = BelzonaInventory::min('date');
            $belzonaMax = BelzonaInventory::max('date');
            $this->info("بلزونا: قدیمی‌ترین تاریخ = {$belzonaMin} ، جدیدترین = {$belzonaMax}");
            $sampleB = BelzonaInventory::select('product_name', 'date', 'input', 'output', 'balance', 'customer_name')->limit(3)->get();
            $this->table(['product_name', 'date', 'input', 'output', 'balance', 'customer_name'], $sampleB->map(function ($r) {
                return [
                    $r->product_name,
                    $r->date ? (is_object($r->date) ? $r->date->format('Y-m-d') : $r->date) : null,
                    $r->input, $r->output, $r->balance, $r->customer_name,
                ];
            })->toArray());
        }

        if ($settlementCount > 0) {
            $sampleS = InvoiceSettlement::select('document_number', 'document_date', 'customer_name', 'base_net_amount', 'paid_amount', 'balance_amount')->limit(3)->get();
            $this->info('نمونه تسویه:');
            $rowsS = $sampleS->map(function ($r) {
                return [
                    $r->document_number,
                    is_object($r->document_date) ? $r->document_date->format('Y-m-d') : (string) $r->document_date,
                    $r->customer_name,
                    $r->base_net_amount,
                    $r->paid_amount,
                    $r->balance_amount,
                ];
            })->toArray();
            $this->table(['document_number', 'document_date', 'customer_name', 'base_net_amount', 'paid_amount', 'balance_amount'], $rowsS);
        }

        $this->newLine();
        $this->info('اگر بازه پیش‌فرض (مثلاً ۹۰ یا ۱۸۰ روز) قدیمی‌تر از تاریخ‌های شماست، خروجی فیچرها خالی می‌شود. در این حالت از گزینه «همه» یا بازه بزرگ‌تر استفاده کنید.');
        return 0;
    }
}
