<?php

namespace App\Http\Middleware\InvoiceSettlements;

use Closure;
use App\Models\TableConfig;

class Index
{
    public function handle($request, Closure $next)
    {
        $this->setTableConfig();
        $this->setFrontend();

        return $next($request);
    }

    protected function setTableConfig()
    {
        $table = TableConfig::where('tableconfig_userid', auth()->id())
            ->where('tableconfig_table_name', 'invoice_settlements')
            ->first();

        if (!$table) {
            $table = new TableConfig();
            $table->tableconfig_userid = auth()->id();
            $table->tableconfig_table_name = 'invoice_settlements';
            $table->tableconfig_column_1 = 'displayed'; // document number
            $table->tableconfig_column_2 = 'displayed'; // document date
            $table->tableconfig_column_3 = 'displayed'; // customer name
            $table->tableconfig_column_4 = 'displayed'; // base net amount
            $table->tableconfig_column_5 = 'displayed'; // paid amount
            $table->tableconfig_column_6 = 'displayed'; // balance amount
            $table->tableconfig_column_7 = 'displayed'; // currency
            $table->tableconfig_column_8 = 'hidden';    // creator
            $table->tableconfig_column_9 = 'hidden';    // created_at
            $table->save();
        }

        config(['table' => $table]);
    }

    protected function setFrontend()
    {
        config([
            'visibility.list_page_actions_filter_button' => true,
            'visibility.list_page_actions_search' => true,
            'visibility.invoice_settlements_col_checkboxes' => false,
        ]);
    }
}

