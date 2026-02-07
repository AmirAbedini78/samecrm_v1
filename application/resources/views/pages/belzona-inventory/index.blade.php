@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ _url('/') }}">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ _url('/accounting') }}">{{ cleanLang(__('lang.accounting')) }}</a></li>
                        <li class="breadcrumb-item active">انبار بلزونا</li>
                    </ol>
                </div>
                <h4 class="page-title">انبار بلزونا</h4>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-0">تعداد محصولات (شیت‌ها)</p>
                    <h4 class="my-1">{{ number_format($stats['distinct_products'] ?? 0) }}</h4>
                    <small class="text-muted d-block">بر اساس نام شیت</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-0">تعداد گردش‌ها</p>
                    <h4 class="my-1">{{ number_format($stats['total_items'] ?? 0) }}</h4>
                    <small class="text-muted d-block">کل ردیف‌های ثبت‌شده</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-0">جمع ورودی</p>
                    <h4 class="my-1">{{ number_format($stats['total_input'] ?? 0) }}</h4>
                    <small class="text-muted d-block">مجموع ستون ورودی</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted mb-0">جمع خروجی</p>
                    <h4 class="my-1">{{ number_format($stats['total_output'] ?? 0) }}</h4>
                    <small class="text-muted d-block">مجموع ستون خروجی</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-light border d-flex justify-content-between align-items-center">
                <div>
                    <strong>آخرین ایمپورت/ثبت:</strong>
                    <span>{{ $stats['last_import_at'] ?? '-' }}</span>
                    <span class="mx-2">|</span>
                    <strong>تعداد مشتری یکتا:</strong>
                    <span>{{ number_format($stats['distinct_customers'] ?? 0) }}</span>
                </div>
                <div class="text-muted">
                    نکته: تاریخ اصلی فایل در ستون «تاریخ» به صورت متنی ذخیره می‌شود و در صورت امکان به تاریخ میلادی تبدیل می‌شود.
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Manager - Inbound Batches (All Products) -->
    <div class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="ti-import me-2"></i>
                            لیست ورودی‌ها (پارت‌ها)
                        </h5>
                        <small class="text-muted">نمایش پیش‌فرض بر اساس تاریخ ورود (جدیدترین)</small>
                    </div>
                    <div class="text-muted small">
                        روی «مشاهده خروجی‌ها» کلیک کنید.
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4">
                            <label class="form-label mb-1">فیلتر محصول (نام شیت)</label>
                            <select id="belzona-inbounds-filter-sheet" class="form-control">
                                <option value="">همه محصولات</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label mb-1">از تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="belzona-inbounds-date-from" class="form-control persian-datepicker" autocomplete="off" placeholder="مثلاً 1403/1/1">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersianDatePicker('belzona-inbounds-date-from')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label mb-1">تا تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="belzona-inbounds-date-to" class="form-control persian-datepicker" autocomplete="off" placeholder="مثلاً 1403/12/29">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersianDatePicker('belzona-inbounds-date-to')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 text-end">
                            <button type="button" id="belzona-inbounds-refresh" class="btn btn-primary">
                                <i class="ti-reload"></i> بروزرسانی
                            </button>
                            <button type="button" id="belzona-inbounds-clear" class="btn btn-outline-secondary ms-2">
                                <i class="ti-eraser"></i> پاک کردن
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3 g-3">
                        <div class="col-md-4">
                            <div class="card border mb-0">
                                <div class="card-body">
                                    <div class="text-muted">جمع ورودی‌ها (بازه انتخابی)</div>
                                    <div class="h4 mb-0" id="belzona-inbounds-sum">-</div>
                                    <small class="text-muted" id="belzona-inbounds-count">-</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card border mb-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted">آخرین پارت ورود</div>
                                        <div class="h5 mb-0" id="belzona-latest-inbound-title">-</div>
                                        <small class="text-muted" id="belzona-latest-inbound-meta">-</small>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-success" id="belzona-latest-inbound-open" disabled>
                                            <i class="ti-eye"></i> مشاهده خروجی‌ها
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table id="belzona-inbounds-table" class="table table-striped table-hover table-bordered w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>تاریخ ورود</th>
                                    <th>محصول (شیت)</th>
                                    <th>عنوان/توضیح پارت</th>
                                    <th>تعداد ورود</th>
                                    <th>جمع خروجی</th>
                                    <th>مانده پارت</th>
                                    <th>تعداد خروجی‌ها</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Product Lookup -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ti-search me-2"></i>
                        جستجوی سریع گردش محصول
                    </h5>
                    <small class="text-muted">محصول = نام شیت (شامل وزن)</small>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5">
                            <label class="form-label">محصول</label>
                            <select id="belzona-product-select" class="form-control">
                                <option value="">انتخاب محصول...</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">از تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="belzona-date-from" class="form-control persian-datepicker" autocomplete="off" placeholder="مثلاً 1403/1/1">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersianDatePicker('belzona-date-from')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">تا تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="belzona-date-to" class="form-control persian-datepicker" autocomplete="off" placeholder="مثلاً 1403/12/29">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersianDatePicker('belzona-date-to')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 text-end">
                            <button type="button" id="belzona-refresh-summary" class="btn btn-primary">
                                <i class="ti-bar-chart"></i> نمایش خلاصه
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4 g-3">
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">جمع ورودی (همه پارت‌ها)</div>
                                    <div class="h4 mb-0" id="belzona-summary-input">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">جمع خروجی (همه پارت‌ها)</div>
                                    <div class="h4 mb-0" id="belzona-summary-output">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">مانده کل (ورودی-خروجی)</div>
                                    <div class="h4 mb-0" id="belzona-summary-net">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">آخرین پارت ورود</div>
                                    <div class="h5 mb-0" id="belzona-summary-balance">-</div>
                                    <small class="text-muted" id="belzona-summary-lastdate">-</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inbound batches list -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>پارت‌های ورود</strong>
                                    <small class="text-muted ms-2" id="belzona-batches-count"></small>
                                </div>
                                <div class="text-muted small">
                                    راهنما: روی هر پارت کلیک کنید تا خروجی‌های همان پارت نمایش داده شود.
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 55px;">#</th>
                                            <th>عنوان/توضیح پارت</th>
                                            <th style="width: 140px;">تاریخ ورود</th>
                                            <th style="width: 120px;">تعداد ورود</th>
                                            <th style="width: 120px;">جمع خروجی</th>
                                            <th style="width: 120px;">مانده پارت</th>
                                            <th style="width: 110px;">تعداد خروجی‌ها</th>
                                        </tr>
                                    </thead>
                                    <tbody id="belzona-inbound-batches">
                                        <tr><td colspan="7" class="text-muted">یک محصول انتخاب کنید.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>خروجی‌های پارت انتخاب‌شده</strong>
                                    <small class="text-muted ms-2" id="belzona-selected-batch-label"></small>
                                </div>
                                <div class="text-muted small" id="belzona-selected-batch-meta"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>خروجی</th>
                                            <th>مانده</th>
                                            <th>فاکتور</th>
                                            <th>مشتری</th>
                                            <th>توضیحات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="belzona-summary-transactions">
                                        <tr><td colspan="6" class="text-muted">یک پارت انتخاب کنید.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Page Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if(config('visibility.list_page_actions_search'))
                            <div class="list-page-actions-search">
                                <input type="text" name="search_query" id="search_query" class="form-control"
                                       placeholder="جستجو در انبار بلزونا"
                                       value="{{ request('search_query') }}">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ _url('/import/belzona-inventory') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> ایمپورت انبار بلزونا
                            </a>
                            <a href="{{ _url('/belzona-inventory/create') }}" class="btn btn-primary">
                                <i class="ti-plus"></i> افزودن ردیف
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-3">
                            <input class="form-control belzona-filter" data-filter="sheet_name" placeholder="فیلتر محصول (نام شیت)">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="product_weight_raw" placeholder="فیلتر وزن (مثلاً 1Kg)">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="date_raw" placeholder="فیلتر تاریخ (متنی)">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="invoice_number" placeholder="فیلتر فاکتور">
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control belzona-filter" data-filter="customer_name" placeholder="فیلتر مشتری">
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="input_min" placeholder="ورودی از">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="input_max" placeholder="ورودی تا">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="output_min" placeholder="خروجی از">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="output_max" placeholder="خروجی تا">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="balance_min" placeholder="مانده از">
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control belzona-filter" data-filter="balance_max" placeholder="مانده تا">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-outline-secondary" id="belzona-clear-filters">
                            <i class="ti-eraser"></i> پاک کردن فیلترها
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable -->
    <div class="row">
        <div class="col-12">
            <div class="card" id="belzona-inventory-table-wrapper">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti-package me-2"></i>
                        لیست انبار بلزونا
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="belzona-inventory-table" class="table table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>محصول (نام شیت)</th>
                                    <th>وزن</th>
                                    <th>تاریخ</th>
                                    <th>ورودی</th>
                                    <th>خروجی</th>
                                    <th>مانده</th>
                                    <th>شماره فاکتور</th>
                                    <th>نام مشتری</th>
                                    <th>توضیحات</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script src="{{ url('public/js/core/datatables-belzona-inventory.js') }}"></script>
<script>
// Persian date picker (copied from sales comparison filter style)
function showPersianDatePicker(inputId) {
    var currentValue = $('#' + inputId).val();
    var year = 1403, month = 1, day = 1;

    if (currentValue && /^\d{4}\/\d{1,2}\/\d{1,2}$/.test(currentValue)) {
        var parts = currentValue.split('/');
        year = parseInt(parts[0]);
        month = parseInt(parts[1]);
        day = parseInt(parts[2]);
    }

    var persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر',
        'مرداد', 'شهریور', 'مهر', 'آبان',
        'آذر', 'دی', 'بهمن', 'اسفند'
    ];

    var yearOptions = '';
    for (var i = 1398; i <= 1410; i++) {
        yearOptions += '<option value="' + i + '"' + (i === year ? ' selected' : '') + '>' + i + '</option>';
    }

    var monthOptions = '';
    for (var m = 1; m <= 12; m++) {
        monthOptions += '<option value="' + m + '"' + (m === month ? ' selected' : '') + '>' + persianMonths[m-1] + '</option>';
    }

    var dayOptions = '';
    var daysInMonth = month <= 6 ? 31 : 30;
    for (var d = 1; d <= daysInMonth; d++) {
        dayOptions += '<option value="' + d + '"' + (d === day ? ' selected' : '') + '>' + d + '</option>';
    }

    var dialog = `
        <div id="datePickerDialog" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
             background: white; border: 2px solid #ccc; border-radius: 8px; padding: 20px; z-index: 1049;
             box-shadow: 0 4px 8px rgba(0,0,0,0.3); min-width: 320px;">
            <h5 style="margin-bottom: 15px;">انتخاب تاریخ شمسی</h5>
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px;">سال:</label>
                    <select id="picker-year" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        ${yearOptions}
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px;">ماه:</label>
                    <select id="picker-month" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        ${monthOptions}
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px;">روز:</label>
                    <select id="picker-day" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        ${dayOptions}
                    </select>
                </div>
            </div>
            <div style="text-align: center;">
                <button onclick="confirmDate('${inputId}')" style="background: #007bff; color: white; border: none;
                        padding: 8px 16px; border-radius: 4px; margin-right: 10px; cursor: pointer;">تأیید</button>
                <button onclick="cancelDate()" style="background: #6c757d; color: white; border: none;
                        padding: 8px 16px; border-radius: 4px; cursor: pointer;">لغو</button>
            </div>
        </div>
        <div id="datePickerOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
             background: rgba(0,0,0,0.5); z-index: 1048;"></div>
    `;

    $('#datePickerDialog, #datePickerOverlay').remove();
    $('body').append(dialog);

    $('#picker-month').on('change', function() {
        var selectedMonth = parseInt($(this).val());
        var dim = selectedMonth <= 6 ? 31 : 30;
        var daySelect = $('#picker-day');
        var currentDay = parseInt(daySelect.val());

        daySelect.empty();
        for (var i = 1; i <= dim; i++) {
            daySelect.append('<option value="' + i + '"' + (i === Math.min(currentDay, dim) ? ' selected' : '') + '>' + i + '</option>');
        }
    });
}

function confirmDate(inputId) {
    var year = $('#picker-year').val();
    var month = $('#picker-month').val();
    var day = $('#picker-day').val();
    var formattedDate = year + '/' + month + '/' + day;
    $('#' + inputId).val(formattedDate).trigger('change');
    $('#datePickerDialog, #datePickerOverlay').remove();
}

function cancelDate() {
    $('#datePickerDialog, #datePickerOverlay').remove();
}

$(document).ready(function() {
    var baseUrl = (window.NX && NX.site_url) ? String(NX.site_url).replace(/\/$/, '') : '';
    function nxUrl(path) {
        path = String(path || '').replace(/^\//, '');
        return baseUrl ? (baseUrl + '/' + path) : ('/' + path);
    }

    function fmtNumber(v) {
        try {
            return new Intl.NumberFormat('fa-IR').format(parseFloat(v || 0));
        } catch (e) {
            return v;
        }
    }

    // Fix stuck/double Bootstrap backdrops (prevents "page stays dark" bugs)
    function nxFixModalBackdrops() {
        // if no visible modals, remove all backdrops and unlock body
        if ($('.modal.show').length === 0) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            return;
        }
        // if there are multiple backdrops, keep the last one only
        var $backs = $('.modal-backdrop');
        if ($backs.length > 1) {
            $backs.slice(0, -1).remove();
        }
    }

    // Modal helpers (outbounds of a specific inbound)
    // Use the system built-in common modal to avoid theme/z-index conflicts.
    function openOutboundsModal(sheetName, inboundRowNumber) {
        if (!sheetName || !inboundRowNumber) return;

        // ensure any persian datepicker overlay is closed (it can block clicks)
        $('#datePickerDialog, #datePickerOverlay').remove();

        // prepare common modal
        $('#commonModalContainer').addClass('modal-xl');
        $('#commonModalTitle').html(
            'خروجی‌های پارت' +
            '<div class="text-muted" style="font-size:12px;margin-top:6px;">' +
            'محصول: <strong>' + sheetName + '</strong>' +
            ' | ردیف ورود: <strong>' + inboundRowNumber + '</strong>' +
            '</div>'
        );
        $('#commonModalBody').html(
            '<div class="alert alert-light border mb-3 text-muted">در حال بارگذاری...</div>' +
            '<div class="table-responsive">' +
            '<table class="table table-sm table-striped table-bordered mb-0">' +
            '<thead class="table-light"><tr>' +
            '<th>تاریخ</th><th>خروجی</th><th>مانده</th><th>فاکتور</th><th>مشتری</th><th>توضیحات</th>' +
            '</tr></thead>' +
            '<tbody id="belzona-commonmodal-tbody">' +
            '<tr><td colspan="6" class="text-muted">در حال بارگذاری...</td></tr>' +
            '</tbody></table></div>'
        );
        $('#commonModalFooter').hide();
        $('#commonModal').modal('show');
        // sometimes Bootstrap leaves extra backdrops behind when showing repeatedly
        setTimeout(nxFixModalBackdrops, 0);

        $.get(nxUrl('belzona-inventory'), {
            action: 'batch_outbounds',
            sheet_name: sheetName,
            inbound_row_number: inboundRowNumber,
            filter_date_from: $('#belzona-inbounds-date-from').val(),
            filter_date_to: $('#belzona-inbounds-date-to').val()
        }, function(res) {
            if (!res || !res.success) return;
            var d = res.data || {};
            var inbound = d.inbound || {};

            var meta =
                'ورود: ' + fmtNumber(inbound.input || 0) +
                ' | خروجی: ' + fmtNumber(inbound.out_total || 0) +
                ' | مانده: ' + fmtNumber(inbound.remaining || 0) +
                (inbound.date_raw ? (' | تاریخ ورود: ' + inbound.date_raw) : '');

            $('#commonModalBody .alert').html(
                '<strong>' + (inbound.label ? inbound.label : 'پارت ورود') + '</strong>' +
                '<div class="text-muted" style="margin-top:6px;">' + meta + '</div>'
            );

            var rows = d.outbounds || [];
            if (!rows.length) {
                $('#belzona-commonmodal-tbody').html('<tr><td colspan="6" class="text-muted">خروجی‌ای یافت نشد.</td></tr>');
                return;
            }

            var html = '';
            rows.forEach(function(r) {
                html += '<tr>' +
                    '<td>' + (r.date_raw || '') + '</td>' +
                    '<td>' + fmtNumber(r.output || 0) + '</td>' +
                    '<td>' + fmtNumber(r.balance || 0) + '</td>' +
                    '<td>' + (r.invoice_number || '') + '</td>' +
                    '<td>' + (r.customer_name || '') + '</td>' +
                    '<td>' + (r.notes || '') + '</td>' +
                    '</tr>';
            });
            $('#belzona-commonmodal-tbody').html(html);
        });
    }

    // cleanup common modal sizing after close (only if we opened it as xl)
    $(document).on('hidden.bs.modal', '#commonModal', function () {
        $('#commonModalContainer').removeClass('modal-xl');
        $('#commonModalFooter').show();
        nxFixModalBackdrops();
    });

    // also fix after show (handles double backdrop edge cases)
    $(document).on('shown.bs.modal', '#commonModal', function () {
        nxFixModalBackdrops();
    });

    // Inbounds table (all products)
    var inboundsTable = null;
    function initInboundsTable() {
        if (!$('#belzona-inbounds-table').length) return;

        if (inboundsTable) {
            inboundsTable.destroy();
            $('#belzona-inbounds-table tbody').empty();
        }

        inboundsTable = $('#belzona-inbounds-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            order: [[0, 'desc']],
            ajax: {
                url: nxUrl('belzona-inventory'),
                type: 'GET',
                data: function(d) {
                    d.action = 'inbound_datatables';
                    d.sheet_name = $('#belzona-inbounds-filter-sheet').val();
                    d.filter_date_from = $('#belzona-inbounds-date-from').val();
                    d.filter_date_to = $('#belzona-inbounds-date-to').val();
                },
                error: function(xhr) {
                    console.error('Belzona inbounds datatable ajax error', xhr.status, xhr.responseText);
                }
            },
            columns: [
                { data: 'date_raw', name: 'date_raw' },
                { data: 'sheet_name', name: 'sheet_name' },
                { data: 'inbound_label', name: 'inbound_label' },
                { data: 'input', name: 'input', render: function(d){ return fmtNumber(d); } },
                { data: 'out_total', name: 'out_total', orderable: false, render: function(d){ return fmtNumber(d); } },
                { data: 'remaining', name: 'remaining', orderable: false, render: function(d){ return fmtNumber(d); } },
                { data: 'out_count', name: 'out_count', orderable: false, render: function(d){ return fmtNumber(d); } },
                { data: null, orderable: false, searchable: false, render: function(_, __, row){
                    return '<button type="button" class="btn btn-sm btn-outline-primary belzona-open-outbounds" ' +
                        'data-sheet="' + (row.sheet_name || '') + '" data-row="' + (row.inbound_row_number || '') + '">' +
                        '<i class="ti-eye"></i> مشاهده خروجی‌ها</button>';
                }}
            ],
            language: { url: nxUrl('public/js/datatables-persian.json') },
            responsive: true
        });
    }

    function refreshInboundSummary() {
        $.get(nxUrl('belzona-inventory'), {
            action: 'inbound_summary',
            sheet_name: $('#belzona-inbounds-filter-sheet').val(),
            filter_date_from: $('#belzona-inbounds-date-from').val(),
            filter_date_to: $('#belzona-inbounds-date-to').val()
        }, function(res){
            if (!res || !res.success) return;
            var d = res.data || {};
            $('#belzona-inbounds-sum').text(fmtNumber(d.inbound_sum || 0));
            $('#belzona-inbounds-count').text((d.inbound_count || 0) + ' پارت');

            var latest = d.latest;
            if (latest) {
                $('#belzona-latest-inbound-title').text(latest.sheet_name + ' - ' + (latest.label || 'پارت ورود'));
                $('#belzona-latest-inbound-meta').text('تاریخ: ' + (latest.date_raw || '-') + ' | ورود: ' + fmtNumber(latest.input || 0));
                $('#belzona-latest-inbound-open')
                    .prop('disabled', false)
                    .data('sheet', latest.sheet_name)
                    .data('row', latest.inbound_row_number);
            } else {
                $('#belzona-latest-inbound-title').text('-');
                $('#belzona-latest-inbound-meta').text('-');
                $('#belzona-latest-inbound-open').prop('disabled', true);
            }
        });
    }

    // init on load
    initInboundsTable();
    refreshInboundSummary();

    // populate inbound product combo (select2)
    function loadInboundProductsCombo() {
        $.get(nxUrl('belzona-inventory'), { action: 'unique_values', column: 'sheet_name' }, function(res) {
            if (!res || !res.success) return;
            var $sel = $('#belzona-inbounds-filter-sheet');
            var prev = $sel.val() || '';
            $sel.empty();
            $sel.append('<option value="">همه محصولات</option>');
            (res.data || []).forEach(function(v) {
                if (!v) return;
                var safe = String(v).replace(/\"/g, '&quot;');
                $sel.append('<option value="' + safe + '">' + safe + '</option>');
            });
            if (prev) $sel.val(prev);

            // init select2 once
            if ($.fn.select2 && !$sel.hasClass('select2-hidden-accessible')) {
                $sel.select2({
                    width: '100%',
                    dir: 'rtl',
                    placeholder: 'انتخاب محصول...',
                    allowClear: true
                });
            }
        });
    }
    loadInboundProductsCombo();

    // actions
    $('#belzona-inbounds-refresh').on('click', function(){
        refreshInboundSummary();
        if (inboundsTable) inboundsTable.ajax.reload();
    });
    $('#belzona-inbounds-clear').on('click', function(){
        $('#belzona-inbounds-filter-sheet').val('').trigger('change');
        $('#belzona-inbounds-date-from').val('');
        $('#belzona-inbounds-date-to').val('');
        refreshInboundSummary();
        if (inboundsTable) inboundsTable.ajax.reload();
    });
    $('#belzona-inbounds-filter-sheet, #belzona-inbounds-date-from, #belzona-inbounds-date-to').on('change keyup', function(){
        // light debounce behavior (DataTables itself debounces server calls)
        refreshInboundSummary();
        if (inboundsTable) inboundsTable.ajax.reload();
    });

    $(document).on('click', '.belzona-open-outbounds', function(){
        openOutboundsModal($(this).data('sheet'), $(this).data('row'));
    });

    $('#belzona-latest-inbound-open').on('click', function(){
        openOutboundsModal($(this).data('sheet'), $(this).data('row'));
    });

    // fill product dropdown from distinct sheet_name values
    function loadProducts() {
        $.get(nxUrl('belzona-inventory'), { action: 'unique_values', column: 'sheet_name' }, function(res) {
            if (!res || !res.success) return;
            var $sel = $('#belzona-product-select');
            $sel.empty();
            $sel.append('<option value=\"\">انتخاب محصول...</option>');
            (res.data || []).forEach(function(v) {
                if (!v) return;
                var safe = String(v).replace(/\"/g, '&quot;');
                $sel.append('<option value=\"' + safe + '\">' + safe + '</option>');
            });

            // make it searchable too
            if ($.fn.select2 && !$sel.hasClass('select2-hidden-accessible')) {
                $sel.select2({
                    width: '100%',
                    dir: 'rtl',
                    placeholder: 'انتخاب محصول...',
                    allowClear: true
                });
            }
        });
    }

    function renderOutbounds(rows) {
        var $tbody = $('#belzona-summary-transactions');
        $tbody.empty();
        if (!rows || rows.length === 0) {
            $tbody.append('<tr><td colspan=\"6\" class=\"text-muted\">خروجی‌ای یافت نشد.</td></tr>');
            return;
        }
        rows.forEach(function(r) {
            $tbody.append(
                '<tr>' +
                '<td>' + (r.date_raw || '') + '</td>' +
                '<td>' + fmtNumber(r.output || 0) + '</td>' +
                '<td>' + fmtNumber(r.balance || 0) + '</td>' +
                '<td>' + (r.invoice_number || '') + '</td>' +
                '<td>' + (r.customer_name || '') + '</td>' +
                '<td>' + (r.notes || '') + '</td>' +
                '</tr>'
            );
        });
    }

    function renderBatches(batches, totals) {
        var $tbody = $('#belzona-inbound-batches');
        $tbody.empty();

        var count = (batches && batches.length) ? batches.length : 0;
        $('#belzona-batches-count').text(count ? ('(' + count + ' پارت)') : '(0 پارت)');

        if (!batches || batches.length === 0) {
            $tbody.append('<tr><td colspan="7" class="text-muted">پارتی یافت نشد.</td></tr>');
            return;
        }

        batches.forEach(function(b, idx) {
            var label = b.label || 'ورود';
            var dateRaw = b.date_raw || '';
            var input = fmtNumber(b.input || 0);
            var outTotal = fmtNumber(b.out_total || 0);
            var remaining = fmtNumber(b.remaining || 0);
            var outCount = fmtNumber(b.out_count || 0);
            var rowNum = b.inbound_row_number || '';

            $tbody.append(
                '<tr class="belzona-batch-row" data-row-number="' + rowNum + '">' +
                '<td>' + (idx + 1) + '</td>' +
                '<td>' + label + '</td>' +
                '<td>' + dateRaw + '</td>' +
                '<td>' + input + '</td>' +
                '<td>' + outTotal + '</td>' +
                '<td>' + remaining + '</td>' +
                '<td>' + outCount + '</td>' +
                '</tr>'
            );
        });
    }

    function loadBatchOutbounds(sheetName, inboundRowNumber) {
        var dateFrom = $('#belzona-date-from').val();
        var dateTo = $('#belzona-date-to').val();

        $.get(nxUrl('belzona-inventory'), {
            action: 'batch_outbounds',
            sheet_name: sheetName,
            inbound_row_number: inboundRowNumber,
            filter_date_from: dateFrom,
            filter_date_to: dateTo
        }, function(res) {
            if (!res || !res.success) return;
            var d = res.data || {};
            var inbound = d.inbound || {};

            $('#belzona-selected-batch-label').text(inbound.label ? ('- ' + inbound.label) : '');
            $('#belzona-selected-batch-meta').text(
                'ورود: ' + fmtNumber(inbound.input || 0) +
                ' | خروجی: ' + fmtNumber(inbound.out_total || 0) +
                ' | مانده: ' + fmtNumber(inbound.remaining || 0) +
                (inbound.date_raw ? (' | تاریخ ورود: ' + inbound.date_raw) : '')
            );

            renderOutbounds(d.outbounds || []);
        });
    }

    function loadSummary() {
        var sheetName = $('#belzona-product-select').val();
        if (!sheetName) return;

        var dateFrom = $('#belzona-date-from').val();
        var dateTo = $('#belzona-date-to').val();

        // Load batches (inbounds) and aggregate totals
        $.get(nxUrl('belzona-inventory'), {
            action: 'product_batches',
            sheet_name: sheetName,
            filter_date_from: dateFrom,
            filter_date_to: dateTo
        }, function(res) {
            if (!res || !res.success) return;
            var d = res.data || {};
            var totals = (d.totals || {});
            var batches = (d.batches || []);

            $('#belzona-summary-input').text(fmtNumber(totals.input_total || 0));
            $('#belzona-summary-output').text(fmtNumber(totals.out_total || 0));
            $('#belzona-summary-net').text(fmtNumber((totals.input_total || 0) - (totals.out_total || 0)));

            if (batches.length) {
                var last = batches[batches.length - 1];
                $('#belzona-summary-balance').text(fmtNumber(last.input || 0));
                $('#belzona-summary-lastdate').text(last.date_raw || '-');
            } else {
                $('#belzona-summary-balance').text('-');
                $('#belzona-summary-lastdate').text('-');
            }

            renderBatches(batches, totals);

            // auto-select first batch
            if (batches.length) {
                var firstRowNum = batches[0].inbound_row_number;
                $('.belzona-batch-row').removeClass('table-primary');
                $('.belzona-batch-row[data-row-number="' + firstRowNum + '"]').addClass('table-primary');
                loadBatchOutbounds(sheetName, firstRowNum);
            } else {
                $('#belzona-selected-batch-label').text('');
                $('#belzona-selected-batch-meta').text('');
                renderOutbounds([]);
            }
        });
    }

    loadProducts();
    $('#belzona-refresh-summary').on('click', loadSummary);

    // clicking on a batch shows its outbounds
    $(document).on('click', '.belzona-batch-row', function() {
        var sheetName = $('#belzona-product-select').val();
        var rowNumber = $(this).data('row-number');
        if (!sheetName || !rowNumber) return;
        $('.belzona-batch-row').removeClass('table-primary');
        $(this).addClass('table-primary');
        loadBatchOutbounds(sheetName, rowNumber);
    });
});
</script>

<!-- Outbounds Modal -->
<div class="modal fade" id="belzona-outbounds-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="belzona-modal-title">خروجی‌های پارت</span>
                    <small class="text-muted d-block" style="font-size: 12px;">
                        محصول: <span id="belzona-modal-sheet">-</span> | ردیف ورود: <span id="belzona-modal-inbound-row">-</span>
                    </small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border mb-3">
                    <span id="belzona-modal-meta" class="text-muted">-</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>تاریخ</th>
                                <th>خروجی</th>
                                <th>مانده</th>
                                <th>فاکتور</th>
                                <th>مشتری</th>
                                <th>توضیحات</th>
                            </tr>
                        </thead>
                        <tbody id="belzona-modal-tbody">
                            <tr><td colspan="6" class="text-muted">-</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
@endsection

