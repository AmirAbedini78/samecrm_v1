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

                        <!-- Tabs: Excel/CSV | PDF -->
                        <ul class="nav nav-tabs mb-4" id="importMethodTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-excel" data-bs-toggle="tab" data-bs-target="#panel-excel" type="button" role="tab">Excel / CSV</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-pdf" data-bs-toggle="tab" data-bs-target="#panel-pdf" type="button" role="tab">PDF (تحلیل خودکار)</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="importMethodTabContent">
                            <!-- Excel/CSV Panel -->
                            <div class="tab-pane fade show active" id="panel-excel" role="tabpanel">
                        <form id="inventory-entry-import-form" method="POST" action="/import/inventory-entry" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>کد کالا <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_code" 
                                               id="inventory_code" placeholder="کد کالا را وارد کنید">
                                        <small class="form-text text-muted">
                                            کد کالایی که از فایل anbar.xlsx ایمپورت شده است
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>انتخاب فایل <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="attachments[]" 
                                               accept=".xlsx,.xls,.csv" id="file_excel">
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
                                    <button type="submit" class="btn btn-primary" id="btn-submit-excel">
                                        <i class="ti-upload"></i> شروع ایمپورت
                                    </button>
                                    <a href="/report/warehouse" class="btn btn-light">
                                        <i class="ti-arrow-right"></i> بازگشت به گزارش انبار
                                    </a>
                                </div>
                            </div>
                        </form>
                            </div>
                            <!-- PDF Panel -->
                            <div class="tab-pane fade" id="panel-pdf" role="tabpanel">
                                @if(empty($page['enable_python_ml']))
                                <div class="alert alert-warning mb-3">
                                    <strong>غیرفعال در این سرور.</strong> استخراج خودکار از PDF روی این هاست در دسترس نیست. از تب Excel/CSV برای ایمپورت استفاده کنید، یا روی سروری که پایتون نصب است (مثلاً لاراگون یا VPS) این قابلیت فعال می‌شود.
                                </div>
                                @else
                                <p class="text-muted mb-3">
                                    هر گونه فایل <strong>PDF گردش کالا</strong> را آپلود کنید. سیستم به صورت خودکار جدول و در صورت امکان <strong>کد کالا</strong> را تشخیص می‌دهد و ورودها را ایمپورت می‌کند.
                                </p>
                                <form id="inventory-entry-pdf-form" method="POST" action="/import/inventory-entry/pdf" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>فایل PDF <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="attachments[]" 
                                                       accept=".pdf" id="file_pdf" required>
                                                <small class="form-text text-muted">حداکثر ۲۰ مگابایت</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>کد کالا (اختیاری)</label>
                                                <input type="text" class="form-control" name="inventory_code" 
                                                       id="inventory_code_pdf" placeholder="در صورت خالی بودن، از داخل PDF تشخیص داده می‌شود">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary" id="btn-submit-pdf">
                                                <i class="ti-file"></i> تحلیل و ایمپورت PDF
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
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
    function submitExcel(e) {
        e.preventDefault();
        var $form = $('#inventory-entry-import-form');
        if (!$form[0].checkValidity()) {
            $form[0].reportValidity();
            return;
        }
        if (!$('#file_excel').val()) {
            if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'لطفاً فایل Excel/CSV انتخاب کنید' });
            return;
        }
        if (!$('#inventory_code').val().trim()) {
            if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'کد کالا را وارد کنید' });
            return;
        }
        var formData = new FormData($form[0]);
        var $submitBtn = $('#btn-submit-excel');
        $submitBtn.prop('disabled', true).html('<i class="ti-reload"></i> در حال پردازش...');
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    if (typeof NX !== 'undefined') NX.notification({ type: 'success', message: response.message || 'ایمپورت با موفقیت انجام شد' });
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: response.message || 'ایمپورت با خطا مواجه شد' });
                }
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'خطا در ارتباط با سرور';
                if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: msg });
            },
            complete: function() {
                $submitBtn.prop('disabled', false).html('<i class="ti-upload"></i> شروع ایمپورت');
            }
        });
    }

    $('#inventory-entry-import-form').on('submit', submitExcel);

    $('#inventory-entry-pdf-form').on('submit', function(e) {
        e.preventDefault();
        if (!$('#file_pdf').val()) {
            if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'لطفاً یک فایل PDF انتخاب کنید' });
            return;
        }
        var formData = new FormData(this);
        var $submitBtn = $('#btn-submit-pdf');
        $submitBtn.prop('disabled', true).html('<i class="ti-reload"></i> در حال تحلیل PDF...');
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    if (typeof NX !== 'undefined') NX.notification({ type: 'success', message: response.message || 'ایمپورت PDF با موفقیت انجام شد' });
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: response.message || 'خطا در پردازش PDF' });
                }
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'خطا در ارتباط با سرور';
                if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: msg });
            },
            complete: function() {
                $submitBtn.prop('disabled', false).html('<i class="ti-file"></i> تحلیل و ایمپورت PDF');
            }
        });
    });
});
</script>
@endsection

