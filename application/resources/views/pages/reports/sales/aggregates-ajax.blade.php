<!--ajax content-->
<tr>
    <td colspan="3" class="text-center">
        <strong>تعداد رکورد: {{ $report['count'] ?? 0 }}</strong>
    </td>
</tr>
<tr>
    <td colspan="3" class="text-center">
        <strong>مجموع مبلغ فروش: {{ number_format($report['total_sales_amount'] ?? 0) }}</strong>
    </td>
</tr>
<tr>
    <td colspan="3" class="text-center">
        <strong>میانگین مبلغ فروش: {{ number_format($report['average_sales_amount'] ?? 0) }}</strong>
    </td>
</tr>
