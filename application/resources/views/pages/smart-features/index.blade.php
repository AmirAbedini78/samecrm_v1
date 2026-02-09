@extends('layout.wrapper')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3 class="page-title">فیچرهای جدید</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">حسابداری</a></li>
                    <li class="breadcrumb-item active">فیچرهای جدید</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <p class="text-muted mb-4">تحلیل‌های هوشمند بر اساس داده‌های فروش و انبار با استفاده از روش‌های آماری و یادگیری ماشین (مطابق فیچرهای بخش ۳ سند تحلیل AI/ML).</p>

                <!-- لینک به گزارش‌های موجود (بخش ۳.۱ سند) -->
                <div class="card mb-4 border-info">
                    <div class="card-body py-3">
                        <h6 class="mb-2"><i class="ti-link"></i> گزارش‌های تحلیلی موجود در سیستم</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url('/report/sales/comparison') }}" class="btn btn-sm btn-outline-secondary">مقایسه دو بازه فروش</a>
                            <a href="{{ url('/report/sales/analytics') }}" class="btn btn-sm btn-outline-secondary">تحلیل‌های فروش (پارتو، فصلی، سود، تحویل)</a>
                            <a href="{{ url('/report/warehouse') }}" class="btn btn-sm btn-outline-secondary">گزارش انبار (موجودی، انقضا، تراکنش‌ها)</a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label">از تاریخ</label>
                                <input type="text" id="sf_from_date" class="form-control form-control-sm persian-date-input" placeholder="از تاریخ" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">تا تاریخ</label>
                                <input type="text" id="sf_to_date" class="form-control form-control-sm persian-date-input" placeholder="تا تاریخ" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">یا بازه (روز)</label>
                                <select id="sf_days" class="form-control form-control-sm">
                                    <option value="30">۳۰ روز</option>
                                    <option value="90">۹۰ روز</option>
                                    <option value="180">۱۸۰ روز</option>
                                    <option value="365">یک سال</option>
                                    <option value="3650" selected>همهٔ داده‌ها</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-sm" id="sf_btn_refresh"><i class="ti-reload"></i> بروزرسانی</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- خلاصه داده‌ها: فروش، انبار، انبار بلزونا، تسویه فاکتور -->
                <div class="row mb-4">
                    <div class="col-12"><h6 class="text-muted mb-2">خلاصه بخش‌های حسابداری (از کل داده)</h6></div>
                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0"><i class="ti-shopping-cart text-primary"></i> خلاصه فروش</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-load-summary" data-type="sales" title="بروزرسانی"><i class="ti-reload"></i></button>
                            </div>
                            <div class="card-body py-2">
                                <div id="summary-sales-loading" class="text-center py-2 small text-muted">در حال بارگذاری...</div>
                                <div id="summary-sales-wrap" class="d-none">
                                    <p class="mb-1 small"><strong>تعداد رکورد:</strong> <span id="sales-count"></span></p>
                                    <p class="mb-1 small"><strong>مجموع مبلغ:</strong> <span id="sales-total-amount"></span></p>
                                    <p class="mb-1 small"><strong>بازه تاریخ:</strong> <span id="sales-date-range"></span></p>
                                    <div class="small text-muted">بیشترین فروش (محصول):</div>
                                    <ul id="sales-top-list" class="list-unstyled mb-0 small"></ul>
                                </div>
                                <div id="summary-sales-empty" class="d-none text-muted small">داده‌ای در فروش وجود ندارد.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0"><i class="ti-package text-warning"></i> خلاصه انبار</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-load-summary" data-type="inventory" title="بروزرسانی"><i class="ti-reload"></i></button>
                            </div>
                            <div class="card-body py-2">
                                <div id="summary-inventory-loading" class="text-center py-2 small text-muted">در حال بارگذاری...</div>
                                <div id="summary-inventory-wrap" class="d-none">
                                    <p class="mb-1 small"><strong>اقلام فعال:</strong> <span id="inventory-active"></span></p>
                                    <p class="mb-1 small"><strong>مجموع موجودی:</strong> <span id="inventory-total-qty"></span></p>
                                    <p class="mb-1 small"><strong>زیر حد/صفر:</strong> <span id="inventory-low-zero"></span></p>
                                    <div class="small text-muted">بیشترین موجودی:</div>
                                    <ul id="inventory-top-list" class="list-unstyled mb-0 small"></ul>
                                </div>
                                <div id="summary-inventory-empty" class="d-none text-muted small">داده‌ای در انبار وجود ندارد.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0"><i class="ti-package text-info"></i> خلاصه انبار بلزونا</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-load-summary" data-type="belzona" title="بروزرسانی"><i class="ti-reload"></i></button>
                            </div>
                            <div class="card-body py-2">
                                <div id="summary-belzona-loading" class="text-center py-2 small text-muted">در حال بارگذاری...</div>
                                <div id="summary-belzona-wrap" class="d-none">
                                    <p class="mb-1 small"><strong>تعداد رکورد:</strong> <span id="belzona-count"></span></p>
                                    <div class="small text-muted">بیشترین خروجی (محصول):</div>
                                    <ul id="belzona-top-list" class="list-unstyled mb-0 small"></ul>
                                </div>
                                <div id="summary-belzona-empty" class="d-none text-muted small">داده‌ای در انبار بلزونا وجود ندارد.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center py-2">
                                <h6 class="mb-0"><i class="ti-wallet text-success"></i> خلاصه تسویه فاکتور</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-load-summary" data-type="settlement" title="بروزرسانی"><i class="ti-reload"></i></button>
                            </div>
                            <div class="card-body py-2">
                                <div id="summary-settlement-loading" class="text-center py-2 small text-muted">در حال بارگذاری...</div>
                                <div id="summary-settlement-wrap" class="d-none">
                                    <p class="mb-1 small"><strong>مانده کل:</strong> <span id="settlement-total-balance"></span></p>
                                    <p class="mb-1 small"><strong>تعداد سند:</strong> <span id="settlement-count"></span></p>
                                    <div class="small text-muted">بیشترین مانده (مشتری):</div>
                                    <ul id="settlement-top-list" class="list-unstyled mb-0 small"></ul>
                                </div>
                                <div id="summary-settlement-empty" class="d-none text-muted small">داده‌ای در جدول تسویه وجود ندارد.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 1. Demand Forecast -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti-line-chart text-primary"></i> پیش‌بینی تقاضا (Exponential Smoothing)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-load-forecast">بارگذاری</button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">میانگین هفتگی فروش و پیش‌بینی دوره بعد با هموارسازی نمایی.</p>
                        <div id="forecast-loading" class="text-center py-4 d-none">در حال محاسبه...</div>
                        <div id="forecast-table-wrap" class="table-responsive d-none">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>کد کالا</th>
                                        <th>نام کالا</th>
                                        <th>میانگین هفتگی</th>
                                        <th>پیش‌بینی دوره بعد</th>
                                        <th>روند</th>
                                    </tr>
                                </thead>
                                <tbody id="forecast-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 1b. Demand Forecast LSTM (یادگیری عمیق) -->
                <div class="card mb-4 border-primary">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="mb-0"><i class="ti-pulse text-primary"></i> پیش‌بینی تقاضا (LSTM – یادگیری عمیق)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-load-forecast-lstm">بارگذاری</button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">پیش‌بینی با شبکه LSTM برای هر محصول. در صورت نصب بودن Python و TensorFlow از LSTM استفاده می‌شود؛ وگرنه از همان هموارسازی نمایی.</p>
                        <div id="forecast-lstm-loading" class="text-center py-4 d-none">در حال محاسبه (ممکن است چند ثانیه طول بکشد)...</div>
                        <div id="forecast-lstm-table-wrap" class="table-responsive d-none">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>کد کالا</th>
                                        <th>نام کالا</th>
                                        <th>پیش‌بینی دوره بعد</th>
                                        <th>پیش‌بینی LSTM (۴ دوره)</th>
                                        <th>روش</th>
                                    </tr>
                                </thead>
                                <tbody id="forecast-lstm-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. Smart Alerts -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti-alert text-warning"></i> هشدار موجودی هوشمند</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-load-alerts">بارگذاری</button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">بر اساس مصرف روزانه و حداقل موجودی، روزهای باقی‌مانده تا کمبود و مقدار پیشنهادی سفارش.</p>
                        <div id="alerts-loading" class="text-center py-4 d-none">در حال محاسبه...</div>
                        <div id="alerts-table-wrap" class="table-responsive d-none">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>کد</th>
                                        <th>نام کالا</th>
                                        <th>موجودی</th>
                                        <th>حداقل</th>
                                        <th>مصرف روزانه</th>
                                        <th>روز تا کمبود</th>
                                        <th>پیشنهاد سفارش</th>
                                        <th>وضعیت</th>
                                    </tr>
                                </thead>
                                <tbody id="alerts-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. Customer Clustering -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti-pie-chart text-info"></i> خوشه‌بندی مشتریان (K-Means)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-load-clustering">بارگذاری</button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">دسته‌بندی مشتریان به سه بخش A (باارزش)، B (متوسط)، C (کم‌تعامل) بر اساس مبلغ، تعداد سفارش و تازگی.</p>
                        <div id="clustering-loading" class="text-center py-4 d-none">در حال محاسبه...</div>
                        <div id="clustering-wrap" class="d-none">
                            <div id="clustering-segments" class="row"></div>
                        </div>
                    </div>
                </div>

                <!-- 4. Anomaly Detection -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti-stats-up text-danger"></i> تشخیص ناهنجاری فروش (IQR)</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-load-anomaly">بارگذاری</button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">روزهایی که مبلغ فروش خارج از محدوده معمول (چارک اول و سوم ± ۱.۵ IQR) است.</p>
                        <div id="anomaly-loading" class="text-center py-4 d-none">در حال محاسبه...</div>
                        <div id="anomaly-table-wrap" class="table-responsive d-none">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>تاریخ</th>
                                        <th>تعداد سفارش</th>
                                        <th>مبلغ کل</th>
                                        <th>دلیل</th>
                                    </tr>
                                </thead>
                                <tbody id="anomaly-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 4b. Anomaly Detection Autoencoder (یادگیری عمیق) -->
                <div class="card mb-4 border-danger">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="mb-0"><i class="ti-pulse text-danger"></i> تشخیص ناهنجاری (Autoencoder – یادگیری عمیق)</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="btn-load-anomaly-ae">بارگذاری</button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">تشخیص روزهای غیرعادی با Autoencoder. در صورت نصب Python و TensorFlow یا scikit-learn از مدل استفاده می‌شود.</p>
                        <div id="anomaly-ae-loading" class="text-center py-4 d-none">در حال محاسبه...</div>
                        <div id="anomaly-ae-table-wrap" class="table-responsive d-none">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>تاریخ</th>
                                        <th>امتیاز Z (خطای بازسازی)</th>
                                        <th>دلیل</th>
                                    </tr>
                                </thead>
                                <tbody id="anomaly-ae-tbody"></tbody>
                            </table>
                            <p id="anomaly-ae-method" class="small text-muted mt-2 mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function qs(id) { return document.getElementById(id); }
    function params() {
        var from = qs('sf_from_date') && qs('sf_from_date').value ? qs('sf_from_date').value : '';
        var to = qs('sf_to_date') && qs('sf_to_date').value ? qs('sf_to_date').value : '';
        var days = qs('sf_days') ? qs('sf_days').value : '90';
        var p = {};
        if (from) p.from_date = from;
        if (to) p.to_date = to;
        if (!from && !to) p.days = days;
        return p;
    }
    function paramStr() {
        var p = params();
        return '?' + Object.keys(p).map(function(k) { return encodeURIComponent(k) + '=' + encodeURIComponent(p[k]); }).join('&');
    }

    function loadForecast() {
        var wrap = qs('forecast-table-wrap'), load = qs('forecast-loading'), tbody = qs('forecast-tbody');
        wrap.classList.add('d-none'); load.classList.remove('d-none');
        fetch('/smart-features/demand-forecast' + paramStr()).then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data && res.data.length) {
                tbody.innerHTML = res.data.slice(0, 25).map(function(row) {
                    return '<tr><td>' + (row.product_code || '-') + '</td><td>' + (row.product_name || '-') + '</td><td>' + (row.avg_weekly_qty || 0) + '</td><td>' + (row.forecast_next_period || 0) + '</td><td>' + (row.trend || 0) + '</td></tr>';
                }).join('');
                wrap.classList.remove('d-none');
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">داده‌ای یافت نشد</td></tr>';
                wrap.classList.remove('d-none');
            }
        }).catch(function() { load.classList.add('d-none'); if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'خطا در بارگذاری' }); });
    }
    function loadAlerts() {
        var wrap = qs('alerts-table-wrap'), load = qs('alerts-loading'), tbody = qs('alerts-tbody');
        wrap.classList.add('d-none'); load.classList.remove('d-none');
        fetch('/smart-features/smart-alerts' + paramStr()).then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data && res.data.length) {
                tbody.innerHTML = res.data.slice(0, 30).map(function(row) {
                    var bad = row.is_low ? ' <span class="badge bg-danger">کم</span>' : '';
                    return '<tr><td>' + (row.inventory_code || '-') + '</td><td>' + (row.inventory_name || '-') + '</td><td>' + row.current_quantity + '</td><td>' + row.minimum_stock + '</td><td>' + row.avg_daily_usage + '</td><td>' + row.days_until_min_stock + '</td><td>' + row.suggested_reorder_qty + '</td><td>' + (row.main_unit || '') + bad + '</td></tr>';
                }).join('');
                wrap.classList.remove('d-none');
            } else {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">داده‌ای یافت نشد</td></tr>';
                wrap.classList.remove('d-none');
            }
        }).catch(function() { load.classList.add('d-none'); if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'خطا در بارگذاری' }); });
    }
    function loadClustering() {
        var wrap = qs('clustering-wrap'), load = qs('clustering-loading'), seg = qs('clustering-segments');
        wrap.classList.add('d-none'); load.classList.remove('d-none');
        fetch('/smart-features/customer-clustering' + paramStr()).then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data && res.data.segments && res.data.segments.length) {
                seg.innerHTML = res.data.segments.map(function(s) {
                    var list = (s.customers || []).slice(0, 15).map(function(c) {
                        return '<li class="list-group-item d-flex justify-content-between"><span>' + (c.customer_name || '-') + '</span><span>' + (c.order_count || 0) + ' سفارش / ' + (c.total_amount || 0).toLocaleString('fa-IR') + '</span></li>';
                    }).join('');
                    return '<div class="col-md-4"><div class="card"><div class="card-header">' + (s.label || '') + ' (' + (s.count || 0) + ')</div><ul class="list-group list-group-flush">' + list + '</ul></div></div>';
                }).join('');
                wrap.classList.remove('d-none');
            } else {
                seg.innerHTML = '<div class="col-12 text-center text-muted">داده‌ای یافت نشد</div>';
                wrap.classList.remove('d-none');
            }
        }).catch(function() { load.classList.add('d-none'); if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'خطا در بارگذاری' }); });
    }
    function loadAnomaly() {
        var wrap = qs('anomaly-table-wrap'), load = qs('anomaly-loading'), tbody = qs('anomaly-tbody');
        wrap.classList.add('d-none'); load.classList.remove('d-none');
        fetch('/smart-features/anomaly-detection' + paramStr()).then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data && res.data.anomalies && res.data.anomalies.length) {
                tbody.innerHTML = res.data.anomalies.map(function(row) {
                    return '<tr><td>' + (row.day || '') + '</td><td>' + (row.order_count || 0) + '</td><td>' + (row.total_amount || 0).toLocaleString('fa-IR') + '</td><td>' + (row.reason || '') + '</td></tr>';
                }).join('');
                wrap.classList.remove('d-none');
            } else {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">ناهنجاری یافت نشد یا داده کافی نیست</td></tr>';
                wrap.classList.remove('d-none');
            }
        }).catch(function() { load.classList.add('d-none'); if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'خطا در بارگذاری' }); });
    }

    function loadForecastLstm() {
        var wrap = qs('forecast-lstm-table-wrap'), load = qs('forecast-lstm-loading'), tbody = qs('forecast-lstm-tbody');
        if (!wrap) return;
        wrap.classList.add('d-none'); load.classList.remove('d-none');
        fetch('/smart-features/demand-forecast-lstm' + paramStr()).then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data && res.data.length) {
                tbody.innerHTML = res.data.slice(0, 20).map(function(row) {
                    var lstm = (row.lstm_predictions || []).join(', ');
                    var method = row.lstm_method || '-';
                    return '<tr><td>' + (row.product_code || '-') + '</td><td>' + (row.product_name || '-') + '</td><td>' + (row.forecast_next_period || 0) + '</td><td>' + lstm + '</td><td>' + method + '</td></tr>';
                }).join('');
                wrap.classList.remove('d-none');
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">داده‌ای یافت نشد یا اسکریپت پایتون در دسترس نیست</td></tr>';
                wrap.classList.remove('d-none');
            }
        }).catch(function() { load.classList.add('d-none'); if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'خطا در بارگذاری' }); });
    }
    function loadAnomalyAutoencoder() {
        var wrap = qs('anomaly-ae-table-wrap'), load = qs('anomaly-ae-loading'), tbody = qs('anomaly-ae-tbody'), methodEl = qs('anomaly-ae-method');
        if (!wrap) return;
        wrap.classList.add('d-none'); load.classList.remove('d-none');
        fetch('/smart-features/anomaly-autoencoder' + paramStr()).then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data) {
                var ae = res.data.autoencoder_anomalies || [];
                var method = res.data.autoencoder_method || 'IQR (بدون پایتون)';
                if (ae.length) {
                    tbody.innerHTML = ae.map(function(row) {
                        return '<tr><td>' + (row.date || '') + '</td><td>' + (row.z_score || '') + '</td><td>' + (row.reason || '') + '</td></tr>';
                    }).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">ناهنجاری با Autoencoder یافت نشد</td></tr>';
                }
                if (methodEl) methodEl.textContent = 'روش: ' + method;
                wrap.classList.remove('d-none');
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">خطا در دریافت داده</td></tr>';
                wrap.classList.remove('d-none');
            }
        }).catch(function() { load.classList.add('d-none'); if (typeof NX !== 'undefined') NX.notification({ type: 'error', message: 'خطا در بارگذاری' }); });
    }

    function loadSettlementSummary() {
        var load = qs('summary-settlement-loading'), wrap = qs('summary-settlement-wrap'), empty = qs('summary-settlement-empty');
        if (!load) return;
        load.classList.remove('d-none'); wrap.classList.add('d-none'); empty.classList.add('d-none');
        fetch('/smart-features/settlement-summary').then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data) {
                var d = res.data;
                if (d.record_count === 0) { empty.classList.remove('d-none'); return; }
                wrap.classList.remove('d-none');
                qs('settlement-total-balance').textContent = (d.total_balance || 0).toLocaleString('fa-IR');
                qs('settlement-count').textContent = (d.record_count || 0).toLocaleString('fa-IR');
                var list = qs('settlement-top-list');
                list.innerHTML = (d.top_by_balance || []).slice(0, 5).map(function(c) {
                    return '<li>' + (c.customer_name || '-') + ': ' + (c.balance || 0).toLocaleString('fa-IR') + '</li>';
                }).join('');
            } else { empty.classList.remove('d-none'); }
        }).catch(function() { load.classList.add('d-none'); empty.classList.remove('d-none'); });
    }
    function loadBelzonaSummary() {
        var load = qs('summary-belzona-loading'), wrap = qs('summary-belzona-wrap'), empty = qs('summary-belzona-empty');
        if (!load) return;
        load.classList.remove('d-none'); wrap.classList.add('d-none'); empty.classList.add('d-none');
        fetch('/smart-features/belzona-summary').then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data) {
                var d = res.data;
                if (d.record_count === 0) { empty.classList.remove('d-none'); return; }
                wrap.classList.remove('d-none');
                qs('belzona-count').textContent = (d.record_count || 0).toLocaleString('fa-IR');
                var list = qs('belzona-top-list');
                list.innerHTML = (d.top_by_output || []).slice(0, 5).map(function(p) {
                    return '<li>' + (p.product_name || '-') + ': خروج ' + (p.total_output || 0).toLocaleString('fa-IR') + '</li>';
                }).join('');
            } else { empty.classList.remove('d-none'); }
        }).catch(function() { load.classList.add('d-none'); empty.classList.remove('d-none'); });
    }
    function loadSalesSummary() {
        var load = qs('summary-sales-loading'), wrap = qs('summary-sales-wrap'), empty = qs('summary-sales-empty');
        if (!load) return;
        load.classList.remove('d-none'); wrap.classList.add('d-none'); empty.classList.add('d-none');
        fetch('/smart-features/sales-summary').then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data) {
                var d = res.data;
                if (d.record_count === 0) { empty.classList.remove('d-none'); return; }
                wrap.classList.remove('d-none');
                qs('sales-count').textContent = (d.record_count || 0).toLocaleString('fa-IR');
                qs('sales-total-amount').textContent = (d.total_amount || 0).toLocaleString('fa-IR');
                qs('sales-date-range').textContent = (d.min_date || '-') + ' تا ' + (d.max_date || '-');
                var list = qs('sales-top-list');
                list.innerHTML = (d.top_by_amount || []).slice(0, 5).map(function(p) {
                    return '<li>' + (p.product_name || p.product_code || '-') + ': ' + (p.total_amount || 0).toLocaleString('fa-IR') + '</li>';
                }).join('');
            } else { empty.classList.remove('d-none'); }
        }).catch(function() { load.classList.add('d-none'); empty.classList.remove('d-none'); });
    }
    function loadInventorySummary() {
        var load = qs('summary-inventory-loading'), wrap = qs('summary-inventory-wrap'), empty = qs('summary-inventory-empty');
        if (!load) return;
        load.classList.remove('d-none'); wrap.classList.add('d-none'); empty.classList.add('d-none');
        fetch('/smart-features/inventory-summary').then(function(r) { return r.json(); }).then(function(res) {
            load.classList.add('d-none');
            if (res.success && res.data) {
                var d = res.data;
                if (d.record_count === 0) { empty.classList.remove('d-none'); return; }
                wrap.classList.remove('d-none');
                qs('inventory-active').textContent = (d.active_count || 0).toLocaleString('fa-IR');
                qs('inventory-total-qty').textContent = (d.total_quantity || 0).toLocaleString('fa-IR');
                qs('inventory-low-zero').textContent = (d.low_stock_count || 0) + ' زیر حد / ' + (d.zero_stock_count || 0) + ' صفر';
                var list = qs('inventory-top-list');
                list.innerHTML = (d.top_by_quantity || []).slice(0, 5).map(function(p) {
                    return '<li>' + (p.inventory_name || p.inventory_code || '-') + ': ' + (p.current_quantity || 0).toLocaleString('fa-IR') + ' ' + (p.main_unit || '') + '</li>';
                }).join('');
            } else { empty.classList.remove('d-none'); }
        }).catch(function() { load.classList.add('d-none'); empty.classList.remove('d-none'); });
    }

    if (qs('btn-load-forecast')) qs('btn-load-forecast').addEventListener('click', loadForecast);
    if (qs('btn-load-forecast-lstm')) qs('btn-load-forecast-lstm').addEventListener('click', loadForecastLstm);
    if (qs('btn-load-alerts')) qs('btn-load-alerts').addEventListener('click', loadAlerts);
    if (qs('btn-load-clustering')) qs('btn-load-clustering').addEventListener('click', loadClustering);
    if (qs('btn-load-anomaly')) qs('btn-load-anomaly').addEventListener('click', loadAnomaly);
    if (qs('btn-load-anomaly-ae')) qs('btn-load-anomaly-ae').addEventListener('click', loadAnomalyAutoencoder);
    if (qs('sf_btn_refresh')) {
        qs('sf_btn_refresh').addEventListener('click', function() {
            loadForecast(); loadForecastLstm(); loadAlerts(); loadClustering(); loadAnomaly(); loadAnomalyAutoencoder();
        });
    }
    document.querySelectorAll('.btn-load-summary').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var t = this.getAttribute('data-type');
            if (t === 'settlement') loadSettlementSummary();
            else if (t === 'belzona') loadBelzonaSummary();
            else if (t === 'sales') loadSalesSummary();
            else if (t === 'inventory') loadInventorySummary();
        });
    });
    loadSalesSummary();
    loadInventorySummary();
    loadBelzonaSummary();
    loadSettlementSummary();
})();
</script>
@endsection
