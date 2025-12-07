@extends('layout.wrapper')

@section('content')
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">ایمپورت ورودهای انبار</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">حسابداری</a></li>
                    <li class="breadcrumb-item"><a href="/report/warehouse">گزارش انبار</a></li>
                    <li class="breadcrumb-item active">ایمپورت ورودهای انبار</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Page Content -->
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ایمپورت داده‌های ورود انبار</h5>
                        <p class="text-muted">
                            این بخش برای ایمپورت <strong>ورودهای قلمی/بچ</strong> کالاها استفاده می‌شود.
                            <br>
                            <strong>نکته مهم:</strong> قبل از ایمپورت ورودها، باید ابتدا کالاهای پایه از طریق 
                            <a href="/import/inventory">صفحه ایمپورت کالا</a> وارد شده باشند.
                        </p>
                        
                        <!-- Import Results -->
                        @if(session('import_results'))
                            @php $results = session('import_results'); @endphp
                            <div class="alert alert-{{ $results['success'] ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">نتایج ایمپورت</h6>
                                <p>{{ $results['message'] }}</p>
                                <hr>
                                <p class="mb-0">
                                    <strong>وارد شده:</strong> {{ $results['imported'] ?? 0 }} | 
                                    <strong>رد شده:</strong> {{ $results['skipped'] ?? 0 }}
                                </p>
                                @if(isset($results['failures']) && count($results['failures']) > 0)
                                    <hr>
                                    <details>
                                        <summary class="cursor-pointer">مشاهده خطاها ({{ count($results['failures']) }})</summary>
                                        <ul class="mt-2 mb-0">
                                            @foreach($results['failures'] as $failure)
                                                <li>
                                                    <strong>ردیف {{ $failure['row'] }}:</strong>
                                                    {{ implode(', ', $failure['errors']) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </details>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Import Form -->
                        <form id="inventory-entry-import-form" method="POST" action="/import/inventory-entry" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>کد کالا <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_code" 
                                               id="inventory_code" required placeholder="کد کالا را وارد کنید">
                                        <small class="form-text text-muted">
                                            کد کالایی که از فایل anbar.xlsx ایمپورت شده است
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
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

                            <!-- Sample Format -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>قالب نمونه فایل</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>تاريخ</th>
                                                    <th>سند</th>
                                                    <th>نوع</th>
                                                    <th>شماره سند مبنا</th>
                                                    <th>مقدار</th>
                                                    <th>في</th>
                                                    <th>مبلغ تمام شده</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1403/01/15</td>
                                                    <td>DOC-001</td>
                                                    <td>ورودی</td>
                                                    <td>BASE-001</td>
                                                    <td>100</td>
                                                    <td>50000</td>
                                                    <td>5000000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small class="text-muted">
                                        <strong>توضیحات:</strong>
                                        <ul class="mb-0">
                                            <li>فایل باید دقیقاً این 7 ستون را داشته باشد</li>
                                            <li><strong>تاريخ:</strong> تاریخ تراکنش (شمسی یا میلادی)</li>
                                            <li><strong>سند:</strong> شماره سند تراکنش</li>
                                            <li><strong>نوع:</strong> ورودی یا خروجی</li>
                                            <li><strong>شماره سند مبنا:</strong> شماره سند مرجع</li>
                                            <li><strong>مقدار:</strong> تعداد کالا</li>
                                            <li><strong>في:</strong> قیمت واحد</li>
                                            <li><strong>مبلغ تمام شده:</strong> مقدار × فی</li>
                                            <li class="text-danger"><strong>نکته مهم:</strong> این فایل برای یک کالا است. قبل از ایمپورت باید کد کالا را مشخص کنید.</li>
                                        </ul>
                                    </small>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti-upload"></i> شروع ایمپورت
                                    </button>
                                    <a href="/report/warehouse" class="btn btn-light">
                                        <i class="ti-arrow-right"></i> بازگشت به گزارش انبار
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Information Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">راهنمای استفاده</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>مراحل ایمپورت:</h6>
                                <ol>
                                    <li>ابتدا از <a href="/import/inventory">صفحه ایمپورت کالا</a> کالاهای پایه را وارد کنید</li>
                                    <li>سپس از این صفحه ورودهای قلمی/بچ را ایمپورت کنید</li>
                                    <li>سیستم به صورت خودکار موجودی کالاها را به‌روزرسانی می‌کند</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6>رابطه داده‌ها:</h6>
                                <ul>
                                    <li><strong>کالا (Inventory):</strong> اطلاعات پایه کالا (کد، نام، واحد)</li>
                                    <li><strong>ورود (Entry):</strong> هر قلم/بچ کالا با تاریخ ورود و انقضا</li>
                                    <li><strong>کلید ارتباط:</strong> <code>کد کالا (inventory_code)</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content -->
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

<script>
$(document).ready(function() {
    $('#inventory-entry-import-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        
        $submitBtn.prop('disabled', true).html('<i class="ti-reload"></i> در حال پردازش...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    NX.notification({
                        type: 'success',
                        message: response.message || 'ایمپورت با موفقیت انجام شد'
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    NX.notification({
                        type: 'error',
                        message: response.message || 'ایمپورت با خطا مواجه شد'
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'خطا در ارتباط با سرور';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                NX.notification({
                    type: 'error',
                    message: errorMessage
                });
            },
            complete: function() {
                $submitBtn.prop('disabled', false).html('<i class="ti-upload"></i> شروع ایمپورت');
            }
        });
    });
});
</script>
@endsection

