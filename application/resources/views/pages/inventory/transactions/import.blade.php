@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">وارد کردن گردش کالا از اکسل</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">حسابداری</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">انبار</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.transactions.index') }}">گردش کالا</a></li>
                    <li class="breadcrumb-item active">وارد کردن از اکسل</li>
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
                        <h5 class="card-title">وارد کردن گردش کالا از فایل اکسل</h5>
                        <p class="text-muted">فایل اکسل باید شامل ستون‌های زیر باشد: تاریخ، سند، نوع، شماره سند مبنا، مقدار، فی، مبلغ تمام شده</p>
                        
                        <!-- Import Form -->
                        <form id="transaction-import-form" method="POST" action="{{ route('inventory.transactions.import') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>انتخاب فایل <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="attachments[]" 
                                               accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            فرمت‌های پشتیبانی شده: XLSX, XLS, CSV (حداکثر حجم: 10MB)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>کالا (اختیاری - برای import تراکنش‌های یک کالا)</label>
                                        <select class="form-control" name="inventory_id">
                                            <option value="">همه کالاها</option>
                                            @foreach($inventories as $inventory)
                                                <option value="{{ $inventory->inventory_id }}">
                                                    {{ $inventory->inventory_code }} - {{ $inventory->inventory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            اگر کالا انتخاب شود، تمام تراکنش‌های import شده به این کالا اختصاص داده می‌شوند
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Format -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>فرمت نمونه فایل اکسل:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>تاریخ</th>
                                                    <th>سند</th>
                                                    <th>نوع</th>
                                                    <th>شماره سند مبنا</th>
                                                    <th>مقدار</th>
                                                    <th>فی</th>
                                                    <th>مبلغ تمام شده</th>
                                                    <th>کد کالا (اختیاری)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1403/01/15</td>
                                                    <td>DOC001</td>
                                                    <td>ورود</td>
                                                    <td>BASE001</td>
                                                    <td>100</td>
                                                    <td>10000</td>
                                                    <td>1000000</td>
                                                    <td>INV001</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-upload"></i> وارد کردن
                                        </button>
                                        <a href="{{ route('inventory.transactions.index') }}" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> بازگشت
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
    $('#transaction-import-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var fileInput = $('input[type="file"]')[0];
        
        if (fileInput.files.length === 0) {
            alert('لطفا فایلی انتخاب کنید');
            return;
        }
        
        $('button[type="submit"]').prop('disabled', true).html('<i class="ti-spinner"></i> در حال آپلود...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('وارد کردن موفق: ' + response.message + '\nوارد شده: ' + response.imported + ' | رد شده: ' + response.skipped);
                    window.location.href = '{{ route('inventory.transactions.index') }}';
                } else {
                    alert('خطا در وارد کردن: ' + response.message);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    alert('خطا: ' + response.message);
                } else {
                    alert('خطا در وارد کردن فایل');
                }
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="ti-upload"></i> وارد کردن');
            }
        });
    });
});
</script>
@endsection

