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
                            <input type="date" id="belzona-date-from" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">تا تاریخ</label>
                            <input type="date" id="belzona-date-to" class="form-control">
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
                                    <div class="text-muted">جمع ورودی</div>
                                    <div class="h4 mb-0" id="belzona-summary-input">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">جمع خروجی</div>
                                    <div class="h4 mb-0" id="belzona-summary-output">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">خالص (ورودی-خروجی)</div>
                                    <div class="h4 mb-0" id="belzona-summary-net">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="text-muted">آخرین مانده / آخرین تاریخ</div>
                                    <div class="h5 mb-0" id="belzona-summary-balance">-</div>
                                    <small class="text-muted" id="belzona-summary-lastdate">-</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>ورودی</th>
                                            <th>خروجی</th>
                                            <th>مانده</th>
                                            <th>فاکتور</th>
                                            <th>مشتری</th>
                                            <th>توضیحات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="belzona-summary-transactions">
                                        <tr><td colspan="7" class="text-muted">یک محصول انتخاب کنید.</td></tr>
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
$(document).ready(function() {
    // fill product dropdown from distinct sheet_name values
    function loadProducts() {
        $.get('/belzona-inventory', { action: 'unique_values', column: 'sheet_name' }, function(res) {
            if (!res || !res.success) return;
            var $sel = $('#belzona-product-select');
            $sel.empty();
            $sel.append('<option value=\"\">انتخاب محصول...</option>');
            (res.data || []).forEach(function(v) {
                if (!v) return;
                var safe = String(v).replace(/\"/g, '&quot;');
                $sel.append('<option value=\"' + safe + '\">' + safe + '</option>');
            });
        });
    }

    function renderTransactions(rows) {
        var $tbody = $('#belzona-summary-transactions');
        $tbody.empty();
        if (!rows || rows.length === 0) {
            $tbody.append('<tr><td colspan=\"7\" class=\"text-muted\">تراکنشی یافت نشد.</td></tr>');
            return;
        }
        rows.forEach(function(r) {
            $tbody.append(
                '<tr>' +
                '<td>' + (r.date_raw || '') + '</td>' +
                '<td>' + (r.input || 0) + '</td>' +
                '<td>' + (r.output || 0) + '</td>' +
                '<td>' + (r.balance || 0) + '</td>' +
                '<td>' + (r.invoice_number || '') + '</td>' +
                '<td>' + (r.customer_name || '') + '</td>' +
                '<td>' + (r.notes || '') + '</td>' +
                '</tr>'
            );
        });
    }

    function loadSummary() {
        var sheetName = $('#belzona-product-select').val();
        if (!sheetName) return;

        var dateFrom = $('#belzona-date-from').val();
        var dateTo = $('#belzona-date-to').val();

        $.get('/belzona-inventory', {
            action: 'product_summary',
            sheet_name: sheetName,
            filter_date_from: dateFrom,
            filter_date_to: dateTo
        }, function(res) {
            if (!res || !res.success) return;
            var d = res.data || {};
            $('#belzona-summary-input').text(d.total_input || 0);
            $('#belzona-summary-output').text(d.total_output || 0);
            $('#belzona-summary-net').text(d.net || 0);
            $('#belzona-summary-balance').text((d.latest_balance !== null && d.latest_balance !== undefined) ? d.latest_balance : '-');
            $('#belzona-summary-lastdate').text(d.last_date_raw || '-');
        });

        $.get('/belzona-inventory', {
            action: 'product_transactions',
            sheet_name: sheetName,
            filter_date_from: dateFrom,
            filter_date_to: dateTo
        }, function(res) {
            if (!res || !res.success) return;
            renderTransactions(res.data || []);
        });
    }

    loadProducts();
    $('#belzona-refresh-summary').on('click', loadSummary);
});
</script>
@endsection

