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
                <h3 class="page-title">ایمپورت انبار بلزونا</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/belzona-inventory">انبار بلزونا</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.import')) }}</li>
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
                        <h5 class="card-title">ایمپورت اطلاعات انبار بلزونا</h5>
                        <p class="text-muted">فایل اکسل/CSV را مطابق قالب نمونه بارگذاری کنید.</p>

                        <!-- Import Results -->
                        @if(session('import_results'))
                            @php $results = session('import_results'); @endphp
                            <div class="alert alert-{{ $results['success'] ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">{{ cleanLang(__('lang.import_results')) }}</h6>
                                <p>{{ $results['message'] }}</p>
                                <hr>
                                <p class="mb-0">
                                    <strong>{{ cleanLang(__('lang.imported')) }}:</strong> {{ $results['imported'] }} |
                                    <strong>{{ cleanLang(__('lang.skipped')) }}:</strong> {{ $results['skipped'] }}
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Import Form -->
                        <form id="belzona-inventory-import-form" method="POST" action="/import/belzona-inventory" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.select_file')) }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="attachments[]"
                                               accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            {{ cleanLang(__('lang.supported_formats')) }}: XLSX, XLS, CSV ({{ cleanLang(__('lang.max_file_size')) }}: 10MB)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Format -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>{{ cleanLang(__('lang.sample_format')) }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>نام محصول</th>
                                                    <th>تاریخ</th>
                                                    <th>ورودی</th>
                                                    <th>خروجی</th>
                                                    <th>مانده</th>
                                                    <th>شماره فاکتور</th>
                                                    <th>نام مشتری</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Belzona 1111</td>
                                                    <td>2026-01-01</td>
                                                    <td>10</td>
                                                    <td>2</td>
                                                    <td>8</td>
                                                    <td>INV-1000</td>
                                                    <td>مشتری نمونه</td>
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
                                            <i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}
                                        </button>
                                        <a href="/belzona-inventory" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> بازگشت به انبار بلزونا
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="my-4">
                        <h5 class="card-title mt-4">ایمپورت مدت ماندگاری (شلف لایف)</h5>
                        <p class="text-muted">فایل اکسل مدت ماندگاری محصولات بلزونا را بارگذاری کنید. در هر شیت جدولی با ستون‌های «کد/نام محصول» و «مدت ماندگاری (سال)» یا مشابه داشته باشید. تطابق با نام شیت انبار انجام می‌شود.</p>
                        <form id="belzona-shelf-life-import-form" method="POST" action="/import/belzona-inventory/shelf-life" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>فایل اکسل مدت ماندگاری <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="shelf_life_file" accept=".xlsx,.xls" required>
                                        <small class="form-text text-muted">فرمت: XLSX یا XLS (حداکثر 10 مگابایت)</small>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-success" id="belzona-shelf-life-submit">
                                            <i class="ti-upload"></i> بارگذاری شلف لایف
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="belzona-shelf-life-result" class="mt-2"></div>
                        </form>
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
@endsection

@section('footerjs')
<script>
$(document).ready(function() {
    $('#belzona-inventory-import-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var fileInput = $('input[type="file"]')[0];

        if (fileInput.files.length === 0) {
            alert('Please select a file');
            return;
        }

        formData.append('attachments[]', fileInput.files[0]);

        $('button[type="submit"]').prop('disabled', true).html('<i class="ti-spinner"></i> Uploading...');

        $.ajax({
            url: '/import/belzona-inventory',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Import successful: ' + response.message);
                    location.reload();
                } else {
                    alert('Import failed: ' + response.message);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    alert('Import failed: ' + response.message);
                } else {
                    alert('Import failed: Please try again');
                }
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}');
            }
        });
    });

    $('#belzona-shelf-life-import-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var fileInput = $('input[name="shelf_life_file"]')[0];
        if (fileInput.files.length === 0) {
            alert('لطفاً یک فایل انتخاب کنید.');
            return;
        }
        var $btn = $('#belzona-shelf-life-submit');
        var $result = $('#belzona-shelf-life-result');
        $btn.prop('disabled', true).html('<i class="ti-spinner"></i> در حال بارگذاری...');
        $result.html('');
        $.ajax({
            url: '/import/belzona-inventory/shelf-life',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $result.html('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    $result.html('<div class="alert alert-danger">' + (response.message || 'خطا') + '</div>');
                }
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'خطا در ارتباط با سرور';
                $result.html('<div class="alert alert-danger">' + msg + '</div>');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="ti-upload"></i> بارگذاری شلف لایف');
            }
        });
    });
});
</script>
@endsection

