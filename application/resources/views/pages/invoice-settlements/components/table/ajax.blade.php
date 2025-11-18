@foreach($settlements as $item)
<tr id="invoice_settlement_{{ $item->invoice_settlement_id }}">
    <td class="invoice_settlements_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
        {{ $item->document_number ?? '---' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
        {{ $item->document_date ?? '---' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
        {{ $item->customer_name ?? '---' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
        {{ formatCurrency($item->base_net_amount, $item->currency ?? 'IRR') }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
        {{ formatCurrency($item->paid_amount, $item->currency ?? 'IRR') }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
        {{ formatCurrency($item->balance_amount, $item->currency ?? 'IRR') }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
        {{ $item->currency ?? 'IRR' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
        {{ optional($item->creator)->first_name }} {{ optional($item->creator)->last_name }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
        {{ runtimeDate($item->created_at) }}
    </td>
    <td class="invoice_settlements_col_action actions_column text-muted">—</td>
</tr>
@endforeach

