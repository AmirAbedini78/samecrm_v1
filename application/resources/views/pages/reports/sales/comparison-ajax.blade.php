<!--ajax content-->
@if(isset($report['range1']['rows']) && count($report['range1']['rows']) > 0)
    @foreach($report['range1']['rows'] as $row)
    <tr>
        <td>{{ $row->sales_id }}</td>
        <td>{{ $row->document_date_persian }}</td>
        <td>{{ $row->customer_name ?? '' }}</td>
        <td>{{ $row->product_name ?? '' }}</td>
        <td>{{ $row->main_quantity ?? 0 }}</td>
        <td>{{ number_format($row->base_sales_amount ?? 0) }}</td>
    </tr>
    @endforeach
@else
    <tr><td colspan="6" class="text-center">هیچ رکوردی یافت نشد</td></tr>
@endif
