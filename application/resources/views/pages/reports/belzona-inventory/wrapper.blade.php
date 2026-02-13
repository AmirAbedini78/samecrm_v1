<div id="belzona-reporting-root">
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
                            <label class="form-label">مدت ماندگاری (سال)</label>
                            <div class="input-group">
                                <input type="number" id="belzona-shelf-life-years" class="form-control" min="0.5" step="0.5" placeholder="مثلاً 2" title="شلف لایف به سال">
                                <button type="button" id="belzona-apply-shelf-life" class="btn btn-outline-success" title="اعمال برای محصول انتخاب‌شده">
                                    <i class="ti-check"></i>
                                </button>
                            </div>
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

    <!-- Page Actions (for main datatable) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="list-page-actions-search">
                                <input type="text" name="search_query" id="search_query" class="form-control" placeholder="جستجو در انبار بلزونا">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ _url('/belzona-inventory') }}" class="btn btn-outline-primary">
                                <i class="ti-import"></i> بازگشت به لیست ورودی‌ها
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
                                    <th>شلف لایف (سال)</th>
                                    <th>تاریخ انقضا (شمسی) / مانده</th>
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

@once
<script>
(function () {
    function loadScriptOnce(id, src, done) {
        if (document.getElementById(id)) {
            if (typeof done === 'function') done();
            return;
        }
        var s = document.createElement('script');
        s.id = id;
        s.src = src;
        s.onload = function () { if (typeof done === 'function') done(); };
        document.body.appendChild(s);
    }

    function bootBelzonaInventoryReports() {
        // prevent duplicate init from document-ready when loading scripts after DOM is ready
        window.__belzonaInventoryAutoInitDisabled = true;
        loadScriptOnce('datatables-belzona-inventory-script', '{{ asset('public/js/core/datatables-belzona-inventory.js') }}', function () {
            loadScriptOnce('belzona-inventory-reports-script', '{{ asset('public/js/core/belzona-inventory-reports.js') }}', function () {
                if (window.initBelzonaInventoryReports) {
                    window.initBelzonaInventoryReports();
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootBelzonaInventoryReports);
    } else {
        bootBelzonaInventoryReports();
    }
})();
</script>
@endonce
</div>

