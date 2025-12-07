@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">ویرایش تراکنش</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">حسابداری</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">انبار</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.transactions.index') }}">گردش کالا</a></li>
                    <li class="breadcrumb-item active">ویرایش تراکنش</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="transaction-edit-form" method="POST" action="{{ route('inventory.transactions.update', $transaction->transaction_id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>کالا <span class="text-danger">*</span></label>
                                        <select class="form-control" name="inventory_id" id="inventory_id" required>
                                            <option value="">انتخاب کالا</option>
                                            @foreach($inventories as $inventory)
                                                <option value="{{ $inventory->inventory_id }}" 
                                                    {{ old('inventory_id', $transaction->inventory_id) == $inventory->inventory_id ? 'selected' : '' }}>
                                                    {{ $inventory->inventory_code }} - {{ $inventory->inventory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>نوع تراکنش <span class="text-danger">*</span></label>
                                        <select class="form-control" name="transaction_type" required>
                                            <option value="input" {{ old('transaction_type', $transaction->transaction_type) == 'input' ? 'selected' : '' }}>ورود</option>
                                            <option value="output" {{ old('transaction_type', $transaction->transaction_type) == 'output' ? 'selected' : '' }}>خروج</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>تاریخ تراکنش <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="transaction_date" 
                                               value="{{ old('transaction_date', $transaction->transaction_date) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>مقدار <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="quantity" 
                                               value="{{ old('quantity', $transaction->quantity) }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>فی (قیمت واحد)</label>
                                        <input type="number" class="form-control" name="unit_price" 
                                               value="{{ old('unit_price', $transaction->unit_price) }}" step="0.01" min="0" id="unit_price">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>مبلغ تمام شده</label>
                                        <input type="number" class="form-control" name="amount" 
                                               value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0" id="amount">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>شماره سند</label>
                                        <input type="text" class="form-control" name="document_number" 
                                               value="{{ old('document_number', $transaction->document_number) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>شماره سند مبنا</label>
                                        <input type="text" class="form-control" name="base_document_number" 
                                               value="{{ old('base_document_number', $transaction->base_document_number) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>انبار</label>
                                        <input type="text" class="form-control" name="warehouse" 
                                               value="{{ old('warehouse', $transaction->warehouse) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>توضیحات</label>
                                        <textarea class="form-control" name="notes" rows="2">{{ old('notes', $transaction->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-save"></i> ذخیره
                                        </button>
                                        <a href="{{ route('inventory.transactions.index') }}" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> انصراف
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script>
$(document).ready(function() {
    // Calculate amount automatically
    function calculateAmount() {
        var quantity = parseFloat($('input[name="quantity"]').val()) || 0;
        var unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
        var amount = quantity * unitPrice;
        if (amount > 0) {
            $('#amount').val(amount.toFixed(2));
        }
    }

    $('input[name="quantity"], input[name="unit_price"]').on('input', calculateAmount);
    
    // Form submission
    $('#transaction-edit-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('تراکنش با موفقیت به‌روزرسانی شد');
                    window.location.href = '{{ route('inventory.transactions.index') }}';
                } else {
                    alert('خطا: ' + (response.message || 'خطای نامشخص'));
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    alert('خطا: ' + response.message);
                } else {
                    alert('خطا در به‌روزرسانی تراکنش');
                }
            }
        });
    });
});
</script>
@endsection

