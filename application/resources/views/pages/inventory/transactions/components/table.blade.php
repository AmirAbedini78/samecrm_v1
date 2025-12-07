<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ردیف</th>
                <th>کالا</th>
                <th>نوع</th>
                <th>تاریخ</th>
                <th>مقدار</th>
                <th>فی</th>
                <th>مبلغ</th>
                <th>شماره سند</th>
                <th>شماره سند مبنا</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($transaction->inventory)
                            <strong>{{ $transaction->inventory->inventory_code }}</strong><br>
                            <small>{{ $transaction->inventory->inventory_name }}</small>
                        @else
                            <span class="text-muted">نامشخص</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->transaction_type == 'input')
                            <span class="badge bg-success">ورود</span>
                        @else
                            <span class="badge bg-danger">خروج</span>
                        @endif
                    </td>
                    <td>{{ $transaction->formatted_transaction_date ?? $transaction->transaction_date }}</td>
                    <td>{{ number_format($transaction->quantity, 2) }}</td>
                    <td>{{ $transaction->unit_price ? number_format($transaction->unit_price, 2) : '-' }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->document_number ?? '-' }}</td>
                    <td>{{ $transaction->base_document_number ?? '-' }}</td>
                    <td>
                        <a href="{{ route('inventory.transactions.edit', $transaction->transaction_id) }}" 
                           class="btn btn-sm btn-info" title="ویرایش">
                            <i class="ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger delete-transaction" 
                                data-id="{{ $transaction->transaction_id }}" title="حذف">
                            <i class="ti-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">هیچ تراکنشی یافت نشد</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($transactions, 'links'))
    <div class="mt-3">
        {{ $transactions->links() }}
    </div>
@endif

<script>
$(document).ready(function() {
    $('.delete-transaction').on('click', function() {
        if (!confirm('آیا از حذف این تراکنش اطمینان دارید؟')) {
            return;
        }
        
        var transactionId = $(this).data('id');
        var row = $(this).closest('tr');
        
        $.ajax({
            url: '/inventory/transactions/' + transactionId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(function() {
                        $(this).remove();
                    });
                    alert('تراکنش با موفقیت حذف شد');
                } else {
                    alert('خطا: ' + (response.message || 'خطای نامشخص'));
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    alert('خطا: ' + response.message);
                } else {
                    alert('خطا در حذف تراکنش');
                }
            }
        });
    });
});
</script>

