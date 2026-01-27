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
$(document).ready(function() {
    var baseUrl = (window.NX && NX.site_url) ? String(NX.site_url).replace(/\/$/, '') : '';
    function nxUrl(path) {
        path = String(path || '').replace(/^\//, '');
        return baseUrl ? (baseUrl + '/' + path) : ('/' + path);
    }

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
        });
    }

    function fmtNumber(v) {
        try {
            return new Intl.NumberFormat('fa-IR').format(parseFloat(v || 0));
        } catch (e) {
            return v;
        }
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
@endsection

