# تغییرات و توضیحات در گزارش فروش
_Exported on 11/26/2025 at 11:25:39 GMT+3:30 from Cursor (2.1.20)_

---

**User**

در بخش گزارش فروش چند تغییر نیاز داریم در گزارش تحلیل های فروش و نمودار ها اول اینکه نمودار هارو چک کنید که داده nan نداشته باشه و همچنین که در پروگرس بارها و نمودار ها داده ها خوانا باشه که در بخش درصد مشتریان این موضوع هست همچنین که در بخشی ما تعداد رو نوشتیم که تعداد در حقیقت منطور واحد سنجش هست که میتونه به کبلو گرم باشه میتونه تعداد باشه این رو هم سعی کنید اصلاح کنید همچنین که منطق و فلسفه این گزارش تجمیعی مخصوصا برای درصد مشتریان 80/20 رو من نمیدون میک توضیحی بدید که این تجمیعی چیه 

---

**Cursor**

```blade
<!-- Sales Analytics Dashboard -->
<div class="row" id="sales-analytics-dashboard">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="ti-bar-chart"></i> تحلیل‌های فروش و نمودارهای تحلیلی
                    </h4>
                    <div class="d-flex align-items-center">
                        <div id="filter-status" class="badge bg-info mr-2" style="display: none;">
                            <i class="ti-filter"></i> <span id="filter-text">فیلتر اعمال شده</span>
                        </div>
                        <div id="focus-active-badge" class="badge bg-warning mr-2" style="display: none;">
                            <i class="ti-target"></i> <span id="focus-active-text">تمرکز فعال</span>
                        </div>
                        <button id="test-data-btn" class="btn btn-sm btn-outline-secondary" title="تست داده‌ها">
                            <i class="ti-bug"></i> Debug
                        </button>
                    </div>
                </div>
                
                <!-- Date Range Filters -->
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label">شیوه فیلتر تاریخ</label>
                        <div class="btn-group btn-group-sm w-100" role="group" aria-label="حالت فیلتر تاریخ">
                            <button type="button" class="btn btn-primary" id="filter-mode-year-month" data-mode="year_month">
                                <i class="ti-calendar"></i> سال / ماه
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="filter-mode-exact" data-mode="exact">
                                <i class="ti-time"></i> تاریخ دقیق
                            </button>
                        </div>
                    </div>
                    <!-- Year Selector -->
                    <div class="col-md-3 filter-year-month-group">
                        <label class="form-label">انتخاب سال</label>
                        <select id="analytics_year" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="1400">1400</option>
                            <option value="1401">1401</option>
                            <option value="1402">1402</option>
                            <option value="1403">1403</option>
                            <option value="1404">1404</option>
                            <option value="1405">1405</option>
                        </select>
                    </div>
                    
                    <!-- Month Range -->
                    <div class="col-md-3 filter-year-month-group">
                        <label class="form-label">از ماه</label>
                        <select id="analytics_from_month" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="1">فروردین</option>
                            <option value="2">اردیبهشت</option>
                            <option value="3">خرداد</option>
                            <option value="4">تیر</option>
                            <option value="5">مرداد</option>
                            <option value="6">شهریور</option>
                            <option value="7">مهر</option>
                            <option value="8">آبان</option>
                            <option value="9">آذر</option>
                            <option value="10">دی</option>
                            <option value="11">بهمن</option>
                            <option value="12">اسفند</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 filter-year-month-group">
                        <label class="form-label">تا ماه</label>
                        <select id="analytics_to_month" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="1">فروردین</option>
                            <option value="2">اردیبهشت</option>
                            <option value="3">خرداد</option>
                            <option value="4">تیر</option>
                            <option value="5">مرداد</option>
                            <option value="6">شهریور</option>
                            <option value="7">مهر</option>
                            <option value="8">آبان</option>
                            <option value="9">آذر</option>
                            <option value="10">دی</option>
                            <option value="11">بهمن</option>
                            <option value="12">اسفند</option>
                        </select>
                    </div>
</div>

                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-4 filter-exact-group">
                        <label class="form-label">از تاریخ (دقیق)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="analytics_from_date" class="form-control persian-date-input" 
                                   placeholder="لطفاً تاریخ شروع دقیق را وارد کنید" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_from_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4 filter-exact-group">
                        <label class="form-label">تا تاریخ (دقیق)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="analytics_to_date" class="form-control persian-date-input" 
                                   placeholder="لطفاً تاریخ پایان دقیق را وارد کنید" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_to_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end justify-content-md-end">
                        <div class="w-100 w-md-auto">
                            <button id="update-analytics" class="btn btn-primary w-100">
                                <i class="ti-reload"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Filters -->
                <div class="row mb-3">
                    <div class="col-md-3 order-md-1">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>دسته‌بندی مشتری</span>
                            <small class="text-muted" id="customer-category-count"></small>
                        </label>
                        <select id="filter_customer_category" class="form-control form-control-sm">
                            <option value="">تمام دسته‌ها</option>
                        </select>
                    </div>
                    <div class="col-md-3 order-md-2">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر مشتری</span>
                            <small class="text-muted" id="customer-count"></small>
                        </label>
                        <select id="filter_customer" class="form-control form-control-sm">
                            <option value="">انتخاب کنید</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3 order-md-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر انبار</span>
                            <small class="text-muted" id="warehouse-count"></small>
                        </label>
                        <select id="filter_warehouse" class="form-control form-control-sm">
                            <option value="">انتخاب کنید</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3 order-md-4">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر محصول</span>
                            <small class="text-muted" id="product-count"></small>
                        </label>
                        <select id="filter_product" class="form-control form-control-sm">
                            <option value="">انتخاب کنید</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary" id="set-current-year">
                                <i class="ti-calendar"></i> سال جاری
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="set-last-year">
                                <i class="ti-back-left"></i> سال گذشته
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="set-current-quarter">
                                <i class="ti-layout-grid2"></i> فصل جاری
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="refresh-filters">
                                <i class="ti-reload"></i> بروزرسانی لیست‌ها
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clear-filters">
                                <i class="ti-close"></i> پاک کردن فیلترها
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-link" id="toggle-help-text">
                            <i class="ti-help-alt"></i> راهنما
                        </button>
                    </div>
                </div>
                
                <!-- Help Text (Collapsible) -->
                <div class="row mb-2" id="filter-help" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="ti-info-alt"></i> نحوه استفاده از فیلترها:</strong>
                            <ul class="mb-0 mt-2" style="font-size: 13px;">
                                <li><strong>فیلتر زمانی - روش 1 (پیشنهادی):</strong> سال و بازه ماه را انتخاب کنید → مناسب برای نمایش یک سال کامل</li>
                                <li><strong>فیلتر زمانی - روش 2:</strong> تاریخ دقیق "از" و "تا" را وارد کنید → مناسب برای بازه‌های سفارشی (چند ساله)</li>
                                <li><strong>فیلتر محصول:</strong> از لیست ComboBox محصول مورد نظر را انتخاب کنید → تحلیل فقط برای این محصول</li>
                                <li><strong>فیلتر مشتری:</strong> از لیست ComboBox مشتری مورد نظر را انتخاب کنید → تحلیل فقط برای این مشتری</li>
                                <li><strong>فیلتر انبار:</strong> از لیست ComboBox انبار مورد نظر را انتخاب کنید → تحلیل فقط برای این انبار</li>
                                <li><strong>وضعیت فروش:</strong> فیلتر بر اساس وضعیت (تکمیل شده، در انتظار، لغو شده)</li>
                                <li><strong>بروزرسانی لیست‌ها:</strong> اگر تاریخ را تغییر دادید، روی این دکمه کلیک کنید تا لیست محصولات/مشتریان/انبارها بر اساس بازه تاریخی جدید به‌روز شوند</li>
                                <li><strong>نکته:</strong> می‌توانید چند فیلتر را با هم ترکیب کنید (مثلاً: سال 1403 + محصول خاص + انبار مرکزی)</li>
                                <li><strong>تعداد موارد:</strong> کنار هر فیلتر تعداد موارد موجود نمایش داده می‌شود</li>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="mb-4">

                <!-- Focus Summary -->
                <div id="focus-summary-wrapper" class="card mb-4 focus-summary-card" style="display: none;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1" id="focus-summary-title">تمرکز فعال</h5>
                            <small class="text-muted" id="focus-summary-subtitle"></small>
                        </div>
                        <span class="badge badge-warning">
                            <i class="ti-target"></i> تمرکز فعال
                        </span>
                    </div>
                    <div class="card-body position-relative">
                        <div id="focus-summary-loading" class="focus-summary-loading" style="display: none;">
                            <div class="text-center">
                                <div class="spinner-border text-primary"></div>
                                <p class="mt-2 mb-0">در حال بارگذاری خلاصه تمرکز...</p>
                            </div>
                        </div>
                        <div id="focus-summary-content">
                            <div class="row focus-summary-metrics">
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="focus-metric-card">
                                        <span class="focus-metric-label">کل فروش</span>
                                        <h4 class="focus-metric-value" id="focus-total-amount">-</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="focus-metric-card">
                                        <span class="focus-metric-label">تعداد سفارش</span>
                                        <h4 class="focus-metric-value" id="focus-order-count">-</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="focus-metric-card">
                                        <span class="focus-metric-label">کل مقدار</span>
                                        <h4 class="focus-metric-value" id="focus-total-quantity">-</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row focus-summary-meta">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">مشتریان یکتا</span>
                                        <span class="focus-meta-value" id="focus-unique-customers">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">محصولات یکتا</span>
                                        <span class="focus-meta-value" id="focus-unique-products">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">انبارهای یکتا</span>
                                        <span class="focus-meta-value" id="focus-unique-warehouses">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">دوره زمانی</span>
                                        <span class="focus-meta-value" id="focus-date-range">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3" id="focus-top-entities">
                                <div class="col-md-4 mb-3" id="focus-top-customers" style="display: none;">
                                    <h6 class="focus-top-title">
                                        <i class="ti-user"></i> مشتریان برتر
                                    </h6>
                                    <ul class="list-group list-group-sm" id="focus-top-customers-list"></ul>
                                </div>
                                <div class="col-md-4 mb-3" id="focus-top-products" style="display: none;">
                                    <h6 class="focus-top-title">
                                        <i class="ti-package"></i> محصولات برتر
                                    </h6>
                                    <ul class="list-group list-group-sm" id="focus-top-products-list"></ul>
                                </div>
                                <div class="col-md-4 mb-3" id="focus-top-warehouses" style="display: none;">
                                    <h6 class="focus-top-title">
                                        <i class="ti-home"></i> انبارهای برتر
                                    </h6>
                                    <ul class="list-group list-group-sm" id="focus-top-warehouses-list"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="analyticsTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="time-tab" data-toggle="tab" href="#time-analytics" 
                           role="tab" aria-controls="time-analytics" aria-selected="true">
                            <i class="ti-time"></i> تحلیل زمانی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="products-tab" data-toggle="tab" href="#products-analytics" 
                           role="tab" aria-controls="products-analytics" aria-selected="false">
                            <i class="ti-package"></i> تحلیل محصولات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers-analytics" 
                           role="tab" aria-controls="customers-analytics" aria-selected="false">
                            <i class="ti-user"></i> تحلیل مشتریان
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial-analytics" 
                           role="tab" aria-controls="financial-analytics" aria-selected="false">
                            <i class="ti-money"></i> تحلیل مالی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="logistics-tab" data-toggle="tab" href="#logistics-analytics" 
                           role="tab" aria-controls="logistics-analytics" aria-selected="false">
                            <i class="ti-truck"></i> تحلیل لجستیک
                        </a>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content mt-4" id="analyticsTabContent">
                    <!-- Time Analytics Tab -->
                    <div class="tab-pane fade show active" id="time-analytics" role="tabpanel" aria-labelledby="time-tab">
                        @include('pages.reports.sales.analytics-time')
                    </div>

                    <!-- Products Analytics Tab -->
                    <div class="tab-pane fade" id="products-analytics" role="tabpanel" aria-labelledby="products-tab">
                        @include('pages.reports.sales.analytics-products')
                    </div>

                    <!-- Customers Analytics Tab -->
                    <div class="tab-pane fade" id="customers-analytics" role="tabpanel" aria-labelledby="customers-tab">
                        @include('pages.reports.sales.analytics-customers')
                    </div>

                    <!-- Financial Analytics Tab -->
                    <div class="tab-pane fade" id="financial-analytics" role="tabpanel" aria-labelledby="financial-tab">
                        @include('pages.reports.sales.analytics-financial')
                    </div>

                    <!-- Logistics Analytics Tab -->
                    <div class="tab-pane fade" id="logistics-analytics" role="tabpanel" aria-labelledby="logistics-tab">
                        @include('pages.reports.sales.analytics-logistics')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Persian Date Picker and Analytics Scripts -->
<script>
// Setup CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Persian months for labels
const persianMonths = [
    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
];

// Ensure numeric values are safe before using them inside charts or UI components
window.toFiniteNumber = window.toFiniteNumber || function(value, fallback = 0) {
    if (value === null || value === undefined || value === '') {
        return fallback;
    }

    let numericValue = value;
    if (typeof numericValue === 'string') {
        numericValue = numericValue.replace(/[,\s]/g, '');
    }

    const parsed = Number(numericValue);
    return Number.isFinite(parsed) ? parsed : fallback;
};

window.toPercentageValue = window.toPercentageValue || function(value, digits = 1) {
    const base = window.toFiniteNumber(value, 0);
    const factor = Math.pow(10, digits);
    return Math.round(base * factor) / factor;
};

window.clampPercentage = window.clampPercentage || function(value, digits = 1) {
    const percentage = window.toPercentageValue(value, digits);
    return Math.min(100, Math.max(0, percentage));
};

// Format numbers with Persian separators
function formatNumber(num) {
    const safeNum = window.toFiniteNumber(num, 0);
    return new Intl.NumberFormat('fa-IR').format(safeNum);
}

function formatCurrency(num) {
    const safeNum = Math.round(window.toFiniteNumber(num, 0));
    return formatNumber(safeNum) + ' ریال';
}

function formatDecimal(num, digits = 2) {
    const safeNum = window.toFiniteNumber(num, 0);
    return new Intl.NumberFormat('fa-IR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: digits
    }).format(safeNum);
}

function formatPercentageValue(value, digits = 1) {
    const safePercentage = window.clampPercentage(value, digits);
    return new Intl.NumberFormat('fa-IR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: digits
    }).format(safePercentage);
}

function getUnitLabel(source, fallback = 'واحد') {
    if (!source || typeof source !== 'object') {
        return fallback;
    }

    const candidateKeys = ['unit_label', 'main_unit', 'unit', 'measurement_unit', 'unitName'];
    for (let i = 0; i < candidateKeys.length; i++) {
        const key = candidateKeys[i];
        if (source[key] && typeof source[key] === 'string' && source[key].trim() !== '') {
            return source[key].trim();
        }
    }

    return fallback;
}

function getDominantUnitLabel(items = [], fallback = 'واحد') {
    if (!Array.isArray(items) || items.length === 0) {
        return fallback;
    }

    const unitCounter = items.reduce((acc, item) => {
        const unit = getUnitLabel(item, fallback);
        acc[unit] = (acc[unit] || 0) + window.toFiniteNumber(item.total_quantity || item.quantity || 1, 0);
        return acc;
    }, {});

    const dominantUnit = Object.entries(unitCounter)
        .sort((a, b) => b[1] - a[1])
        .map(entry => entry[0])[0];

    return dominantUnit || fallback;
}

function formatQuantityValue(quantity, unitLabel = 'واحد', digits = 2) {
    const value = window.toFiniteNumber(quantity, 0);
    if (value === 0) {
        return `0 ${unitLabel}`;
    }
    return `${formatDecimal(value, digits)} ${unitLabel}`;
}

let focusDataLoading = false;
let currentFilterMode = 'year_month';
let hasTriggeredUpdate = false;
const analyticsCsrfToken = $('meta[name="csrf-token"]').attr('content');

function getActiveFocus() {
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();

    // Only one focus at a time
    const focusSelections = [
        product ? 'product' : null,
        customer ? 'customer' : null,
        warehouse ? 'warehouse' : null
    ].filter(Boolean);

    if (focusSelections.length !== 1) {
        return null;
    }

    const focusType = focusSelections[0];
    const valueMap = {
        product: product,
        customer: customer,
        warehouse: warehouse
    };

    return {
        type: focusType,
        value: valueMap[focusType]
    };
}

function truncateLabel(label, maxLength = 40) {
    if (!label || typeof label !== 'string') {
        return '-';
    }
    return label.length > maxLength ? label.substring(0, maxLength) + '…' : label;
}

function getFocusTypeLabel(focusType) {
    switch (focusType) {
        case 'product':
            return 'محصول';
        case 'customer':
            return 'مشتری';
        case 'warehouse':
            return 'انبار';
        default:
            return 'تمرکز';
    }
}

function showFocusLoading() {
    $('#focus-summary-wrapper').show();
    $('#focus-summary-loading').show();
    $('#focus-summary-content').css('opacity', 0.2);
}

function hideFocusSummary() {
    $('#focus-summary-wrapper').hide();
    $('#focus-summary-loading').hide();
    $('#focus-summary-content').css('opacity', 1);
    $('#focus-top-customers-list, #focus-top-products-list, #focus-top-warehouses-list').empty();
    $('#focus-top-customers, #focus-top-products, #focus-top-warehouses').hide();
    applyFocusBadges(null);
}

function applyFocusBadges(focus, summaryData = null) {
    $('#filter_product, #filter_customer, #filter_warehouse, #filter_customer_category').removeClass('focus-highlight');
    $('#focus-active-badge').hide();

    if (!focus) {
        return;
    }

    const focusLabel = getFocusTypeLabel(focus.type);
    const summaryLabel = summaryData && summaryData.label ? summaryData.label : focus.value;
    const displayLabel = truncateLabel(summaryLabel, 45);

    $('#focus-active-text').text(`${focusLabel}: ${displayLabel}`);
    $('#focus-active-badge').fadeIn(150);

    if (focus.type === 'product') {
        $('#filter_product').addClass('focus-highlight');
    } else if (focus.type === 'customer') {
        $('#filter_customer').addClass('focus-highlight');
        $('#filter_customer_category').addClass('focus-highlight');
    } else if (focus.type === 'warehouse') {
        $('#filter_warehouse').addClass('focus-highlight');
    }
}

function renderFocusList(containerSelector, list, options = {}) {
    const $container = $(containerSelector);
    if (!$container.length) {
        return;
    }

    const $list = $container.find('ul');
    if (!list || !Array.isArray(list) || list.length === 0) {
        $list.empty();
        $container.hide();
        return;
    }

    $list.empty();
    list.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 45);
        const totalAmount = formatCurrency(item.total_amount || 0);
        const quantityKey = options.quantityKey;
        const quantityLabel = quantityKey && item[quantityKey] ? formatNumber(Math.round(item[quantityKey])) + ' واحد' : '';
        const countLabel = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';

        const badgeHtml = `<span class="badge badge-light mr-2">${index + 1}</span>`;
        const metaHtml = [quantityLabel, countLabel].filter(Boolean).map(text => `<small class="text-muted d-block">${text}</small>`).join('');

        const itemHtml = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    ${badgeHtml}
                    <div class="d-flex flex-column">
                        <span>${label}</span>
                        ${metaHtml}
                    </div>
                </div>
                <span class="text-muted">${totalAmount}</span>
            </li>
        `;
        $list.append(itemHtml);
    });

    $container.fadeIn(150);
}

function renderFocusSummary(summary) {
    if (!summary) {
        hideFocusSummary();
        return;
    }

    $('#focus-summary-wrapper').show();
    $('#focus-summary-loading').hide();
    $('#focus-summary-content').css('opacity', 1);

    const focusType = summary.focus;
    const focusLabel = getFocusTypeLabel(focusType);
    const titleMap = {
        product: 'تحلیل اختصاصی محصول',
        customer: 'تحلیل اختصاصی مشتری',
        warehouse: 'تحلیل اختصاصی انبار'
    };

    $('#focus-summary-title').text(titleMap[focusType] || 'تحلیل تمرکز فعال');
    $('#focus-summary-subtitle').text(`نمایش اطلاعات برای ${focusLabel}: ${truncateLabel(summary.label || '-', 60)}`);

    $('#focus-total-amount').text(formatCurrency(summary.total_amount));
    $('#focus-order-count').text(formatNumber(summary.order_count || 0));
    $('#focus-total-quantity').text(formatNumber(Math.round(summary.total_quantity || 0)));

    $('#focus-unique-customers').text(formatNumber(summary.unique_customers || 0));
    $('#focus-unique-products').text(formatNumber(summary.unique_products || 0));
    $('#focus-unique-warehouses').text(formatNumber(summary.unique_warehouses || 0));

    const dateRange = summary.first_sale_date && summary.last_sale_date
        ? `${summary.first_sale_date} تا ${summary.last_sale_date}`
        : '-';
    $('#focus-date-range').text(dateRange);

    const topEntities = summary.top_entities || {};
    renderFocusList('#focus-top-customers', topEntities.customers || []);
    renderFocusList('#focus-top-products', topEntities.products || [], { quantityKey: 'total_quantity' });
    renderFocusList('#focus-top-warehouses', topEntities.warehouses || []);

    applyFocusBadges({ type: focusType, value: summary.label }, summary);
}

function loadFocusData(filters) {
    const focus = getActiveFocus();
    window.currentFocus = null;
    window.focusSummary = null;
    window.focusDistributions = null;

    if (!focus) {
        hideFocusSummary();
        return $.Deferred().resolve().promise();
    }

    const payload = Object.assign({}, filters, { focus: focus.type });
    payload[focus.type] = focus.value;

    showFocusLoading();
    focusDataLoading = true;

    const summaryRequest = $.ajax({
        url: '/report/sales/analytics/focus-summary',
        method: 'POST',
        headers: analyticsCsrfToken ? { 'X-CSRF-TOKEN': analyticsCsrfToken } : {},
        data: payload
    });

    const distributionRequest = $.ajax({
        url: '/report/sales/analytics/focus-distributions',
        method: 'POST',
        headers: analyticsCsrfToken ? { 'X-CSRF-TOKEN': analyticsCsrfToken } : {},
        data: payload
    });

    return $.when(summaryRequest, distributionRequest)
        .done(function(summaryResponse, distributionResponse) {
            const summaryData = summaryResponse[0];
            const distributionData = distributionResponse[0];

            if (summaryData && summaryData.success) {
                window.currentFocus = focus;
                window.focusSummary = summaryData.data;
                renderFocusSummary(summaryData.data);
                if (summaryData.data && summaryData.data.focus === 'product') {
                    window.productsChartsLoaded = false;
                    loadProductsAnalytics();
                    window.financialChartsLoaded = false;
                    loadFinancialAnalytics();
                } else if (summaryData.data && summaryData.data.focus === 'customer') {
                    window.customersChartsLoaded = false;
                    loadCustomersAnalytics();
                    window.financialChartsLoaded = false;
                    loadFinancialAnalytics();
                } else if (summaryData.data && summaryData.data.focus === 'warehouse') {
                    window.logisticsChartsLoaded = false;
                    loadLogisticsAnalytics();
                }
            } else {
                hideFocusSummary();
            }

            if (distributionData && distributionData.success) {
                window.focusDistributions = distributionData.data;
            }
        })
        .fail(function(xhr) {
            console.error('Focus data error:', xhr);
            hideFocusSummary();
        })
        .always(function() {
            focusDataLoading = false;
        });
}

// Tab switching is now handled in $(document).ready() above

// Update charts button
$('#update-analytics').on('click', function() {
    // Find which tab pane is active
    let activeTabId = '';
    $('.tab-pane').each(function() {
        if ($(this).hasClass('show') && $(this).hasClass('active')) {
            activeTabId = '#' + $(this).attr('id');
        }
    });
    
    const year = $('#analytics_year').val();
    const fromMonth = $('#analytics_from_month').val();
    const toMonth = $('#analytics_to_month').val();
    const fromDate = $('#analytics_from_date').val();
    const toDate = $('#analytics_to_date').val();
    
    console.log('Update button clicked');
    console.log('Active tab ID:', activeTabId);
    console.log('Filter - Year:', year, 'From Month:', fromMonth, 'To Month:', toMonth);
    console.log('Filter - From date:', fromDate, 'To date:', toDate);
    
    // Show filter status
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();
    const filters = getFilterDates();
    
    let filterText = '';
    let filterParts = [];
    
    if (currentFilterMode === 'exact') {
        // Using exact dates
        if (fromDate && toDate) {
            filterParts.push(`${fromDate} تا ${toDate}`);
        } else if (fromDate) {
            filterParts.push(`از ${fromDate}`);
        } else if (toDate) {
            filterParts.push(`تا ${toDate}`);
        }
    } else if (year) {
        // Using year/month
        let yearText = `سال ${year}`;
        if (fromMonth && toMonth) {
            if (fromMonth === toMonth) {
                yearText += ` - ${persianMonths[fromMonth - 1]}`;
            } else {
                yearText += ` - ${persianMonths[fromMonth - 1]} تا ${persianMonths[toMonth - 1]}`;
            }
        } else if (fromMonth) {
            yearText += ` - از ${persianMonths[fromMonth - 1]}`;
        } else if (toMonth) {
            yearText += ` - تا ${persianMonths[toMonth - 1]}`;
        }
        filterParts.push(yearText);
    }
    
    // Add additional filter info
    if (customer) filterParts.push(`مشتری: ${customer}`);
    if (warehouse) filterParts.push(`انبار: ${warehouse}`);
    if (product) filterParts.push(`محصول: ${product}`);
    const customerCategoryValue = $('#filter_customer_category').val();
    if (customerCategoryValue) {
        const categoryLabel = $('#filter_customer_category option:selected').text();
        filterParts.push(`دسته‌بندی: ${categoryLabel}`);
    }
    
    if (filterParts.length > 0) {
        filterText = filterParts.join(' | ');
        $('#filter-text').text(filterText);
        $('#filter-status').show();
    } else {
        $('#filter-status').hide();
    }
    
    $(this).prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...');
    
    // Reset loaded flags to force reload with new filters
    window.timeChartsLoaded = false;
    window.productsChartsLoaded = false;
    window.customersChartsLoaded = false;
    window.financialChartsLoaded = false;
    window.logisticsChartsLoaded = false;
    
    // Hide all tab contents until load completes
    $('.tab-pane .card, .tab-pane .chart-container, #focus-summary-wrapper').hide();
    $('#focus-summary-wrapper').hide();

    // Reload data for active tab
    const loaders = {
        '#time-analytics': loadTimeAnalytics,
        '#products-analytics': loadProductsAnalytics,
        '#customers-analytics': loadCustomersAnalytics,
        '#financial-analytics': loadFinancialAnalytics,
        '#logistics-analytics': loadLogisticsAnalytics
    };
    const loader = loaders[activeTabId] || loadTimeAnalytics;
    const activateContent = () => {
        hasTriggeredUpdate = true;
        $('.tab-pane.show.active .card, .tab-pane.show.active .chart-container').fadeIn(150);
    };
    try {
        const loaderResult = loader();
        if (loaderResult && typeof loaderResult.always === 'function') {
            loaderResult.always(activateContent);
        } else if (loaderResult && typeof loaderResult.finally === 'function') {
            loaderResult.finally(activateContent);
        } else {
            activateContent();
        }
    } catch (error) {
        console.error('Loader execution error:', error);
        activateContent();
    }
    
    setTimeout(() => {
        $(this).prop('disabled', false).html('<i class="ti-reload"></i> به‌روزرسانی نمودارها');
    }, 1000);

    // Detect and load focus-specific data
    loadFocusData({
        ...filters,
        product: product,
        customer: customer,
        warehouse: warehouse,
        customer_category: $('#filter_customer_category').val()
    });
});

// Get current Persian year (approximate calculation)
function getCurrentPersianYear() {
    // Simple conversion: Gregorian year - 621 or 622
    const now = new Date();
    const gregorianYear = now.getFullYear();
    const month = now.getMonth() + 1;
    
    // If before March 21, use year-622, else year-621
    if (month < 3 || (month === 3 && now.getDate() < 21)) {
        return gregorianYear - 622;
    } else {
        return gregorianYear - 621;
    }
}

function updateFilterMode(mode, options = {}) {
    if (!['year_month', 'exact'].includes(mode)) {
        console.warn('Unknown filter mode:', mode);
        return;
    }

    const { preserveYearValues = false, preserveDateValues = false } = options;
    currentFilterMode = mode;

    const $yearInputs = $('#analytics_year, #analytics_from_month, #analytics_to_month');
    const $dateInputs = $('#analytics_from_date, #analytics_to_date');
    const $yearGroups = $('.filter-year-month-group');
    const $dateGroups = $('.filter-exact-group');
    const $yearModeBtn = $('#filter-mode-year-month');
    const $exactModeBtn = $('#filter-mode-exact');

    if (mode === 'year_month') {
        $yearInputs.prop('disabled', false);
        $dateInputs.prop('disabled', true);
        if (!preserveDateValues) {
            $dateInputs.val('');
        }

        $yearGroups.removeClass('filter-group-disabled');
        $dateGroups.addClass('filter-group-disabled');

        $yearModeBtn.addClass('btn-primary active').removeClass('btn-outline-primary');
        $exactModeBtn.removeClass('btn-primary active').addClass('btn-outline-primary');
    } else {
        $yearInputs.prop('disabled', true);
        $dateInputs.prop('disabled', false);
        if (!preserveYearValues) {
            $('#analytics_year').val('');
            $('#analytics_from_month').val('');
            $('#analytics_to_month').val('');
        }

        $yearGroups.addClass('filter-group-disabled');
        $dateGroups.removeClass('filter-group-disabled');

        $exactModeBtn.addClass('btn-primary active').removeClass('btn-outline-primary');
        $yearModeBtn.removeClass('btn-primary active').addClass('btn-outline-primary');
    }
}

// Get all filters including date, product, customer, warehouse
function getFilterDates() {
    const filters = {
        filter_mode: currentFilterMode,
        from_date: '',
        to_date: '',
        year: '',
        from_month: '',
        to_month: ''
    };

    if (currentFilterMode === 'exact') {
        const fromDate = $('#analytics_from_date').val();
        const toDate = $('#analytics_to_date').val();

        filters.from_date = fromDate || '';
        filters.to_date = toDate || '';

        if (fromDate || toDate) {
            console.log('Using custom exact date range:', fromDate, 'to', toDate);
        }
    } else {
        const year = $('#analytics_year').val();
        const fromMonth = $('#analytics_from_month').val();
        const toMonth = $('#analytics_to_month').val();

        filters.year = year;
        filters.from_month = fromMonth;
        filters.to_month = toMonth;

        if (year) {
            const startMonth = (fromMonth || '01').toString().padStart(2, '0');
            const endMonth = (toMonth || '12').toString().padStart(2, '0');
            const endDay = (endMonth === '12') ? '29' : (parseInt(endMonth, 10) <= 6 ? '31' : '30');

            filters.from_date = `${year}/${startMonth}/01`;
            filters.to_date = `${year}/${endMonth}/${endDay}`;

            console.log('Using year/month filters:', filters.from_date, 'to', filters.to_date);
        }
    }

    // Additional filters
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();
    const customerCategory = $('#filter_customer_category').val();
    if (product) {
        filters.product = product;
        console.log('Filter - Product:', product);
    }
    if (customer) {
        filters.customer = customer;
        console.log('Filter - Customer:', customer);
    }
    if (warehouse) {
        filters.warehouse = warehouse;
        console.log('Filter - Warehouse:', warehouse);
    }
    if (customerCategory) {
        filters.customer_category = customerCategory;
        console.log('Filter - Customer Category:', customerCategory);
    }
    
    return filters;
}

$('#filter-mode-year-month').on('click', function() {
    updateFilterMode('year_month');
});

$('#filter-mode-exact').on('click', function() {
    updateFilterMode('exact', { preserveDateValues: true });
});

// Set current year button
$('#set-current-year').on('click', function() {
    updateFilterMode('year_month');
    const currentYear = getCurrentPersianYear();
    
    // Clear custom date inputs
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Set year/month selectors
    $('#analytics_year').val(currentYear);
    $('#analytics_from_month').val('1');
    $('#analytics_to_month').val('12');
    
    console.log('Set to current year:', currentYear);
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// Set last year button
$('#set-last-year').on('click', function() {
    updateFilterMode('year_month');
    const lastYear = getCurrentPersianYear() - 1;
    
    // Clear custom date inputs
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Set year/month selectors
    $('#analytics_year').val(lastYear);
    $('#analytics_from_month').val('1');
    $('#analytics_to_month').val('12');
    
    console.log('Set to last year:', lastYear);
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// Set current quarter button
$('#set-current-quarter').on('click', function() {
    updateFilterMode('year_month');
    const currentYear = getCurrentPersianYear();
    const now = new Date();
    const month = now.getMonth() + 1;
    
    // Simple approximation of Persian month
    let persianMonth = month - 3;
    if (persianMonth <= 0) persianMonth += 12;
    
    // Determine quarter
    let fromMonth, toMonth;
    if (persianMonth <= 3) {
        fromMonth = 1; toMonth = 3; // بهار
    } else if (persianMonth <= 6) {
        fromMonth = 4; toMonth = 6; // تابستان
    } else if (persianMonth <= 9) {
        fromMonth = 7; toMonth = 9; // پاییز
    } else {
        fromMonth = 10; toMonth = 12; // زمستان
    }
    
    // Clear custom date inputs
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Set year/month selectors
    $('#analytics_year').val(currentYear);
    $('#analytics_from_month').val(fromMonth);
    $('#analytics_to_month').val(toMonth);
    
    console.log('Set to current quarter:', currentYear, 'from month', fromMonth, 'to', toMonth);
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// Clear filters button
$('#clear-filters').on('click', function() {
    updateFilterMode('year_month');
    // Clear all filters
    $('#analytics_year').val('');
    $('#analytics_from_month').val('');
    $('#analytics_to_month').val('');
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    $('#filter_customer').val('').trigger('change');
    $('#filter_product').val('').trigger('change');
    $('#filter_warehouse').val('').trigger('change');
    $('#filter-status').hide();
    
    console.log('All filters cleared');
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// When year/month changes, clear exact dates and highlight
$('#analytics_year, #analytics_from_month, #analytics_to_month').on('change', function() {
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Visual feedback
    $('#analytics_year, #analytics_from_month, #analytics_to_month').parent().addClass('border-primary');
    setTimeout(() => {
        $('#analytics_year, #analytics_from_month, #analytics_to_month').parent().removeClass('border-primary');
    }, 1000);
    
    console.log('Year/Month changed, custom dates cleared');
});

// When exact dates change, clear year/month and highlight
$('#analytics_from_date, #analytics_to_date').on('change', function() {
    if ($('#analytics_from_date').val() || $('#analytics_to_date').val()) {
        $('#analytics_year').val('');
        $('#analytics_from_month').val('');
        $('#analytics_to_month').val('');
        
        // Visual feedback
        $('.persian-date-input').parent().addClass('border-success');
        setTimeout(() => {
            $('.persian-date-input').parent().removeClass('border-success');
        }, 1000);
        
        console.log('Custom dates changed, year/month cleared');
    }
});

// Toggle help text
$('#toggle-help-text').on('click', function(e) {
    e.preventDefault();
    $('#filter-help').slideToggle();
});

// Refresh filters button - reload ComboBoxes based on date range
$('#refresh-filters').on('click', function() {
    const $btn = $(this);
    const originalHtml = $btn.html();
    
    $btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
    
    console.log('Refreshing filter lists based on date range...');
    
    loadUniqueFilterValues(true);
    
    setTimeout(function() {
        $btn.prop('disabled', false).html(originalHtml);
    }, 1500);
});

// When year changes, optionally reload ComboBoxes
$('#analytics_year, #analytics_from_month, #analytics_to_month').on('change', function() {
    // Optional: auto-refresh lists when year/month changes
    // Uncomment the next line to enable:
    // loadUniqueFilterValues(true);
});

// Test data button
$('#test-data-btn').on('click', function() {
    $(this).prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
    
    $.ajax({
        url: '/report/sales/analytics/test-data',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('=== داده‌های آزمایشی ===');
            console.log('تعداد کل رکوردها:', response.total_records);
            console.log('بازه تاریخی:', response.date_range);
            console.log('توزیع ماهانه:', response.monthly_distribution);
            console.log('نمونه داده‌ها:', response.sample_data);
            
            let message = `✅ تعداد کل رکوردها: ${response.total_records}\n`;
            message += `📅 بازه تاریخی: ${response.date_range.min} تا ${response.date_range.max}\n`;
            message += `📊 تعداد ماه‌های دارای داده: ${response.monthly_distribution.length}`;
            
            alert(message);
            $('#test-data-btn').prop('disabled', false).html('<i class="ti-bug"></i> Debug');
        },
        error: function(xhr, status, error) {
            console.error('خطا در تست داده‌ها:', error);
            alert('خطا در بارگذاری داده‌های تست. لطفاً Console را بررسی کنید.');
            $('#test-data-btn').prop('disabled', false).html('<i class="ti-bug"></i> Debug');
        }
    });
});

// Load customer categories for category filter
function loadCustomerCategories() {
    $.ajax({
        url: '/report/sales/analytics/customer-categories',
        method: 'GET',
        dataType: 'json'
    }).done(function(response) {
        const categories = response && response.success ? (response.data || []) : [];
        populateCategorySelect('#filter_customer_category', categories);
    }).fail(function(xhr) {
        console.error('Error loading customer categories', xhr);
        populateCategorySelect('#filter_customer_category', []);
    });
}

function populateCategorySelect(selector, categories) {
    const $select = $(selector);
    const previousValue = $select.val() || '';

    $select.empty();
    $select.append('<option value="">تمام دسته‌ها</option>');

    categories.forEach(function(category) {
        if (!category || !category.slug) {
            return;
        }
        const count = typeof category.client_count === 'number' ? category.client_count : '';
        const label = count !== '' ? `${category.name} (${count})` : category.name;
        const selected = category.slug === previousValue ? 'selected' : '';
        $select.append(`<option value="${category.slug}" ${selected}>${label}</option>`);
    });

    $('#customer-category-count').text(`(${categories.length})`);

    if (previousValue && !$select.find(`option[value="${previousValue}"]`).length) {
        $select.val('');
    }
}

// Load unique values for filters (with optional date filter)
function loadUniqueFilterValues(useDateFilter = false) {
    console.log('Loading unique filter values...');
    
    let filterData = {};
    if (useDateFilter) {
        const dates = getFilterDates();
        if (dates.from_date) filterData.from_date = dates.from_date;
        if (dates.to_date) filterData.to_date = dates.to_date;
    }
    
    const customerCategory = $('#filter_customer_category').val();

    const currentValues = {
        product: $('#filter_product').val(),
        customer: $('#filter_customer').val(),
        warehouse: $('#filter_warehouse').val()
    };
    
    if (customerCategory) {
        filterData.customer_category = customerCategory;
    }

    const requests = [
        { selector: '#filter_customer', column: 'customer_name', current: currentValues.customer, countId: 'customer-count' },
        { selector: '#filter_warehouse', column: 'warehouse', current: currentValues.warehouse, countId: 'warehouse-count' },
        { selector: '#filter_product', column: 'product_name', current: currentValues.product, countId: 'product-count' }
    ];
    
    requests.forEach(cfg => {
        $.ajax({
            url: '/report/sales/analytics/unique-values',
            method: 'POST',
            headers: analyticsCsrfToken ? { 'X-CSRF-TOKEN': analyticsCsrfToken } : {},
            data: { column: cfg.column, ...filterData },
            dataType: 'json'
        }).done(function(response) {
            const values = response.success ? (response.data || []) : [];
            populateSelect(cfg.selector, values, 'انتخاب کنید', cfg.current);
            $(`#${cfg.countId}`).text(`(${values.length})`);
        }).fail(function(xhr) {
            console.error('Error loading filter', cfg.column, xhr);
            populateSelect(cfg.selector, [], 'انتخاب کنید');
            $(`#${cfg.countId}`).text('(0)');
        });
    });
}

// Populate select with options
function populateSelect(selector, data, placeholder, selectedValue = '') {
    const $select = $(selector);
    const desiredValue = selectedValue || '';

    $select.empty();

    // Add placeholder option
    $select.append(`<option value="">${placeholder}</option>`);

    if (data && data.length > 0) {
        data.forEach(function(item) {
            if (item && item.trim() !== '') {
                const isSelected = item === desiredValue ? 'selected' : '';
                const displayName = item.length > 50 ? item.substring(0, 50) + '...' : item;
                $select.append(`<option value="${item}" ${isSelected}>${displayName}</option>`);
            }
        });

        const countLabel = selector.replace('#filter_', '') + '-count';
        $(`#${countLabel}`).text(`(${data.length})`);
    } else {
        $select.append('<option value="" disabled>داده‌ای یافت نشد</option>');
        const countLabel = selector.replace('#filter_', '') + '-count';
        $(`#${countLabel}`).text('(0)');
    }

    $select.val(desiredValue || '');

    console.log(`${selector} populated with ${(data && data.length) || 0} items`);
}

// Initialize on page load
$(document).ready(function() {
    console.log('Analytics page loaded');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Chart.js available:', typeof Chart !== 'undefined');
    console.log('Bootstrap available:', typeof $.fn.tab !== 'undefined');
    
    // Check if canvas elements exist
    console.log('Canvas #monthlyTrendChart exists:', $('#monthlyTrendChart').length > 0);
    console.log('Canvas #seasonalChart exists:', $('#seasonalChart').length > 0);
    
    // Set default to current year
    const currentYear = getCurrentPersianYear();
    $('#analytics_year').val('');
    $('#analytics_from_month').val('');
    $('#analytics_to_month').val('');
    
    updateFilterMode('year_month', { preserveYearValues: true, preserveDateValues: true });
    
    $('.tab-pane .card, .tab-pane .chart-container, #focus-summary-wrapper').hide();
    console.log('Default year placeholder active. Current year is:', currentYear);
    
    loadCustomerCategories();
    loadUniqueFilterValues(true);

    $('#filter_customer_category').on('change', function() {
        $('#filter_customer').val('');
        loadUniqueFilterValues(true);
        applyFocusBadges(getActiveFocus());
    });

    // Initialize Bootstrap tabs manually with click handler
    $('#analyticsTab a[data-toggle="tab"]').each(function() {
        $(this).on('click', function(e) {
            e.preventDefault();
            
            const targetId = $(this).attr('href');
            console.log('Tab clicked:', targetId);
            
            // Remove active from all tabs and panes
            $('#analyticsTab .nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');
            
            // Add active to clicked tab
            $(this).addClass('active');
            
            // Show corresponding tab pane
            $(targetId).addClass('show active');
            
            $('.tab-pane.show.active .card, .tab-pane.show.active .chart-container').toggle(hasTriggeredUpdate);

            // Load data for the specific tab if not loaded
            setTimeout(() => {
                if (!hasTriggeredUpdate) {
                    return;
                }
                if (targetId === '#time-analytics' && !window.timeChartsLoaded) {
                    console.log('Loading time analytics...');
                    loadTimeAnalytics();
                } else if (targetId === '#products-analytics' && !window.productsChartsLoaded) {
                    console.log('Loading products analytics...');
                    loadProductsAnalytics();
                } else if (targetId === '#customers-analytics' && !window.customersChartsLoaded) {
                    console.log('Loading customers analytics...');
                    loadCustomersAnalytics();
                } else if (targetId === '#financial-analytics' && !window.financialChartsLoaded) {
                    console.log('Loading financial analytics...');
                    loadFinancialAnalytics();
                } else if (targetId === '#logistics-analytics' && !window.logisticsChartsLoaded) {
                    console.log('Loading logistics analytics...');
                    loadLogisticsAnalytics();
                } else {
                    $('.tab-pane.show.active .card, .tab-pane.show.active .chart-container').fadeIn(150);
                }
                
                // Trigger resize to redraw charts
                window.dispatchEvent(new Event('resize'));
            }, 100);
        });
    });
    
    console.log('Tabs initialized with manual click handlers');
    
    // Initialize Persian date pickers
    initPersianDatePickers();
    
    // Load unique values for ComboBoxes
    loadUniqueFilterValues();

    // Highlight focus selections when filters change
    $('#filter_product, #filter_customer, #filter_warehouse').on('change', function() {
        applyFocusBadges(getActiveFocus());
    });
    applyFocusBadges(getActiveFocus());
    
    // Warehouse change event - Reload products based on selected warehouse
    $('#filter_warehouse').on('change', function() {
        const selectedWarehouse = $(this).val();
        const previousProduct = $('#filter_product').val();
        console.log('Warehouse changed:', selectedWarehouse);
        
        loadUniqueFilterValues(true);
        if (selectedWarehouse) {
            $('#filter_product').addClass('border-primary');
            setTimeout(() => $('#filter_product').removeClass('border-primary'), 2000);
        }
    });
    
    // Load first tab data after a short delay
    setTimeout(function() {
        console.log('Loading initial analytics data...');
        loadTimeAnalytics();
    }, 500);
});

// Persian Date Picker Initialization
function initPersianDatePickers() {
    $('.persian-date-input').each(function() {
        const inputId = $(this).attr('id');
        
        // Calendar button click
        $('button[data-target="' + inputId + '"]').on('click', function(e) {
            e.preventDefault();
            openPersianDatePicker(inputId);
        });
        
        // Input click
        $('#' + inputId).on('click', function() {
            openPersianDatePicker(inputId);
        });
        
        // Input change event
        $('#' + inputId).on('change', function() {
            console.log('Date changed:', inputId, $(this).val());
        });
        
        // Enter key press
        $('#' + inputId).on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#update-analytics').click();
            }
        });
    });
}

let currentPickerInput = null;
let selectedDate = { year: 1403, month: 1, day: 1 };

function openPersianDatePicker(inputId) {
    currentPickerInput = inputId;
    
    console.log('Opening date picker for:', inputId);
    
    // Get current date from input or use today (1403/01/01 as default)
    const currentValue = $('#' + inputId).val();
    if (currentValue && currentValue.trim() !== '') {
        const parts = currentValue.split('/');
        if (parts.length === 3) {
            selectedDate = {
                year: parseInt(parts[0]),
                month: parseInt(parts[1]),
                day: parseInt(parts[2])
            };
        }
    } else {
        // Set default date
        selectedDate = {
            year: 1403,
            month: 1,
            day: 1
        };
    }
    
    // Remove existing picker
    $('.persian-datepicker-popup').remove();
    
    // Create and show picker
    const popup = createPersianDatePickerPopup(selectedDate);
    $('body').append(popup);
    
    // Position popup near input
    const input = $('#' + inputId);
    const offset = input.offset();
    popup.css({
        top: offset.top + input.outerHeight() + 5,
        left: offset.left,
        zIndex: 9999
    });
    
    // Add click outside to close
    setTimeout(() => {
        $(document).on('click.persian-picker', function(e) {
            if (!$(e.target).closest('.persian-datepicker-popup').length && 
                !$(e.target).closest('#' + inputId).length &&
                !$(e.target).closest('button[data-target="' + inputId + '"]').length) {
                closePersianDatePicker();
            }
        });
    }, 100);
}

function createPersianDatePickerPopup(currentDate) {
    const { year, month, day } = currentDate;
    
    const popup = $(`
        <div class="persian-datepicker-popup" style="position: absolute; z-index: 9999; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 300px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h6 style="margin: 0; font-weight: bold;">انتخاب تاریخ</h6>
                <button type="button" onclick="closePersianDatePicker()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">سال</label>
                    <select id="picker-year" class="form-select form-select-sm" onchange="updatePersianCalendar()">
                        ${Array.from({length: 20}, (_, i) => 1395 + i).map(y => 
                            `<option value="${y}" ${y === year ? 'selected' : ''}>${y}</option>`
                        ).join('')}
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">ماه</label>
                    <select id="picker-month" class="form-select form-select-sm" onchange="updatePersianCalendar()">
                        ${persianMonths.map((m, i) => 
                            `<option value="${i + 1}" ${(i + 1) === month ? 'selected' : ''}>${m}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; margin-bottom: 15px;">
                ${['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].map(d => 
                    `<div style="font-weight: bold; font-size: 12px; padding: 5px;">${d}</div>`
                ).join('')}
                ${generateCalendarDays(year, month, day)}
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closePersianDatePicker()">لغو</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="confirmPersianDate()">تأیید</button>
            </div>
        </div>
    `);
    
    return popup;
}

function generateCalendarDays(year, month, selectedDay) {
    const daysInMonth = month <= 6 ? 31 : (month <= 11 ? 30 : (isLeapYear(year) ? 30 : 29));
    let html = '';
    
    for (let i = 1; i <= daysInMonth; i++) {
        const isSelected = i === selectedDay;
        html += `
            <div class="calendar-day ${isSelected ? 'selected' : ''}" 
                 data-day="${i}" 
                 onclick="selectDay(${i})"
                 style="
                     padding: 8px; 
                     cursor: pointer; 
                     border-radius: 4px;
                     font-size: 13px;
                     ${isSelected ? 'background: #5969ff; color: white; font-weight: bold;' : 'background: #f8f9fa;'}
                 ">
                ${i}
            </div>
        `;
    }
    
    return html;
}

function isLeapYear(year) {
    const breaks = [1, 5, 9, 13, 17, 22, 26, 30];
    const cycle = year % 33;
    return breaks.includes(cycle);
}

window.selectDay = function(day) {
    selectedDate.day = day;
    $('.calendar-day').removeClass('selected').css({'background': '#f8f9fa', 'color': 'inherit', 'font-weight': 'normal'});
    $(`.calendar-day[data-day="${day}"]`).addClass('selected').css({'background': '#5969ff', 'color': 'white', 'font-weight': 'bold'});
};

window.updatePersianCalendar = function() {
    selectedDate.year = parseInt($('#picker-year').val());
    selectedDate.month = parseInt($('#picker-month').val());
    
    console.log('Calendar updated to:', selectedDate.year, '/', selectedDate.month);
    
    // Regenerate calendar
    const calendarHtml = generateCalendarDays(selectedDate.year, selectedDate.month, selectedDate.day);
    $('.persian-datepicker-popup > div:nth-child(3)').html(`
        ${['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].map(d => 
            `<div style="font-weight: bold; font-size: 12px; padding: 5px;">${d}</div>`
        ).join('')}
        ${calendarHtml}
    `);
};

window.confirmPersianDate = function() {
    if (currentPickerInput) {
        const dateString = `${selectedDate.year}/${String(selectedDate.month).padStart(2, '0')}/${String(selectedDate.day).padStart(2, '0')}`;
        $('#' + currentPickerInput).val(dateString);
        console.log('Date set for', currentPickerInput, ':', dateString);
        
        // Trigger change event
        $('#' + currentPickerInput).trigger('change');
    }
    closePersianDatePicker();
};

window.closePersianDatePicker = function() {
    $('.persian-datepicker-popup').remove();
    $(document).off('click.persian-picker');
};
</script>

<style>
.nav-tabs .nav-link {
    color: #495057;
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}

.nav-tabs .nav-link.active {
    color: #5969ff;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    font-weight: 600;
}

.chart-container {
    position: relative;
    height: 400px;
    margin-bottom: 20px;
}

.chart-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.stat-card {
    border-left: 4px solid #5969ff;
    transition: all 0.3s ease;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.persian-datepicker-popup:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

.calendar-day:hover:not(.selected) {
    background: #e7e9fd !important;
}

.border-primary {
    border: 2px solid #5969ff !important;
    transition: border 0.3s ease;
}

.border-success {
    border: 2px solid #24d2b5 !important;
    transition: border 0.3s ease;
}

.form-control:disabled, .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 0.7;
}

.btn-group-sm>.btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.alert ul {
    padding-right: 20px;
    margin-bottom: 0;
}

.input-group-sm > .form-control {
    font-size: 0.875rem;
}

.focus-summary-card {
    border: 1px solid #ffe0a3;
    border-radius: 12px;
    box-shadow: 0 8px 18px rgba(255, 193, 7, 0.08);
}

.focus-summary-card .card-header {
    background: #fff7e6;
    border-bottom: 1px solid #ffe0a3;
}

.focus-summary-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 0 0 12px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.focus-summary-metrics .focus-metric-card {
    background: #f8f9fc;
    border-radius: 10px;
    padding: 14px 16px;
    border: 1px solid #e6ecff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
}

.focus-summary-metrics .focus-metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(89, 105, 255, 0.12);
}

.focus-metric-label {
    font-size: 13px;
    color: #6c757d;
}

.focus-metric-value {
    font-weight: 600;
    color: #2b2f5c;
}

.focus-meta-card {
    background: #ffffff;
    border: 1px dashed #d8dffb;
    border-radius: 10px;
    padding: 12px;
    height: 100%;
}

.focus-meta-label {
    font-size: 12px;
    color: #7a7f9a;
}

.focus-meta-value {
    font-weight: 600;
    color: #5969ff;
    font-size: 15px;
}

.focus-top-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    color: #2b2d5f;
    margin-bottom: 10px;
}

.list-group-sm .list-group-item {
    padding: 0.45rem 0.75rem;
    font-size: 0.88rem;
}

.list-group-sm .list-group-item .badge {
    font-weight: 500;
}

.focus-highlight {
    border: 2px solid #ff9800 !important;
    box-shadow: 0 0 12px rgba(255, 152, 0, 0.25);
}

#product-focus-view .focus-empty-state {
    background: #f7f9ff;
    border: 1px dashed #cbd6ff;
    color: #4f5d91;
}

#product-focus-view .focus-warehouse-list .list-group-item {
    border: none;
    border-bottom: 1px solid #f1f3f9;
}

#product-focus-view .focus-warehouse-list .list-group-item:last-child {
    border-bottom: none;
}

.filter-group-disabled {
    opacity: 0.6;
}
</style>


```

```blade
<!-- Customers Analytics Tab Content -->

<div id="customer-focus-view" style="display: none;">
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="ti-light-bulb mr-2"></i>
        <div>
            <strong>نمای اختصاصی مشتری فعال است.</strong>
            <div class="small text-muted">برای بازگشت به گزارش عمومی، فیلتر مشتری را پاک کنید یا فیلتر متفاوتی انتخاب نمایید.</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center mb-3">
                        <span><i class="ti-package"></i> محصولات خریداری شده توسط مشتری</span>
                        <small class="text-muted" id="customer-focus-products-count"></small>
                    </h5>
                    <div class="chart-container" style="height: 360px;">
                        <canvas id="customerFocusProductsChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="customerFocusProductsEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> برای این مشتری محصولی یافت نشد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-home"></i> انبارهای مورد استفاده
                    </h5>
                    <ul class="list-group list-group-sm focus-warehouse-list" id="customerFocusWarehousesList"></ul>
                    <div class="alert alert-info focus-empty-state mt-3" id="customerFocusWarehousesEmpty" style="display: none;">
                        <i class="ti-info-alt"></i> سفارشات این مشتری به انبار خاصی نسبت داده نشده است.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-pie-chart"></i> وضعیت سفارش‌های این مشتری
                    </h5>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="customerFocusStatusChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="customerFocusStatusEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> سفارش فعالی برای وضعیت نمایش وجود ندارد.
                        </div>
                    </div>
                    <div class="mt-3" id="customerFocusStatusLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-stats-up"></i> شاخص‌های کلیدی مشتری
                    </h5>
                    <ul class="list-group list-group-sm" id="customerFocusStats"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="ti-list"></i> محصولات خریداری شده (جزئیات کامل)
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="customerFocusProductsTable">
                    <thead>
                        <tr>
                            <th width="60">رتبه</th>
                            <th>محصول</th>
                            <th width="120">تعداد سفارش</th>
                            <th width="160">مبلغ کل</th>
                            <th width="160">مقدار کل (واحد)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> در حال آماده‌سازی داده‌های محصول...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="customer-general-view" class="row">
    <!-- Top Customers Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-user"></i> Top 10 مشتریان برتر
                </h5>
                <p class="text-muted">مشتریانی که بیشترین خرید را داشته‌اند</p>
                <div class="chart-container">
                    <canvas id="topCustomersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول مشتریان برتر
                </h5>
                <p class="text-muted">جزئیات خرید مشتریان</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover" id="topCustomersTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>رتبه</th>
                                <th>نام مشتری</th>
                                <th>تعداد سفارش</th>
                                <th>مبلغ کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="ti-stats-up"></i> آمار کلیدی مشتریان
                </h5>
                <div class="mb-4">
                    <p class="text-muted mb-2">تعداد مشتریان</p>
                    <h3 class="mb-0" id="totalCustomers">-</h3>
                </div>
                <div class="mb-4">
                    <p class="text-muted mb-2">مشتری برتر</p>
                    <h6 class="mb-0" id="topCustomer" style="font-size: 13px;">-</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Segmentation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-pie-chart"></i> تحلیل پارتو (قانون 80/20)
                </h5>
                <p class="text-muted">درصد مشتریان که 80% فروش را تشکیل می‌دهند</p>
                <div class="alert alert-info" role="alert" id="paretoAnalysis">
                    <i class="spinner-border spinner-border-sm"></i> در حال محاسبه...
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Percentage Analysis Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="ti-pie-chart"></i> تحلیل درصدی فروش مشتریان (Pareto & ABC Analysis)
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="ti-info-alt"></i> 
                    این تحلیل نشان می‌دهد هر مشتری چند درصد از کل فروش شما را تشکیل می‌دهد و کدام مشتریان کلیدی (A)، مهم (B) یا عادی (C) هستند.
                </p>

                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">کل فروش</h6>
                                <h4 id="percentage-total-sales">-</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">تعداد مشتریان</h6>
                                <h4 id="percentage-total-customers">-</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left: 4px solid #28a745;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Top 10 مشتری</h6>
                                <h4 id="percentage-top10" class="text-success">-</h4>
                                <small class="text-muted">از کل فروش</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left: 4px solid #ff9f40;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">20% مشتریان برتر</h6>
                                <h4 id="percentage-top20" class="text-warning">-</h4>
                                <small class="text-muted">از کل فروش (پارتو)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ABC Classification Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            <h6><i class="ti-star"></i> دسته A - کلیدی</h6>
                            <p class="mb-1"><strong id="class-a-count">-</strong> مشتری (80% اول فروش)</p>
                            <small>مشتریان استراتژیک که باید به آنها توجه ویژه شود</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <h6><i class="ti-bookmark"></i> دسته B - مهم</h6>
                            <p class="mb-1"><strong id="class-b-count">-</strong> مشتری (15% بعدی)</p>
                            <small>مشتریان مهم با پتانسیل رشد</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-info">
                            <h6><i class="ti-user"></i> دسته C - عادی</h6>
                            <p class="mb-1"><strong id="class-c-count">-</strong> مشتری (5% باقیمانده)</p>
                            <small>مشتریان عادی با حجم خرید کم</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-pie-chart"></i> نمودار دایره‌ای سهم مشتریان (Top 15)
                                </h6>
                                <div class="chart-container" style="height: 400px;">
                                    <canvas id="customerPercentagePieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Percentage Table -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-list"></i> جدول تحلیل درصدی (همه مشتریان)
                                </h6>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-hover" id="customerPercentageTable">
                                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                            <tr>
                                                <th width="50">رتبه</th>
                                                <th>مشتری</th>
                                                <th width="100">مبلغ</th>
                                                <th width="80">درصد</th>
                                                <th width="80">تجمعی</th>
                                                <th width="60">دسته</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pareto Chart (Cumulative Line) -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-bar-chart"></i> نمودار پارتو (نمودار تجمعی)
                                </h6>
                                <p class="text-muted">نمایش درصد تجمعی فروش نسبت به تعداد مشتریان</p>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="paretoChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let topCustomersChart = null;
let customerPercentagePieChart = null;
let paretoChart = null;
let customerFocusProductsChart = null;
let customerFocusStatusChart = null;
window.customersChartsLoaded = false;

// Load Customers Analytics Data
function loadCustomersAnalytics() {
    console.log('Loading customers analytics...');

    if (window.currentFocus && window.currentFocus.type === 'customer') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for customer view, delaying render...');
            setTimeout(loadCustomersAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderCustomerFocusView(window.focusSummary, window.focusDistributions);
            window.customersChartsLoaded = true;
            return;
        }
    } else {
        toggleCustomerFocusView(false);
    }

    const dates = getFilterDates();
    
    // Load Top Customers (existing)
    $.ajax({
        url: '/report/sales/analytics/top-customers',
        method: 'POST',
        data: { ...dates, limit: 10 },
        dataType: 'json',
        success: function(response) {
            console.log('Top customers response:', response);
            if (response.success && response.data) {
                toggleCustomerFocusView(false);
                renderTopCustomersChart(response.data);
                updateTopCustomersTable(response.data);
                calculateCustomerStatistics(response.data);
                calculateParetoAnalysis(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading top customers:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    // Load Customer Percentage Analysis (NEW)
    $.ajax({
        url: '/report/sales/analytics/customer-percentage',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Customer percentage response:', response);
            if (response.success && response.data) {
                updatePercentageSummary(response.summary);
                renderCustomerPercentagePieChart(response.data);
                updateCustomerPercentageTable(response.data);
                renderParetoChart(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading customer percentage:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.customersChartsLoaded = true;
}

function toggleCustomerFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#customer-general-view').hide();
        $('#customer-focus-view').fadeIn(150);
    } else {
        $('#customer-focus-view').hide();
        $('#customer-general-view').show();
    }
}

function renderCustomerFocusView(summaryData, distributionsData) {
    if (!summaryData || !distributionsData) {
        toggleCustomerFocusView(false);
        return;
    }

    toggleCustomerFocusView(true);

    const distributions = distributionsData.distributions || {};
    const productDistribution = distributions.products || [];
    const warehouseDistribution = distributions.warehouses || [];
    const statusDistribution = distributions.statuses || [];

    renderCustomerFocusProductsChart(productDistribution);
    renderCustomerFocusWarehouseList(warehouseDistribution);
    renderCustomerFocusStatusChart(statusDistribution);
    renderCustomerFocusStats(summaryData);

    const topProducts = summaryData.top_entities ? summaryData.top_entities.products || [] : [];
    $('#customer-focus-products-count').text(topProducts.length ? `${formatNumber(topProducts.length)} محصول` : '');
    renderCustomerFocusProductsTable(topProducts);
}

function renderCustomerFocusProductsChart(data) {
    const ctx = document.getElementById('customerFocusProductsChart');
    if (!ctx) return;

    if (customerFocusProductsChart) {
        customerFocusProductsChart.destroy();
        customerFocusProductsChart = null;
    }

    if (!data || data.length === 0) {
        $('#customerFocusProductsEmpty').show();
        return;
    }

    $('#customerFocusProductsEmpty').hide();

    const labels = data.map(item => item.label || '-');
    const amounts = data.map(item => item.total_amount || 0);

    customerFocusProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ خرید (ریال)',
                data: amounts,
                backgroundColor: 'rgba(89, 105, 255, 0.75)',
                borderColor: 'rgba(89, 105, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return formatCurrency(context.parsed.x);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: { family: 'Vazir', size: 11 }
                    }
                }
            }
        }
    });
}

function renderCustomerFocusWarehouseList(data) {
    const $list = $('#customerFocusWarehousesList');
    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $('#customerFocusWarehousesEmpty').show();
        return;
    }

    $('#customerFocusWarehousesEmpty').hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = item.label || '-';
        const amount = formatCurrency(item.total_amount || 0);
        const orders = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';
        const quantity = item.total_quantity ? formatNumber(Math.round(item.total_quantity)) + ' واحد' : '';
        const meta = [orders, quantity].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

function renderCustomerFocusStatusChart(data) {
    const ctx = document.getElementById('customerFocusStatusChart');
    if (!ctx) return;

    if (customerFocusStatusChart) {
        customerFocusStatusChart.destroy();
        customerFocusStatusChart = null;
    }

    if (!data || data.length === 0) {
        $('#customerFocusStatusEmpty').show();
        $('#customerFocusStatusLegend').empty();
        return;
    }

    $('#customerFocusStatusEmpty').hide();

    const labels = data.map(item => item.label || 'نامشخص');
    const counts = data.map(item => item.order_count || 0);
    const colors = [
        'rgba(255, 152, 0, 0.8)',
        'rgba(36, 210, 181, 0.8)',
        'rgba(89, 105, 255, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    customerFocusStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return `${context.label || ''}: ${formatNumber(context.parsed)} سفارش`;
                        }
                    }
                }
            }
        }
    });

    let legendHtml = '<div class="row">';
    data.forEach((item, index) => {
        const color = colors[index % colors.length];
        legendHtml += `
            <div class="col-md-6 mb-2 d-flex align-items-center">
                <span class="badge badge-pill mr-2" style="background:${color};">&nbsp;</span>
                <div>
                    <strong>${item.label || 'نامشخص'}</strong>
                    <div class="text-muted small">${formatNumber(item.order_count || 0)} سفارش</div>
                </div>
            </div>
        `;
    });
    legendHtml += '</div>';
    $('#customerFocusStatusLegend').html(legendHtml);
}

function renderCustomerFocusStats(summary) {
    const $list = $('#customerFocusStats');
    if (!$list.length || !summary) return;

    const totalAmount = window.toFiniteNumber(summary.total_amount, 0);
    const orderCount = window.toFiniteNumber(summary.order_count, 0);
    const hasQuantity = summary.total_quantity !== null && summary.total_quantity !== undefined;
    const totalQuantity = hasQuantity ? window.toFiniteNumber(summary.total_quantity, 0) : null;
    const uniqueProducts = window.toFiniteNumber(summary.unique_products, 0);
    const uniqueWarehouses = window.toFiniteNumber(summary.unique_warehouses, 0);

    const stats = [
        { label: 'کل خرید', value: formatCurrency(totalAmount) },
        { label: 'تعداد سفارش', value: formatNumber(orderCount) },
        { label: 'کل مقدار خرید (واحد ثبت‌شده)', value: hasQuantity ? formatNumber(totalQuantity) + ' واحد' : '-' },
        { label: 'محصولات یکتا', value: formatNumber(uniqueProducts) },
        { label: 'انبارهای فعال', value: formatNumber(uniqueWarehouses) }
    ];

    $list.empty();
    stats.forEach(stat => {
        $list.append(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${stat.label}</span>
                <span class="font-weight-bold">${stat.value}</span>
            </li>
        `);
    });
}

function renderCustomerFocusProductsTable(products) {
    const $tbody = $('#customerFocusProductsTable tbody');
    if (!$tbody.length) return;

    if (!products || products.length === 0) {
        $tbody.html(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="ti-alert"></i> محصولی برای این مشتری ثبت نشده است.
                </td>
            </tr>
        `);
        return;
    }

    $tbody.empty();
    products.forEach((product, index) => {
        const hasQuantity = product.total_quantity !== null && product.total_quantity !== undefined;
        const quantityValue = hasQuantity ? window.toFiniteNumber(product.total_quantity, 0) : 0;
        const quantityDisplay = hasQuantity ? formatNumber(quantityValue) : '-';
        const orderCount = window.toFiniteNumber(product.order_count, 0);
        const totalAmount = window.toFiniteNumber(product.total_amount, 0);
        $tbody.append(`
            <tr>
                <td><span class="badge badge-secondary">${index + 1}</span></td>
                <td>${product.label || '-'}</td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatCurrency(totalAmount)}</td>
                <td>${quantityDisplay}</td>
            </tr>
        `);
    });
}

// Render Top Customers Chart
function renderTopCustomersChart(data) {
    const ctx = document.getElementById('topCustomersChart');
    
    if (topCustomersChart) {
        topCustomersChart.destroy();
    }
    
    const labels = data.map(item => {
        const name = item.customer_name || 'نامشخص';
        return name.length > 25 ? name.substring(0, 25) + '...' : name;
    });
    const amounts = data.map(item => window.toFiniteNumber(item.total_amount, 0));
    const counts = data.map(item => window.toFiniteNumber(item.order_count, 0));
    
    topCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'مبلغ خرید (ریال)',
                    data: amounts,
                    backgroundColor: 'rgba(89, 105, 255, 0.7)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 2,
                    yAxisID: 'y'
                },
                {
                    label: 'تعداد سفارش',
                    data: counts,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += formatNumber(window.toFiniteNumber(context.parsed.y, 0));
                            if (context.datasetIndex === 0) {
                                label += ' ریال';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'مبلغ (ریال)',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'تعداد',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Update Top Customers Table
function updateTopCustomersTable(data) {
    const tbody = $('#topCustomersTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item, index) => {
        const badgeClass = index === 0 ? 'bg-warning' : (index === 1 ? 'bg-secondary' : (index === 2 ? 'bg-bronze' : 'bg-light text-dark'));
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const row = `
            <tr>
                <td><span class="badge ${badgeClass}">${index + 1}</span></td>
                <td><strong>${item.customer_name}</strong></td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatNumber(Math.round(totalAmount))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Customer Statistics
function calculateCustomerStatistics(data) {
    if (data.length === 0) return;
    
    // Total customers
    $('#totalCustomers').text(formatNumber(data.length));
    
    // Top customer
    if (data.length > 0) {
        const topCust = data[0].customer_name;
        $('#topCustomer').text(topCust.length > 30 ? topCust.substring(0, 30) + '...' : topCust);
    }
    
}

// Calculate Pareto Analysis (80/20 rule)
function calculateParetoAnalysis(data) {
    if (!Array.isArray(data) || data.length === 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده کافی برای تحلیل موجود نیست');
        return;
    }
    
    const totalSales = data.reduce((sum, item) => {
        return sum + window.toFiniteNumber(item.total_amount, 0);
    }, 0);

    if (totalSales <= 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده معتبر برای تحلیل پارتو یافت نشد');
        return;
    }

    const target80 = totalSales * 0.8;
    
    let cumulativeSum = 0;
    let count80 = 0;
    
    for (let i = 0; i < data.length; i++) {
        cumulativeSum += window.toFiniteNumber(data[i].total_amount, 0);
        count80++;
        if (cumulativeSum >= target80) {
            break;
        }
    }
    
    const percentage = window.toPercentageValue((count80 / data.length) * 100, 1);
    
    $('#paretoAnalysis').html(`
        <i class="ti-info-alt"></i> 
        <strong>${formatNumber(count80)}</strong> مشتری از ${formatNumber(data.length)} (${formatNumber(percentage)}%) مشتری برتر، 
        <strong>80%</strong> از کل فروش را تشکیل می‌دهند.
        <br>
        <small class="text-muted">این تحلیل به شما کمک می‌کند روی مشتریان کلیدی تمرکز کنید.</small>
    `);
}

// ============ NEW FUNCTIONS FOR PERCENTAGE ANALYSIS ============

// Update Percentage Summary Statistics
function updatePercentageSummary(summary) {
    if (!summary) return;
    
    const totalSales = window.toFiniteNumber(summary.total_sales, 0);
    const totalCustomers = window.toFiniteNumber(summary.total_customers, 0);
    const top10Percentage = window.clampPercentage(summary.top_10_percentage, 1);
    const top20Percentage = window.clampPercentage(summary.top_20_percentage, 1);
    const classACount = window.toFiniteNumber(summary.class_a_customers, 0);
    const classBCount = window.toFiniteNumber(summary.class_b_customers, 0);
    const classCCount = window.toFiniteNumber(summary.class_c_customers, 0);
    
    $('#percentage-total-sales').text(formatNumber(Math.round(totalSales)) + ' ریال');
    $('#percentage-total-customers').text(formatNumber(totalCustomers) + ' مشتری');
    $('#percentage-top10').text(formatPercentageValue(top10Percentage, 1) + '٪');
    $('#percentage-top20').text(formatPercentageValue(top20Percentage, 1) + '٪');
    
    $('#class-a-count').text(formatNumber(classACount));
    $('#class-b-count').text(formatNumber(classBCount));
    $('#class-c-count').text(formatNumber(classCCount));
}

// Render Customer Percentage Pie Chart
function renderCustomerPercentagePieChart(data) {
    const ctx = document.getElementById('customerPercentagePieChart');
    
    if (!ctx) {
        console.error('Canvas element not found: customerPercentagePieChart');
        return;
    }
    
    if (customerPercentagePieChart) {
        customerPercentagePieChart.destroy();
    }
    
    if (data.length === 0) {
        return;
    }
    
    // Take top 15 customers for pie chart
    const top15 = data.slice(0, 15);
    const labels = top15.map(item => item.customer_name || 'نامشخص');
    const percentages = top15.map(item => window.clampPercentage(item.percentage, 2));
    
    // Generate colors
    const colors = generatePieColors(top15.length);
    
    customerPercentagePieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'درصد فروش',
                data: percentages,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir',
                            size: 11
                        },
                        padding: 10,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const shortLabel = label.length > 20 ? label.substring(0, 20) + '...' : label;
                                    return {
                                        text: `${shortLabel} (${value}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = window.toFiniteNumber(context.parsed, 0);
                            const dataItem = top15[context.dataIndex];
                            return [
                                `${label}`,
                                `درصد: ${formatPercentageValue(value, 2)}٪`,
                                `مبلغ: ${formatNumber(Math.round(window.toFiniteNumber(dataItem.total_amount, 0)))} ریال`,
                                `تعداد: ${formatNumber(window.toFiniteNumber(dataItem.order_count, 0))} سفارش`
                            ];
                        }
                    }
                }
            }
        }
    });
}

// Update Customer Percentage Table
function updateCustomerPercentageTable(data) {
    const tbody = $('#customerPercentageTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item) => {
        // Classification badge
        let classBadge = '';
        if (item.classification === 'A') {
            classBadge = '<span class="badge badge-success">A</span>';
        } else if (item.classification === 'B') {
            classBadge = '<span class="badge badge-warning">B</span>';
        } else {
            classBadge = '<span class="badge badge-info">C</span>';
        }
        
        // Rank badge
        let rankBadge = `<span class="badge badge-light">${item.rank}</span>`;
        if (item.rank === 1) rankBadge = `<span class="badge badge-warning">🥇 ${item.rank}</span>`;
        else if (item.rank === 2) rankBadge = `<span class="badge badge-secondary">🥈 ${item.rank}</span>`;
        else if (item.rank === 3) rankBadge = `<span class="badge bg-bronze text-white">🥉 ${item.rank}</span>`;
        
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const percentageValue = window.clampPercentage(item.percentage, 2);
        const percentageDisplay = formatPercentageValue(percentageValue, 2);
        const cumulativeValue = window.clampPercentage(item.cumulative_percentage, 2);
        const cumulativeDisplay = formatPercentageValue(cumulativeValue, 2);
        const progressColor = item.classification === 'A' ? 'success' : (item.classification === 'B' ? 'warning' : 'info');
        const progressWidth = percentageValue;
        const row = `
            <tr>
                <td>${rankBadge}</td>
                <td>
                    <strong>${item.customer_name || 'نامشخص'}</strong>
                    <br><small class="text-muted">${formatNumber(orderCount)} سفارش</small>
                </td>
                <td>${formatNumber(Math.round(totalAmount))}</td>
                <td>
                    <div class="customer-percentage-progress">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 14px;">
                                <div class="progress-bar bg-${progressColor}" role="progressbar" 
                                     style="width: ${progressWidth}%;" 
                                     aria-valuenow="${percentageValue}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-light progress-value">${percentageDisplay}٪</span>
                    </div>
                </td>
                <td>
                    <strong class="${cumulativeValue <= 80 ? 'text-success' : ''}">${cumulativeDisplay}٪</strong>
                </td>
                <td>${classBadge}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Render Pareto Chart (Cumulative Line Chart)
function renderParetoChart(data) {
    const ctx = document.getElementById('paretoChart');
    
    if (!ctx) {
        console.error('Canvas element not found: paretoChart');
        return;
    }
    
    if (paretoChart) {
        paretoChart.destroy();
    }
    
    if (data.length === 0) {
        return;
    }
    
    const labels = data.map(item => {
        const name = item.customer_name || 'نامشخص';
        const shortName = name.length > 15 ? name.substring(0, 15) + '...' : name;
        return `${item.rank}. ${shortName}`;
    });
    const percentages = data.map(item => window.clampPercentage(item.percentage, 2));
    const cumulativePercentages = data.map(item => window.clampPercentage(item.cumulative_percentage, 2));
    
    paretoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'درصد فروش',
                    data: percentages,
                    backgroundColor: 'rgba(89, 105, 255, 0.6)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'درصد تجمعی',
                    data: cumulativePercentages,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    yAxisID: 'y',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const dataItem = data[context.dataIndex];
                            if (context.dataset.type === 'bar') {
                                const safeValue = window.toFiniteNumber(context.parsed.y, 0);
                                return `درصد: ${formatPercentageValue(safeValue, 2)}٪`;
                            } else {
                                const safeValue = window.toFiniteNumber(context.parsed.y, 0);
                                return `تجمعی: ${formatPercentageValue(safeValue, 2)}٪`;
                            }
                        }
                    }
                },
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: 80,
                            yMax: 80,
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                content: '80% (قاعده پارتو)',
                                enabled: true,
                                position: 'end'
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'درصد',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 9
                        },
                        maxRotation: 90,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Generate colors for pie chart
function generatePieColors(count) {
    const baseColors = [
        'rgba(89, 105, 255, 0.8)',   // Blue
        'rgba(255, 159, 64, 0.8)',   // Orange
        'rgba(75, 192, 192, 0.8)',   // Teal
        'rgba(255, 99, 132, 0.8)',   // Red
        'rgba(153, 102, 255, 0.8)',  // Purple
        'rgba(255, 205, 86, 0.8)',   // Yellow
        'rgba(54, 162, 235, 0.8)',   // Light Blue
        'rgba(231, 76, 60, 0.8)',    // Red-Orange
        'rgba(46, 204, 113, 0.8)',   // Green
        'rgba(155, 89, 182, 0.8)',   // Purple
        'rgba(52, 152, 219, 0.8)',   // Blue
        'rgba(241, 196, 15, 0.8)',   // Gold
        'rgba(230, 126, 34, 0.8)',   // Orange
        'rgba(26, 188, 156, 0.8)',   // Turquoise
        'rgba(142, 68, 173, 0.8)'    // Dark Purple
    ];
    
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(baseColors[i % baseColors.length]);
    }
    return colors;
}
</script>

<style>
.customer-percentage-progress {
    display: flex;
    align-items: center;
}

.customer-percentage-progress .progress {
    flex-grow: 1;
    margin-bottom: 0;
    background-color: #f1f3f5;
}

.customer-percentage-progress .progress-value {
    margin-right: 0.75rem;
    margin-left: 0.75rem;
    font-weight: 600;
    color: #343a40;
    min-width: 64px;
    text-align: center;
}

.bg-bronze {
    background-color: #CD7F32;
    color: white;
}
</style>


```

```blade
<!-- Time Analytics Tab Content -->
<div class="row">
    <!-- Monthly Trend Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-stats-up"></i> روند فروش ماهانه
                </h5>
                <p class="text-muted">نمایش تعداد و مبلغ فروش به تفکیک ماه</p>
                <div class="chart-container">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Seasonal Analysis Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-pie-chart"></i> تحلیل فصلی
                </h5>
                <p class="text-muted">سهم هر فصل از کل فروش</p>
                <div class="chart-container">
                    <canvas id="seasonalChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Table -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> آمار ماهانه
                </h5>
                <p class="text-muted">جزئیات فروش به تفکیک ماه</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover" id="monthlyStatsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>ماه</th>
                                <th>تعداد</th>
                                <th>مبلغ کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="col-12">
        <div class="row" id="timeStatsCards">
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">بهترین ماه</p>
                                <h4 class="mb-0" id="bestMonth">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-crown" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">کل فروش</p>
                                <h4 class="mb-0" id="totalSales">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-wallet" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">بهترین فصل</p>
                                <h4 class="mb-0" id="bestSeason">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-flag" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let monthlyTrendChart = null;
let seasonalChart = null;
window.timeChartsLoaded = false;

// Load Time Analytics Data
function loadTimeAnalytics() {
    console.log('Loading time analytics...');
    const dates = getFilterDates();
    
    // Load Monthly Trend
    $.ajax({
        url: '/report/sales/analytics/monthly-trend',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Monthly trend response:', response);
            if (response.success && response.data) {
                renderMonthlyTrendChart(response.data);
                updateMonthlyStatsTable(response.data);
                calculateTimeStatistics(response.data);
            } else {
                console.error('Invalid response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading monthly trend:', status, error);
            console.error('Response:', xhr.responseText);
            alert('خطا در بارگذاری داده‌ها. لطفاً کنسول مرورگر را بررسی کنید.');
        }
    });
    
    // Load Seasonal Analysis
    $.ajax({
        url: '/report/sales/analytics/seasonal-analysis',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Seasonal analysis response:', response);
            if (response.success && response.data) {
                renderSeasonalChart(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading seasonal analysis:', status, error);
        }
    });
    
    window.timeChartsLoaded = true;
}

// Render Monthly Trend Chart
function renderMonthlyTrendChart(data) {
    console.log('Rendering monthly trend chart with data:', data);
    
    const ctx = document.getElementById('monthlyTrendChart');
    
    if (!ctx) {
        console.error('Canvas element #monthlyTrendChart not found!');
        return;
    }
    
    if (monthlyTrendChart) {
        monthlyTrendChart.destroy();
    }
    
    // Check if data is empty
    if (!data || data.length === 0) {
        console.warn('No data for monthly trend chart');
        $('#monthlyTrendChart').parent().html('<div class="alert alert-warning text-center"><i class="ti-info-alt"></i> داده‌ای برای نمایش یافت نشد. لطفاً بازه تاریخی دیگری انتخاب کنید.</div>');
        return;
    }
    
    // Create labels with year and month (e.g., "شهریور 1403")
    const labels = data.map(item => {
        const monthName = persianMonths[item.month - 1];
        // If multiple years exist, show year in label
        const uniqueYears = [...new Set(data.map(d => d.year))];
        if (uniqueYears.length > 1) {
            return `${monthName} ${item.year}`;
        } else {
            return monthName;
        }
    });
    
    const counts = data.map(item => window.toFiniteNumber(item.count, 0));
    const amounts = data.map(item => window.toFiniteNumber(item.total_amount, 0));
    
    console.log('Chart labels:', labels);
    console.log('Chart counts:', counts);
    console.log('Chart amounts:', amounts);
    
    monthlyTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'تعداد فروش',
                    data: counts,
                    borderColor: '#5969ff',
                    backgroundColor: 'rgba(89, 105, 255, 0.1)',
                    yAxisID: 'y',
                    tension: 0.4
                },
                {
                    label: 'مبلغ فروش (ریال)',
                    data: amounts,
                    borderColor: '#24d2b5',
                    backgroundColor: 'rgba(36, 210, 181, 0.1)',
                    yAxisID: 'y1',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const value = window.toFiniteNumber(context.parsed.y, 0);
                            label += formatNumber(value);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'تعداد',
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'مبلغ (ریال)',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

// Render Seasonal Chart
function renderSeasonalChart(data) {
    const ctx = document.getElementById('seasonalChart');
    
    if (seasonalChart) {
        seasonalChart.destroy();
    }
    
    // Check if data is empty
    if (!data || data.length === 0) {
        console.warn('No data for seasonal chart');
        return;
    }
    
    const labels = data.map(item => item.name);
    const totals = data.map(item => window.toFiniteNumber(item.total, 0));
    
    seasonalChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: totals,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.8)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir',
                            size: 12
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const parsedValue = window.toFiniteNumber(context.parsed, 0);
                            const value = formatNumber(parsedValue);
                            const total = context.dataset.data.reduce((sum, item) => sum + window.toFiniteNumber(item, 0), 0);
                            const percentage = total ? formatPercentageValue((parsedValue / total) * 100, 1) : formatPercentageValue(0, 1);
                            return `${label}: ${value} ریال (${percentage}٪)`;
                        }
                    }
                }
            }
        }
    });
    
    // Update best season
    const bestSeasonData = data.reduce((max, item) => item.total > max.total ? item : max, data[0]);
    $('#bestSeason').text(bestSeasonData.name);
}

// Update Monthly Stats Table
function updateMonthlyStatsTable(data) {
    const tbody = $('#monthlyStatsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    // Check if multiple years exist
    const uniqueYears = [...new Set(data.map(d => d.year))];
    const showYear = uniqueYears.length > 1;
    
    data.forEach(item => {
        const monthName = showYear ? `${persianMonths[item.month - 1]} ${item.year}` : persianMonths[item.month - 1];
        
        const row = `
            <tr>
                <td><strong>${monthName}</strong></td>
                <td>${formatNumber(window.toFiniteNumber(item.count, 0))}</td>
                <td>${formatNumber(Math.round(window.toFiniteNumber(item.total_amount, 0)))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Time Statistics
function calculateTimeStatistics(data) {
    if (data.length === 0) return;
    
    // Best month
    const bestMonth = data.reduce((max, item) => item.total_amount > max.total_amount ? item : max, data[0]);
    const uniqueYears = [...new Set(data.map(d => d.year))];
    const monthLabel = uniqueYears.length > 1 ? 
        `${persianMonths[bestMonth.month - 1]} ${bestMonth.year}` : 
        persianMonths[bestMonth.month - 1];
    $('#bestMonth').text(monthLabel);
    
    // Total sales
    const totalSales = data.reduce((sum, item) => sum + window.toFiniteNumber(item.total_amount, 0), 0);
    $('#totalSales').text(formatNumber(Math.round(totalSales)) + ' ریال');
    
}
</script>


```

```blade
*** Begin Patch
*** Update File: application/resources/views/pages/reports/sales/analytics-logistics.blade.php
@@
-    data.forEach((item, index) => {
+    data.forEach((item, index) => {
         const label = truncateLabel(item.label || '-', 50);
         const amount = formatCurrency(item.total_amount || 0);
-        const quantity = item.total_quantity ? formatNumber(Math.round(item.total_quantity)) + ' واحد' : '';
+        const unitLabel = getUnitLabel(item);
+        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
+        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
         const orders = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';
         const meta = [quantity, orders].filter(Boolean).join(' • ');
 
         const li = `
             <li class="list-group-item d-flex justify-content-between align-items-center">
@@
-function renderDeliveryRateChart(deliveryRate) {
+function renderDeliveryRateChart(deliveryRate) {
     const ctx = document.getElementById('deliveryRateChart');
     
     if (deliveryRateChart) {
         deliveryRateChart.destroy();
     }
     
-    const remaining = 100 - deliveryRate;
+    const safeRate = window.clampPercentage(deliveryRate, 2);
+    const remaining = Math.max(0, 100 - safeRate);
     
     deliveryRateChart = new Chart(ctx, {
         type: 'doughnut',
         data: {
             labels: ['تحویل شده', 'باقیمانده'],
             datasets: [{
-                data: [deliveryRate, remaining],
+                data: [safeRate, remaining],
                 backgroundColor: [
                     'rgba(36, 210, 181, 0.8)',
                     'rgba(255, 180, 77, 0.3)'
                 ],
@@
                 tooltip: {
                     rtl: true,
                     callbacks: {
                         label: function(context) {
-                            return context.label + ': ' + context.parsed.toFixed(1) + '%';
+                            const value = window.clampPercentage(context.parsed, 1);
+                            return context.label + ': ' + formatPercentageValue(value, 1) + '٪';
                         }
                     }
                 }
             }
         }
     });
     
-    $('#deliveryRatePercent').text(deliveryRate.toFixed(1) + '%');
+    $('#deliveryRatePercent').text(formatPercentageValue(safeRate, 1) + '٪');
 }
 
 // Render Pending Products Chart
 function renderPendingProductsChart(data) {
     const ctx = document.getElementById('pendingProductsChart');
@@
-    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
-    const quantities = data.map(item => item.pending_quantity);
+    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
+    const quantities = data.map(item => window.toFiniteNumber(item.pending_quantity, 0));
+    const unitLabels = data.map(item => getUnitLabel(item));
     
     pendingProductsChart = new Chart(ctx, {
         type: 'bar',
         data: {
             labels: labels,
             datasets: [{
                 label: 'مقدار معلق',
                 data: quantities,
                 backgroundColor: 'rgba(255, 180, 77, 0.7)',
                 borderColor: 'rgba(255, 180, 77, 1)',
-                borderWidth: 2
+                borderWidth: 2,
+                unitLabels: unitLabels
             }]
         },
         options: {
             indexAxis: 'y',
             responsive: true,
@@
                 tooltip: {
                     rtl: true,
                     callbacks: {
                         label: function(context) {
-                            return 'مقدار: ' + formatNumber(context.parsed.x);
+                            const unitLabel = context.dataset.unitLabels ? context.dataset.unitLabels[context.dataIndex] : getUnitLabel({});
+                            return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
                         }
                     }
                 }
             },
             scales: {
@@
 function updatePendingProductsTable(data) {
     const tbody = $('#pendingProductsTable tbody');
     tbody.empty();
     
     if (data.length === 0) {
         tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
         return;
     }
     
     data.forEach(item => {
+        const unitLabel = getUnitLabel(item);
         const row = `
             <tr>
                 <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
-                <td class="text-warning"><strong>${formatNumber(item.pending_quantity)}</strong></td>
-                <td>${formatNumber(item.order_count)}</td>
+                <td class="text-warning"><strong>${formatQuantityValue(item.pending_quantity, unitLabel)}</strong></td>
+                <td>${formatNumber(window.toFiniteNumber(item.order_count, 0))}</td>
             </tr>
         `;
         tbody.append(row);
     });
 }
 
 // Update Delivery Statistics
 function updateDeliveryStatistics(stats, deliveryRate) {
-    $('#deliveredQty').text(formatNumber(Math.round(stats.total_issued || 0)));
-    $('#pendingQty').text(formatNumber(Math.round(stats.total_remaining || 0)));
-    $('#totalOrders').text(formatNumber(stats.total_orders || 0));
+    const unitLabel = getUnitLabel(stats);
+    $('#deliveredQty').text(formatQuantityValue(stats.total_issued || 0, unitLabel));
+    $('#pendingQty').text(formatQuantityValue(stats.total_remaining || 0, unitLabel));
+    $('#totalOrders').text(formatNumber(window.toFiniteNumber(stats.total_orders, 0)));
 }
 
 // Evaluate Delivery Performance
 function evaluateDeliveryPerformance(deliveryRate, stats) {
     let alertClass = 'alert-success';
@@
-    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
+    const safeRate = window.clampPercentage(deliveryRate, 2);
+    const totalOrders = window.toFiniteNumber(stats.total_orders, 0);
+    const deliveredOrders = totalOrders * (safeRate / 100);
+    const pendingOrders = totalOrders - deliveredOrders;
+    const unitLabel = getUnitLabel(stats);
     
     $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
         .addClass(alertClass)
         .html(`
             <h5><i class="${icon}"></i> ${message}</h5>
             <p class="mb-2">${recommendation}</p>
             <hr>
             <div class="row">
                 <div class="col-md-4">
                     <small class="text-muted">سفارشات تحویل شده:</small>
-                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
+                    <br><strong>${formatNumber(Math.round(deliveredOrders))}</strong>
                 </div>
                 <div class="col-md-4">
                     <small class="text-muted">سفارشات معلق:</small>
-                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
+                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                 </div>
                 <div class="col-md-4">
-                    <small class="text-muted">نرخ تحویل:</small>
-                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
+                    <small class="text-muted">نرخ تحویل:</small>
+                    <br><strong>${formatPercentageValue(safeRate, 1)}٪</strong>
                 </div>
             </div>
             <div class="row mt-3">
                 <div class="col-md-6">
                     <small class="text-muted">کل مقدار صادر شده:</small>
-                    <br><strong>${formatNumber(Math.round(stats.total_issued || 0))}</strong>
+                    <br><strong>${formatQuantityValue(stats.total_issued || 0, unitLabel)}</strong>
                 </div>
                 <div class="col-md-6">
                     <small class="text-muted">کل مقدار باقی‌مانده:</small>
-                    <br><strong class="text-warning">${formatNumber(Math.round(stats.total_remaining || 0))}</strong>
+                    <br><strong class="text-warning">${formatQuantityValue(stats.total_remaining || 0, unitLabel)}</strong>
                 </div>
             </div>
         `);
 }
*** End Patch
```

```blade
<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const orders = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const remaining = 100 - deliveryRate;
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [deliveryRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(deliveryRate.toFixed(1) + '%');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => item.pending_quantity);
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return 'مقدار: ' + formatNumber(context.parsed.x);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatNumber(item.pending_quantity)}</strong></td>
                <td>${formatNumber(item.order_count)}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    $('#deliveredQty').text(formatNumber(Math.round(stats.total_issued || 0)));
    $('#pendingQty').text(formatNumber(Math.round(stats.total_remaining || 0)));
    $('#totalOrders').text(formatNumber(stats.total_orders || 0));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    if (deliveryRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (deliveryRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (deliveryRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
                </div>
            </div>
        `);
}
</script>


```

```blade
<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const orders = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const safeRate = window.clampPercentage(deliveryRate, 2);
    const remaining = Math.max(0, 100 - safeRate);
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [safeRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const safeValue = window.clampPercentage(context.parsed, 1);
                            return context.label + ': ' + formatPercentageValue(safeValue, 1) + '٪';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(formatPercentageValue(safeRate, 1) + '٪');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => item.pending_quantity);
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return 'مقدار: ' + formatNumber(context.parsed.x);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatNumber(item.pending_quantity)}</strong></td>
                <td>${formatNumber(item.order_count)}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    $('#deliveredQty').text(formatNumber(Math.round(stats.total_issued || 0)));
    $('#pendingQty').text(formatNumber(Math.round(stats.total_remaining || 0)));
    $('#totalOrders').text(formatNumber(stats.total_orders || 0));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    if (deliveryRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (deliveryRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (deliveryRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
                </div>
            </div>
        `);
}
</script>


```

```blade
<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const orders = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const safeRate = window.clampPercentage(deliveryRate, 2);
    const remaining = Math.max(0, 100 - safeRate);
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [safeRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const safeValue = window.clampPercentage(context.parsed, 1);
                            return context.label + ': ' + formatPercentageValue(safeValue, 1) + '٪';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(formatPercentageValue(safeRate, 1) + '٪');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => window.toFiniteNumber(item.pending_quantity, 0));
    const unitLabels = data.map(item => getUnitLabel(item));
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
                borderWidth: 2,
                unitLabels: unitLabels
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const unitLabel = context.dataset.unitLabels ? context.dataset.unitLabels[context.dataIndex] : getUnitLabel({});
                            return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatNumber(item.pending_quantity)}</strong></td>
                <td>${formatNumber(item.order_count)}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    $('#deliveredQty').text(formatNumber(Math.round(stats.total_issued || 0)));
    $('#pendingQty').text(formatNumber(Math.round(stats.total_remaining || 0)));
    $('#totalOrders').text(formatNumber(stats.total_orders || 0));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    if (deliveryRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (deliveryRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (deliveryRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
                </div>
            </div>
        `);
}
</script>


```

```blade
<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const orders = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const safeRate = window.clampPercentage(deliveryRate, 2);
    const remaining = Math.max(0, 100 - safeRate);
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [safeRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const safeValue = window.clampPercentage(context.parsed, 1);
                            return context.label + ': ' + formatPercentageValue(safeValue, 1) + '٪';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(formatPercentageValue(safeRate, 1) + '٪');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => window.toFiniteNumber(item.pending_quantity, 0));
    const unitLabels = data.map(item => getUnitLabel(item));
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
                borderWidth: 2,
                unitLabels: unitLabels
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const unitLabel = context.dataset.unitLabels ? context.dataset.unitLabels[context.dataIndex] : getUnitLabel({});
                            return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const unitLabel = getUnitLabel(item);
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatQuantityValue(item.pending_quantity, unitLabel)}</strong></td>
                <td>${formatNumber(window.toFiniteNumber(item.order_count, 0))}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    $('#deliveredQty').text(formatNumber(Math.round(stats.total_issued || 0)));
    $('#pendingQty').text(formatNumber(Math.round(stats.total_remaining || 0)));
    $('#totalOrders').text(formatNumber(stats.total_orders || 0));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    if (deliveryRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (deliveryRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (deliveryRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
                </div>
            </div>
        `);
}
</script>


```

```blade
<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const orders = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const safeRate = window.clampPercentage(deliveryRate, 2);
    const remaining = Math.max(0, 100 - safeRate);
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [safeRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const safeValue = window.clampPercentage(context.parsed, 1);
                            return context.label + ': ' + formatPercentageValue(safeValue, 1) + '٪';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(formatPercentageValue(safeRate, 1) + '٪');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => window.toFiniteNumber(item.pending_quantity, 0));
    const unitLabels = data.map(item => getUnitLabel(item));
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
                borderWidth: 2,
                unitLabels: unitLabels
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const unitLabel = context.dataset.unitLabels ? context.dataset.unitLabels[context.dataIndex] : getUnitLabel({});
                            return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const unitLabel = getUnitLabel(item);
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatQuantityValue(item.pending_quantity, unitLabel)}</strong></td>
                <td>${formatNumber(window.toFiniteNumber(item.order_count, 0))}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    const unitLabel = getUnitLabel(stats);
    $('#deliveredQty').text(formatQuantityValue(stats.total_issued || 0, unitLabel));
    $('#pendingQty').text(formatQuantityValue(stats.total_remaining || 0, unitLabel));
    $('#totalOrders').text(formatNumber(window.toFiniteNumber(stats.total_orders, 0)));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    if (deliveryRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (deliveryRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (deliveryRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
                </div>
            </div>
        `);
}
</script>


```

```blade
<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const orders = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const safeRate = window.clampPercentage(deliveryRate, 2);
    const remaining = Math.max(0, 100 - safeRate);
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [safeRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const safeValue = window.clampPercentage(context.parsed, 1);
                            return context.label + ': ' + formatPercentageValue(safeValue, 1) + '٪';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(formatPercentageValue(safeRate, 1) + '٪');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => window.toFiniteNumber(item.pending_quantity, 0));
    const unitLabels = data.map(item => getUnitLabel(item));
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
                borderWidth: 2,
                unitLabels: unitLabels
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const unitLabel = context.dataset.unitLabels ? context.dataset.unitLabels[context.dataIndex] : getUnitLabel({});
                            return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const unitLabel = getUnitLabel(item);
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatQuantityValue(item.pending_quantity, unitLabel)}</strong></td>
                <td>${formatNumber(window.toFiniteNumber(item.order_count, 0))}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    const unitLabel = getUnitLabel(stats);
    $('#deliveredQty').text(formatQuantityValue(stats.total_issued || 0, unitLabel));
    $('#pendingQty').text(formatQuantityValue(stats.total_remaining || 0, unitLabel));
    $('#totalOrders').text(formatNumber(window.toFiniteNumber(stats.total_orders, 0)));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    const safeRate = window.clampPercentage(deliveryRate, 2);
    const totalOrders = window.toFiniteNumber(stats.total_orders, 0);
    const deliveredOrders = totalOrders * (safeRate / 100);
    const pendingOrders = Math.max(0, totalOrders - deliveredOrders);
    const unitLabel = getUnitLabel(stats);
    const deliveredQuantity = window.toFiniteNumber(stats.total_issued, 0);
    const pendingQuantity = window.toFiniteNumber(stats.total_remaining, 0);
    
    if (safeRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (safeRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (safeRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(deliveredOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${formatPercentageValue(safeRate, 1)}٪</strong>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <small class="text-muted">کل مقدار صادر شده:</small>
                    <br><strong>${formatQuantityValue(deliveredQuantity, unitLabel)}</strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">کل مقدار باقی‌مانده:</small>
                    <br><strong class="text-warning">${formatQuantityValue(pendingQuantity, unitLabel)}</strong>
                </div>
            </div>
        `);
}
</script>


```

```blade
<!-- Products Analytics Tab Content -->

<div id="product-focus-view" style="display: none;">
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="ti-light-bulb mr-2"></i>
        <div>
            <strong>نمای اختصاصی محصول فعال است.</strong>
            <div class="small text-muted">برای بازگشت به گزارش عمومی، فیلتر محصول را پاک کنید یا فیلتر دیگری را انتخاب نمایید.</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center mb-3">
                        <span><i class="ti-user"></i> مشتریان برتر این محصول</span>
                        <small class="text-muted" id="product-focus-customers-count"></small>
                    </h5>
                    <div class="chart-container" style="height: 360px;">
                        <canvas id="productFocusCustomersChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="productFocusCustomersEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> داده‌ای برای نمایش مشتریان برتر یافت نشد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-home"></i> توزیع فروش بین انبارها
                    </h5>
                    <ul class="list-group list-group-sm focus-warehouse-list" id="productFocusWarehousesList"></ul>
                    <div class="alert alert-info focus-empty-state mt-3" id="productFocusWarehousesEmpty" style="display: none;">
                        <i class="ti-info-alt"></i> این محصول در انباری ثبت نشده است.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-pie-chart"></i> وضعیت سفارش‌های این محصول
                    </h5>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="productFocusStatusChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="productFocusStatusEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> هیچ سفارشی برای نمایش وضعیت وجود ندارد.
                        </div>
                    </div>
                    <div class="mt-3" id="productFocusStatusLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-bar-chart"></i> شاخص‌های کلیدی محصول
                    </h5>
                    <ul class="list-group list-group-sm" id="productFocusStats"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="ti-list"></i> مشتریان برتر (جزئیات کامل)
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="productFocusCustomersTable">
                    <thead>
                        <tr>
                            <th width="60">رتبه</th>
                            <th>مشتری</th>
                            <th width="120">تعداد سفارش</th>
                            <th width="160">مبلغ کل</th>
                            <th width="120">مقدار کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> در حال آماده‌سازی داده‌های مشتریان...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="product-general-view" class="row">
    <!-- Top 10 Products Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-medall"></i> Top 10 محصولات پرفروش
                </h5>
                <p class="text-muted">محصولاتی که بیشترین فروش را داشته‌اند</p>
                <div class="chart-container">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Statistics -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات پرفروش (جدول)</h5>
                <p class="text-muted">جزئیات فروش محصولات</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="topProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>رتبه</th>
                                <th>محصول</th>
                                <th>تعداد سفارش</th>
                                <th>مقدار (واحد)</th>
                                <th>مبلغ کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Categories Analysis -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-layout-grid2"></i> سهم محصولات از کل فروش
                </h5>
                <p class="text-muted">توزیع فروش بین محصولات</p>
                <div class="chart-container">
                    <canvas id="productsDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Performance Cards -->
    <div class="col-12">
        <div class="row" id="productStatsCards">
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">تعداد محصولات</p>
                                <h4 class="mb-0" id="totalProducts">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-package" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">محصول برتر</p>
                                <h6 class="mb-0" id="topProduct" style="font-size: 14px;">-</h6>
                            </div>
                            <div class="text-warning">
                                <i class="ti-crown" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع مقدار (واحد ثبت‌شده)</p>
                                <h4 class="mb-0" id="totalQuantity">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let topProductsChart = null;
let productsDistributionChart = null;
let productFocusCustomersChart = null;
let productFocusStatusChart = null;
window.productsChartsLoaded = false;

// Load Products Analytics Data
function loadProductsAnalytics() {
    console.log('Loading products analytics...');

    // Handle focus mode (single product selected)
    if (window.currentFocus && window.currentFocus.type === 'product') {
        if (focusDataLoading) {
            console.log('Focus data is still loading, waiting before rendering product focus view...');
            setTimeout(loadProductsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderProductFocusView(window.focusSummary, window.focusDistributions);
            window.productsChartsLoaded = true;
            return;
        }
    } else {
        toggleProductFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/top-products',
        method: 'POST',
        data: { ...dates, limit: 10 },
        dataType: 'json',
        success: function(response) {
            console.log('Top products response:', response);
            if (response.success && response.data) {
                toggleProductFocusView(false);
                renderTopProductsChart(response.data);
                renderProductsDistributionChart(response.data);
                updateTopProductsTable(response.data);
                calculateProductStatistics(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading top products:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.productsChartsLoaded = true;
}

function toggleProductFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#product-general-view').hide();
        $('#product-focus-view').fadeIn(150);
    } else {
        $('#product-focus-view').hide();
        $('#product-general-view').show();
    }
}

function renderProductFocusView(summaryData, distributionsData) {
    if (!summaryData || !distributionsData) {
        toggleProductFocusView(false);
        return;
    }

    toggleProductFocusView(true);

    const distributions = distributionsData.distributions || {};
    const customerDistribution = distributions.customers || [];
    const warehouseDistribution = distributions.warehouses || [];
    const statusDistribution = distributions.statuses || [];

    renderProductFocusCustomersChart(customerDistribution);
    renderProductFocusWarehouseList(warehouseDistribution);
    renderProductFocusStatusChart(statusDistribution);
    renderProductFocusStats(summaryData);

    const topCustomers = summaryData.top_entities ? summaryData.top_entities.customers || [] : [];
    $('#product-focus-customers-count').text(topCustomers.length ? `${formatNumber(topCustomers.length)} مشتری` : '');
    renderProductFocusCustomersTable(topCustomers);
}

function renderProductFocusCustomersChart(data) {
    const ctx = document.getElementById('productFocusCustomersChart');
    if (!ctx) return;

    if (productFocusCustomersChart) {
        productFocusCustomersChart.destroy();
        productFocusCustomersChart = null;
    }

    if (!data || data.length === 0) {
        $('#productFocusCustomersEmpty').show();
        return;
    }

    $('#productFocusCustomersEmpty').hide();

    const labels = data.map(item => item.label || '-');
    const amounts = data.map(item => window.toFiniteNumber(item.total_amount, 0));

    productFocusCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ فروش (ریال)',
                data: amounts,
                backgroundColor: 'rgba(255, 152, 0, 0.7)',
                borderColor: 'rgba(255, 152, 0, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return formatCurrency(window.toFiniteNumber(context.parsed.x, 0));
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: { family: 'Vazir', size: 11 }
                    }
                }
            }
        }
    });
}

function renderProductFocusWarehouseList(data) {
    const $list = $('#productFocusWarehousesList');
    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $('#productFocusWarehousesEmpty').show();
        return;
    }

    $('#productFocusWarehousesEmpty').hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = item.label || '-';
        const amount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const orders = orderCount ? formatNumber(orderCount) + ' سفارش' : '';
        const unitLabel = getUnitLabel(item);
        const hasQuantity = item.total_quantity !== null && item.total_quantity !== undefined;
        const quantityValue = hasQuantity ? window.toFiniteNumber(item.total_quantity, 0) : 0;
        const quantity = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const meta = [orders, quantity].filter(Boolean).join(' • ');

        const listItem = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(listItem);
    });
}

function renderProductFocusStatusChart(data) {
    const ctx = document.getElementById('productFocusStatusChart');
    if (!ctx) return;

    if (productFocusStatusChart) {
        productFocusStatusChart.destroy();
        productFocusStatusChart = null;
    }

    if (!data || data.length === 0) {
        $('#productFocusStatusEmpty').show();
        $('#productFocusStatusLegend').empty();
        return;
    }

    $('#productFocusStatusEmpty').hide();

    const labels = data.map(item => item.label || 'نامشخص');
    const counts = data.map(item => window.toFiniteNumber(item.order_count, 0));
    const bgColors = [
        'rgba(89, 105, 255, 0.8)',
        'rgba(255, 152, 0, 0.8)',
        'rgba(36, 210, 181, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    productFocusStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: bgColors.slice(0, labels.length),
                borderColor: bgColors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = formatNumber(window.toFiniteNumber(context.parsed, 0));
                            return `${label}: ${value} سفارش`;
                        }
                    }
                }
            }
        }
    });

    let legendHtml = '<div class="row">';
    data.forEach((item, index) => {
        const color = bgColors[index % bgColors.length];
        legendHtml += `
            <div class="col-md-6 mb-2 d-flex align-items-center">
                <span class="badge badge-pill mr-2" style="background:${color};">&nbsp;</span>
                <div>
                    <strong>${item.label || 'نامشخص'}</strong>
                    <div class="text-muted small">${formatNumber(window.toFiniteNumber(item.order_count, 0))} سفارش</div>
                </div>
            </div>
        `;
    });
    legendHtml += '</div>';
    $('#productFocusStatusLegend').html(legendHtml);
}

function renderProductFocusStats(summary) {
    const $list = $('#productFocusStats');
    if (!$list.length || !summary) return;

    const totalAmount = window.toFiniteNumber(summary.total_amount, 0);
    const orderCount = window.toFiniteNumber(summary.order_count, 0);
    const hasQuantity = summary.total_quantity !== null && summary.total_quantity !== undefined;
    const totalQuantity = hasQuantity ? window.toFiniteNumber(summary.total_quantity, 0) : null;
    const uniqueCustomers = window.toFiniteNumber(summary.unique_customers, 0);
    const uniqueWarehouses = window.toFiniteNumber(summary.unique_warehouses, 0);
    const unitLabel = getUnitLabel(summary);

    const stats = [
        { label: 'کل فروش', value: formatCurrency(totalAmount) },
        { label: 'تعداد سفارش', value: formatNumber(orderCount) },
        { label: 'کل مقدار فروش (واحد ثبت‌شده)', value: hasQuantity && totalQuantity !== null ? formatQuantityValue(totalQuantity, unitLabel) : '-' },
        { label: 'مشتریان یکتا', value: formatNumber(uniqueCustomers) },
        { label: 'انبارهای فعال', value: formatNumber(uniqueWarehouses) }
    ];

    $list.empty();
    stats.forEach(stat => {
        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${stat.label}</span>
                <span class="font-weight-bold">${stat.value}</span>
            </li>
        `;
        $list.append(li);
    });
}

function renderProductFocusCustomersTable(customers) {
    const $tbody = $('#productFocusCustomersTable tbody');
    if (!$tbody.length) return;

    if (!customers || customers.length === 0) {
        $tbody.html(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="ti-alert"></i> اطلاعاتی برای نمایش مشتریان برتر وجود ندارد.
                </td>
            </tr>
        `);
        return;
    }

    $tbody.empty();
    customers.forEach((customer, index) => {
        const orderCount = window.toFiniteNumber(customer.order_count, 0);
        const totalAmount = window.toFiniteNumber(customer.total_amount, 0);
        const hasQuantity = customer.total_quantity !== null && customer.total_quantity !== undefined;
        const quantityValue = hasQuantity ? window.toFiniteNumber(customer.total_quantity, 0) : 0;
        const unitLabel = getUnitLabel(customer);
        const quantity = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '-';
        const row = `
            <tr>
                <td><span class="badge badge-secondary">${index + 1}</span></td>
                <td>${customer.label || '-'}</td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatCurrency(totalAmount)}</td>
                <td>${quantity}</td>
            </tr>
        `;
        $tbody.append(row);
    });
}

// Render Top Products Chart (Horizontal Bar)
function renderTopProductsChart(data) {
    const ctx = document.getElementById('topProductsChart');
    
    if (topProductsChart) {
        topProductsChart.destroy();
    }
    
    const labels = data.map(item => {
        const name = item.product_name || 'نامشخص';
        return name.length > 30 ? name.substring(0, 30) + '...' : name;
    });
    const amounts = data.map(item => window.toFiniteNumber(item.total_amount, 0));
    
    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ فروش (ریال)',
                data: amounts,
                backgroundColor: 'rgba(89, 105, 255, 0.7)',
                borderColor: 'rgba(89, 105, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return 'مبلغ: ' + formatNumber(window.toFiniteNumber(context.parsed.x, 0)) + ' ریال';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

// Render Products Distribution Chart (Pie)
function renderProductsDistributionChart(data) {
    const ctx = document.getElementById('productsDistributionChart');
    
    if (productsDistributionChart) {
        productsDistributionChart.destroy();
    }
    
    const labels = data.slice(0, 5).map(item => {
        const name = item.product_name || 'نامشخص';
        return name.length > 20 ? name.substring(0, 20) + '...' : name;
    });
    const amounts = data.slice(0, 5).map(item => window.toFiniteNumber(item.total_amount, 0));
    
    // Add "سایر" category if more than 5 products
    if (data.length > 5) {
        const otherTotal = data.slice(5).reduce((sum, item) => sum + window.toFiniteNumber(item.total_amount, 0), 0);
        labels.push('سایر محصولات');
        amounts.push(otherTotal);
    }
    
    const colors = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(201, 203, 207, 0.8)'
    ];
    
    productsDistributionChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: amounts,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir',
                            size: 11
                        },
                        padding: 10
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = window.toFiniteNumber(context.parsed, 0);
                            const total = context.dataset.data.reduce((a, b) => a + window.toFiniteNumber(b, 0), 0);
                            const percentage = total ? window.toPercentageValue((value / total) * 100, 1) : 0;
                            return `${label}: ${formatNumber(value)} ریال (${formatNumber(percentage)}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Update Top Products Table
function updateTopProductsTable(data) {
    const tbody = $('#topProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="5" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item, index) => {
        const productName = item.product_name || 'نامشخص';
        const salesCount = window.toFiniteNumber(item.sales_count, 0);
        const hasQuantity = item.total_quantity !== null && item.total_quantity !== undefined;
        const quantityValue = hasQuantity ? window.toFiniteNumber(item.total_quantity, 0) : 0;
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const unitLabel = getUnitLabel(item);
        const quantityDisplay = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '-';
        const row = `
            <tr>
                <td><span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">${index + 1}</span></td>
                <td><strong>${productName}</strong></td>
                <td>${formatNumber(salesCount)}</td>
                <td>${quantityDisplay}</td>
                <td>${formatNumber(Math.round(totalAmount))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Product Statistics
function calculateProductStatistics(data) {
    if (data.length === 0) return;
    
    // Total unique products
    $('#totalProducts').text(formatNumber(data.length));
    
    // Top product
    if (data.length > 0) {
        $('#topProduct').text(data[0].product_name.length > 20 ? data[0].product_name.substring(0, 20) + '...' : data[0].product_name);
    }
    
    // Total quantity
    const totalQty = data.reduce((sum, item) => sum + window.toFiniteNumber(item.total_quantity, 0), 0);
    const dominantUnit = getDominantUnitLabel(data);
    $('#totalQuantity').text(formatQuantityValue(totalQty, dominantUnit));
}
</script>


```

```blade
<!-- Customers Analytics Tab Content -->

<div id="customer-focus-view" style="display: none;">
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="ti-light-bulb mr-2"></i>
        <div>
            <strong>نمای اختصاصی مشتری فعال است.</strong>
            <div class="small text-muted">برای بازگشت به گزارش عمومی، فیلتر مشتری را پاک کنید یا فیلتر متفاوتی انتخاب نمایید.</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center mb-3">
                        <span><i class="ti-package"></i> محصولات خریداری شده توسط مشتری</span>
                        <small class="text-muted" id="customer-focus-products-count"></small>
                    </h5>
                    <div class="chart-container" style="height: 360px;">
                        <canvas id="customerFocusProductsChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="customerFocusProductsEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> برای این مشتری محصولی یافت نشد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-home"></i> انبارهای مورد استفاده
                    </h5>
                    <ul class="list-group list-group-sm focus-warehouse-list" id="customerFocusWarehousesList"></ul>
                    <div class="alert alert-info focus-empty-state mt-3" id="customerFocusWarehousesEmpty" style="display: none;">
                        <i class="ti-info-alt"></i> سفارشات این مشتری به انبار خاصی نسبت داده نشده است.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-pie-chart"></i> وضعیت سفارش‌های این مشتری
                    </h5>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="customerFocusStatusChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="customerFocusStatusEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> سفارش فعالی برای وضعیت نمایش وجود ندارد.
                        </div>
                    </div>
                    <div class="mt-3" id="customerFocusStatusLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-stats-up"></i> شاخص‌های کلیدی مشتری
                    </h5>
                    <ul class="list-group list-group-sm" id="customerFocusStats"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="ti-list"></i> محصولات خریداری شده (جزئیات کامل)
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="customerFocusProductsTable">
                    <thead>
                        <tr>
                            <th width="60">رتبه</th>
                            <th>محصول</th>
                            <th width="120">تعداد سفارش</th>
                            <th width="160">مبلغ کل</th>
                            <th width="160">مقدار کل (واحد)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> در حال آماده‌سازی داده‌های محصول...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="customer-general-view" class="row">
    <!-- Top Customers Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-user"></i> Top 10 مشتریان برتر
                </h5>
                <p class="text-muted">مشتریانی که بیشترین خرید را داشته‌اند</p>
                <div class="chart-container">
                    <canvas id="topCustomersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول مشتریان برتر
                </h5>
                <p class="text-muted">جزئیات خرید مشتریان</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover" id="topCustomersTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>رتبه</th>
                                <th>نام مشتری</th>
                                <th>تعداد سفارش</th>
                                <th>مبلغ کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="ti-stats-up"></i> آمار کلیدی مشتریان
                </h5>
                <div class="mb-4">
                    <p class="text-muted mb-2">تعداد مشتریان</p>
                    <h3 class="mb-0" id="totalCustomers">-</h3>
                </div>
                <div class="mb-4">
                    <p class="text-muted mb-2">مشتری برتر</p>
                    <h6 class="mb-0" id="topCustomer" style="font-size: 13px;">-</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Segmentation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-pie-chart"></i> تحلیل پارتو (قانون 80/20)
                </h5>
                <p class="text-muted">درصد مشتریان که 80% فروش را تشکیل می‌دهند</p>
                <div class="alert alert-info" role="alert" id="paretoAnalysis">
                    <i class="spinner-border spinner-border-sm"></i> در حال محاسبه...
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Percentage Analysis Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="ti-pie-chart"></i> تحلیل درصدی فروش مشتریان (Pareto & ABC Analysis)
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="ti-info-alt"></i> 
                    این تحلیل نشان می‌دهد هر مشتری چند درصد از کل فروش شما را تشکیل می‌دهد و کدام مشتریان کلیدی (A)، مهم (B) یا عادی (C) هستند.
                </p>

                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">کل فروش</h6>
                                <h4 id="percentage-total-sales">-</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">تعداد مشتریان</h6>
                                <h4 id="percentage-total-customers">-</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left: 4px solid #28a745;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Top 10 مشتری</h6>
                                <h4 id="percentage-top10" class="text-success">-</h4>
                                <small class="text-muted">از کل فروش</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left: 4px solid #ff9f40;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">20% مشتریان برتر</h6>
                                <h4 id="percentage-top20" class="text-warning">-</h4>
                                <small class="text-muted">از کل فروش (پارتو)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ABC Classification Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            <h6><i class="ti-star"></i> دسته A - کلیدی</h6>
                            <p class="mb-1"><strong id="class-a-count">-</strong> مشتری (80% اول فروش)</p>
                            <small>مشتریان استراتژیک که باید به آنها توجه ویژه شود</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <h6><i class="ti-bookmark"></i> دسته B - مهم</h6>
                            <p class="mb-1"><strong id="class-b-count">-</strong> مشتری (15% بعدی)</p>
                            <small>مشتریان مهم با پتانسیل رشد</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-info">
                            <h6><i class="ti-user"></i> دسته C - عادی</h6>
                            <p class="mb-1"><strong id="class-c-count">-</strong> مشتری (5% باقیمانده)</p>
                            <small>مشتریان عادی با حجم خرید کم</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-pie-chart"></i> نمودار دایره‌ای سهم مشتریان (Top 15)
                                </h6>
                                <div class="chart-container" style="height: 400px;">
                                    <canvas id="customerPercentagePieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Percentage Table -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-list"></i> جدول تحلیل درصدی (همه مشتریان)
                                </h6>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-hover" id="customerPercentageTable">
                                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                            <tr>
                                                <th width="50">رتبه</th>
                                                <th>مشتری</th>
                                                <th width="100">مبلغ</th>
                                                <th width="80">درصد</th>
                                                <th width="80">تجمعی</th>
                                                <th width="60">دسته</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pareto Chart (Cumulative Line) -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-bar-chart"></i> نمودار پارتو (نمودار تجمعی)
                                </h6>
                                <p class="text-muted">نمایش درصد تجمعی فروش نسبت به تعداد مشتریان</p>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="paretoChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let topCustomersChart = null;
let customerPercentagePieChart = null;
let paretoChart = null;
let customerFocusProductsChart = null;
let customerFocusStatusChart = null;
window.customersChartsLoaded = false;

// Load Customers Analytics Data
function loadCustomersAnalytics() {
    console.log('Loading customers analytics...');

    if (window.currentFocus && window.currentFocus.type === 'customer') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for customer view, delaying render...');
            setTimeout(loadCustomersAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderCustomerFocusView(window.focusSummary, window.focusDistributions);
            window.customersChartsLoaded = true;
            return;
        }
    } else {
        toggleCustomerFocusView(false);
    }

    const dates = getFilterDates();
    
    // Load Top Customers (existing)
    $.ajax({
        url: '/report/sales/analytics/top-customers',
        method: 'POST',
        data: { ...dates, limit: 10 },
        dataType: 'json',
        success: function(response) {
            console.log('Top customers response:', response);
            if (response.success && response.data) {
                toggleCustomerFocusView(false);
                renderTopCustomersChart(response.data);
                updateTopCustomersTable(response.data);
                calculateCustomerStatistics(response.data);
                calculateParetoAnalysis(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading top customers:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    // Load Customer Percentage Analysis (NEW)
    $.ajax({
        url: '/report/sales/analytics/customer-percentage',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Customer percentage response:', response);
            if (response.success && response.data) {
                updatePercentageSummary(response.summary);
                renderCustomerPercentagePieChart(response.data);
                updateCustomerPercentageTable(response.data);
                renderParetoChart(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading customer percentage:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.customersChartsLoaded = true;
}

function toggleCustomerFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#customer-general-view').hide();
        $('#customer-focus-view').fadeIn(150);
    } else {
        $('#customer-focus-view').hide();
        $('#customer-general-view').show();
    }
}

function renderCustomerFocusView(summaryData, distributionsData) {
    if (!summaryData || !distributionsData) {
        toggleCustomerFocusView(false);
        return;
    }

    toggleCustomerFocusView(true);

    const distributions = distributionsData.distributions || {};
    const productDistribution = distributions.products || [];
    const warehouseDistribution = distributions.warehouses || [];
    const statusDistribution = distributions.statuses || [];

    renderCustomerFocusProductsChart(productDistribution);
    renderCustomerFocusWarehouseList(warehouseDistribution);
    renderCustomerFocusStatusChart(statusDistribution);
    renderCustomerFocusStats(summaryData);

    const topProducts = summaryData.top_entities ? summaryData.top_entities.products || [] : [];
    $('#customer-focus-products-count').text(topProducts.length ? `${formatNumber(topProducts.length)} محصول` : '');
    renderCustomerFocusProductsTable(topProducts);
}

function renderCustomerFocusProductsChart(data) {
    const ctx = document.getElementById('customerFocusProductsChart');
    if (!ctx) return;

    if (customerFocusProductsChart) {
        customerFocusProductsChart.destroy();
        customerFocusProductsChart = null;
    }

    if (!data || data.length === 0) {
        $('#customerFocusProductsEmpty').show();
        return;
    }

    $('#customerFocusProductsEmpty').hide();

    const labels = data.map(item => item.label || '-');
    const amounts = data.map(item => item.total_amount || 0);

    customerFocusProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ خرید (ریال)',
                data: amounts,
                backgroundColor: 'rgba(89, 105, 255, 0.75)',
                borderColor: 'rgba(89, 105, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return formatCurrency(context.parsed.x);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: { family: 'Vazir', size: 11 }
                    }
                }
            }
        }
    });
}

function renderCustomerFocusWarehouseList(data) {
    const $list = $('#customerFocusWarehousesList');
    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $('#customerFocusWarehousesEmpty').show();
        return;
    }

    $('#customerFocusWarehousesEmpty').hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = item.label || '-';
        const amount = formatCurrency(item.total_amount || 0);
        const orders = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const meta = [orders, quantity].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

function renderCustomerFocusStatusChart(data) {
    const ctx = document.getElementById('customerFocusStatusChart');
    if (!ctx) return;

    if (customerFocusStatusChart) {
        customerFocusStatusChart.destroy();
        customerFocusStatusChart = null;
    }

    if (!data || data.length === 0) {
        $('#customerFocusStatusEmpty').show();
        $('#customerFocusStatusLegend').empty();
        return;
    }

    $('#customerFocusStatusEmpty').hide();

    const labels = data.map(item => item.label || 'نامشخص');
    const counts = data.map(item => item.order_count || 0);
    const colors = [
        'rgba(255, 152, 0, 0.8)',
        'rgba(36, 210, 181, 0.8)',
        'rgba(89, 105, 255, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    customerFocusStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return `${context.label || ''}: ${formatNumber(context.parsed)} سفارش`;
                        }
                    }
                }
            }
        }
    });

    let legendHtml = '<div class="row">';
    data.forEach((item, index) => {
        const color = colors[index % colors.length];
        legendHtml += `
            <div class="col-md-6 mb-2 d-flex align-items-center">
                <span class="badge badge-pill mr-2" style="background:${color};">&nbsp;</span>
                <div>
                    <strong>${item.label || 'نامشخص'}</strong>
                    <div class="text-muted small">${formatNumber(item.order_count || 0)} سفارش</div>
                </div>
            </div>
        `;
    });
    legendHtml += '</div>';
    $('#customerFocusStatusLegend').html(legendHtml);
}

function renderCustomerFocusStats(summary) {
    const $list = $('#customerFocusStats');
    if (!$list.length || !summary) return;

    const totalAmount = window.toFiniteNumber(summary.total_amount, 0);
    const orderCount = window.toFiniteNumber(summary.order_count, 0);
    const hasQuantity = summary.total_quantity !== null && summary.total_quantity !== undefined;
    const totalQuantity = hasQuantity ? window.toFiniteNumber(summary.total_quantity, 0) : null;
    const uniqueProducts = window.toFiniteNumber(summary.unique_products, 0);
    const uniqueWarehouses = window.toFiniteNumber(summary.unique_warehouses, 0);
    const unitLabel = getUnitLabel(summary);

    const stats = [
        { label: 'کل خرید', value: formatCurrency(totalAmount) },
        { label: 'تعداد سفارش', value: formatNumber(orderCount) },
        { label: 'کل مقدار خرید (واحد ثبت‌شده)', value: hasQuantity && totalQuantity !== null ? formatQuantityValue(totalQuantity, unitLabel) : '-' },
        { label: 'محصولات یکتا', value: formatNumber(uniqueProducts) },
        { label: 'انبارهای فعال', value: formatNumber(uniqueWarehouses) }
    ];

    $list.empty();
    stats.forEach(stat => {
        $list.append(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${stat.label}</span>
                <span class="font-weight-bold">${stat.value}</span>
            </li>
        `);
    });
}

function renderCustomerFocusProductsTable(products) {
    const $tbody = $('#customerFocusProductsTable tbody');
    if (!$tbody.length) return;

    if (!products || products.length === 0) {
        $tbody.html(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="ti-alert"></i> محصولی برای این مشتری ثبت نشده است.
                </td>
            </tr>
        `);
        return;
    }

    $tbody.empty();
    products.forEach((product, index) => {
        const hasQuantity = product.total_quantity !== null && product.total_quantity !== undefined;
        const quantityValue = hasQuantity ? window.toFiniteNumber(product.total_quantity, 0) : 0;
        const unitLabel = getUnitLabel(product);
        const quantityDisplay = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '-';
        const orderCount = window.toFiniteNumber(product.order_count, 0);
        const totalAmount = window.toFiniteNumber(product.total_amount, 0);
        $tbody.append(`
            <tr>
                <td><span class="badge badge-secondary">${index + 1}</span></td>
                <td>${product.label || '-'}</td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatCurrency(totalAmount)}</td>
                <td>${quantityDisplay}</td>
            </tr>
        `);
    });
}

// Render Top Customers Chart
function renderTopCustomersChart(data) {
    const ctx = document.getElementById('topCustomersChart');
    
    if (topCustomersChart) {
        topCustomersChart.destroy();
    }
    
    const labels = data.map(item => {
        const name = item.customer_name || 'نامشخص';
        return name.length > 25 ? name.substring(0, 25) + '...' : name;
    });
    const amounts = data.map(item => window.toFiniteNumber(item.total_amount, 0));
    const counts = data.map(item => window.toFiniteNumber(item.order_count, 0));
    
    topCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'مبلغ خرید (ریال)',
                    data: amounts,
                    backgroundColor: 'rgba(89, 105, 255, 0.7)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 2,
                    yAxisID: 'y'
                },
                {
                    label: 'تعداد سفارش',
                    data: counts,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += formatNumber(window.toFiniteNumber(context.parsed.y, 0));
                            if (context.datasetIndex === 0) {
                                label += ' ریال';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'مبلغ (ریال)',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'تعداد',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Update Top Customers Table
function updateTopCustomersTable(data) {
    const tbody = $('#topCustomersTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item, index) => {
        const badgeClass = index === 0 ? 'bg-warning' : (index === 1 ? 'bg-secondary' : (index === 2 ? 'bg-bronze' : 'bg-light text-dark'));
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const row = `
            <tr>
                <td><span class="badge ${badgeClass}">${index + 1}</span></td>
                <td><strong>${item.customer_name}</strong></td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatNumber(Math.round(totalAmount))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Customer Statistics
function calculateCustomerStatistics(data) {
    if (data.length === 0) return;
    
    // Total customers
    $('#totalCustomers').text(formatNumber(data.length));
    
    // Top customer
    if (data.length > 0) {
        const topCust = data[0].customer_name;
        $('#topCustomer').text(topCust.length > 30 ? topCust.substring(0, 30) + '...' : topCust);
    }
    
}

// Calculate Pareto Analysis (80/20 rule)
function calculateParetoAnalysis(data) {
    if (!Array.isArray(data) || data.length === 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده کافی برای تحلیل موجود نیست');
        return;
    }
    
    const totalSales = data.reduce((sum, item) => {
        return sum + window.toFiniteNumber(item.total_amount, 0);
    }, 0);

    if (totalSales <= 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده معتبر برای تحلیل پارتو یافت نشد');
        return;
    }

    const target80 = totalSales * 0.8;
    
    let cumulativeSum = 0;
    let count80 = 0;
    
    for (let i = 0; i < data.length; i++) {
        cumulativeSum += window.toFiniteNumber(data[i].total_amount, 0);
        count80++;
        if (cumulativeSum >= target80) {
            break;
        }
    }
    
    const percentage = window.toPercentageValue((count80 / data.length) * 100, 1);
    
    $('#paretoAnalysis').html(`
        <i class="ti-info-alt"></i> 
        <strong>${formatNumber(count80)}</strong> مشتری از ${formatNumber(data.length)} (${formatNumber(percentage)}%) مشتری برتر، 
        <strong>80%</strong> از کل فروش را تشکیل می‌دهند.
        <br>
        <small class="text-muted">این تحلیل به شما کمک می‌کند روی مشتریان کلیدی تمرکز کنید.</small>
    `);
}

// ============ NEW FUNCTIONS FOR PERCENTAGE ANALYSIS ============

// Update Percentage Summary Statistics
function updatePercentageSummary(summary) {
    if (!summary) return;
    
    const totalSales = window.toFiniteNumber(summary.total_sales, 0);
    const totalCustomers = window.toFiniteNumber(summary.total_customers, 0);
    const top10Percentage = window.clampPercentage(summary.top_10_percentage, 1);
    const top20Percentage = window.clampPercentage(summary.top_20_percentage, 1);
    const classACount = window.toFiniteNumber(summary.class_a_customers, 0);
    const classBCount = window.toFiniteNumber(summary.class_b_customers, 0);
    const classCCount = window.toFiniteNumber(summary.class_c_customers, 0);
    
    $('#percentage-total-sales').text(formatNumber(Math.round(totalSales)) + ' ریال');
    $('#percentage-total-customers').text(formatNumber(totalCustomers) + ' مشتری');
    $('#percentage-top10').text(formatPercentageValue(top10Percentage, 1) + '٪');
    $('#percentage-top20').text(formatPercentageValue(top20Percentage, 1) + '٪');
    
    $('#class-a-count').text(formatNumber(classACount));
    $('#class-b-count').text(formatNumber(classBCount));
    $('#class-c-count').text(formatNumber(classCCount));
}

// Render Customer Percentage Pie Chart
function renderCustomerPercentagePieChart(data) {
    const ctx = document.getElementById('customerPercentagePieChart');
    
    if (!ctx) {
        console.error('Canvas element not found: customerPercentagePieChart');
        return;
    }
    
    if (customerPercentagePieChart) {
        customerPercentagePieChart.destroy();
    }
    
    if (data.length === 0) {
        return;
    }
    
    // Take top 15 customers for pie chart
    const top15 = data.slice(0, 15);
    const labels = top15.map(item => item.customer_name || 'نامشخص');
    const percentages = top15.map(item => window.clampPercentage(item.percentage, 2));
    
    // Generate colors
    const colors = generatePieColors(top15.length);
    
    customerPercentagePieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'درصد فروش',
                data: percentages,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir',
                            size: 11
                        },
                        padding: 10,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const shortLabel = label.length > 20 ? label.substring(0, 20) + '...' : label;
                                    return {
                                        text: `${shortLabel} (${value}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = window.toFiniteNumber(context.parsed, 0);
                            const dataItem = top15[context.dataIndex];
                            return [
                                `${label}`,
                                `درصد: ${formatPercentageValue(value, 2)}٪`,
                                `مبلغ: ${formatNumber(Math.round(window.toFiniteNumber(dataItem.total_amount, 0)))} ریال`,
                                `تعداد: ${formatNumber(window.toFiniteNumber(dataItem.order_count, 0))} سفارش`
                            ];
                        }
                    }
                }
            }
        }
    });
}

// Update Customer Percentage Table
function updateCustomerPercentageTable(data) {
    const tbody = $('#customerPercentageTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item) => {
        // Classification badge
        let classBadge = '';
        if (item.classification === 'A') {
            classBadge = '<span class="badge badge-success">A</span>';
        } else if (item.classification === 'B') {
            classBadge = '<span class="badge badge-warning">B</span>';
        } else {
            classBadge = '<span class="badge badge-info">C</span>';
        }
        
        // Rank badge
        let rankBadge = `<span class="badge badge-light">${item.rank}</span>`;
        if (item.rank === 1) rankBadge = `<span class="badge badge-warning">🥇 ${item.rank}</span>`;
        else if (item.rank === 2) rankBadge = `<span class="badge badge-secondary">🥈 ${item.rank}</span>`;
        else if (item.rank === 3) rankBadge = `<span class="badge bg-bronze text-white">🥉 ${item.rank}</span>`;
        
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const percentageValue = window.clampPercentage(item.percentage, 2);
        const percentageDisplay = formatPercentageValue(percentageValue, 2);
        const cumulativeValue = window.clampPercentage(item.cumulative_percentage, 2);
        const cumulativeDisplay = formatPercentageValue(cumulativeValue, 2);
        const progressColor = item.classification === 'A' ? 'success' : (item.classification === 'B' ? 'warning' : 'info');
        const progressWidth = percentageValue;
        const row = `
            <tr>
                <td>${rankBadge}</td>
                <td>
                    <strong>${item.customer_name || 'نامشخص'}</strong>
                    <br><small class="text-muted">${formatNumber(orderCount)} سفارش</small>
                </td>
                <td>${formatNumber(Math.round(totalAmount))}</td>
                <td>
                    <div class="customer-percentage-progress">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 14px;">
                                <div class="progress-bar bg-${progressColor}" role="progressbar" 
                                     style="width: ${progressWidth}%;" 
                                     aria-valuenow="${percentageValue}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-light progress-value">${percentageDisplay}٪</span>
                    </div>
                </td>
                <td>
                    <strong class="${cumulativeValue <= 80 ? 'text-success' : ''}">${cumulativeDisplay}٪</strong>
                </td>
                <td>${classBadge}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Render Pareto Chart (Cumulative Line Chart)
function renderParetoChart(data) {
    const ctx = document.getElementById('paretoChart');
    
    if (!ctx) {
        console.error('Canvas element not found: paretoChart');
        return;
    }
    
    if (paretoChart) {
        paretoChart.destroy();
    }
    
    if (data.length === 0) {
        return;
    }
    
    const labels = data.map(item => {
        const name = item.customer_name || 'نامشخص';
        const shortName = name.length > 15 ? name.substring(0, 15) + '...' : name;
        return `${item.rank}. ${shortName}`;
    });
    const percentages = data.map(item => window.clampPercentage(item.percentage, 2));
    const cumulativePercentages = data.map(item => window.clampPercentage(item.cumulative_percentage, 2));
    
    paretoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'درصد فروش',
                    data: percentages,
                    backgroundColor: 'rgba(89, 105, 255, 0.6)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'درصد تجمعی',
                    data: cumulativePercentages,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    yAxisID: 'y',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const dataItem = data[context.dataIndex];
                            if (context.dataset.type === 'bar') {
                                const safeValue = window.toFiniteNumber(context.parsed.y, 0);
                                return `درصد: ${formatPercentageValue(safeValue, 2)}٪`;
                            } else {
                                const safeValue = window.toFiniteNumber(context.parsed.y, 0);
                                return `تجمعی: ${formatPercentageValue(safeValue, 2)}٪`;
                            }
                        }
                    }
                },
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: 80,
                            yMax: 80,
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                content: '80% (قاعده پارتو)',
                                enabled: true,
                                position: 'end'
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'درصد',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 9
                        },
                        maxRotation: 90,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Generate colors for pie chart
function generatePieColors(count) {
    const baseColors = [
        'rgba(89, 105, 255, 0.8)',   // Blue
        'rgba(255, 159, 64, 0.8)',   // Orange
        'rgba(75, 192, 192, 0.8)',   // Teal
        'rgba(255, 99, 132, 0.8)',   // Red
        'rgba(153, 102, 255, 0.8)',  // Purple
        'rgba(255, 205, 86, 0.8)',   // Yellow
        'rgba(54, 162, 235, 0.8)',   // Light Blue
        'rgba(231, 76, 60, 0.8)',    // Red-Orange
        'rgba(46, 204, 113, 0.8)',   // Green
        'rgba(155, 89, 182, 0.8)',   // Purple
        'rgba(52, 152, 219, 0.8)',   // Blue
        'rgba(241, 196, 15, 0.8)',   // Gold
        'rgba(230, 126, 34, 0.8)',   // Orange
        'rgba(26, 188, 156, 0.8)',   // Turquoise
        'rgba(142, 68, 173, 0.8)'    // Dark Purple
    ];
    
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(baseColors[i % baseColors.length]);
    }
    return colors;
}
</script>

<style>
.customer-percentage-progress {
    display: flex;
    align-items: center;
}

.customer-percentage-progress .progress {
    flex-grow: 1;
    margin-bottom: 0;
    background-color: #f1f3f5;
}

.customer-percentage-progress .progress-value {
    margin-right: 0.75rem;
    margin-left: 0.75rem;
    font-weight: 600;
    color: #343a40;
    min-width: 64px;
    text-align: center;
}

.bg-bronze {
    background-color: #CD7F32;
    color: white;
}
</style>


```

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Models\Sales;
use App\Services\CustomerCategoryService;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReports extends Controller {

    /**
     * @var CustomerCategoryService
     */
    private $categoryService;

    public function __construct(CustomerCategoryService $categoryService) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates', 'analytics']);

        $this->categoryService = $categoryService;
    }

    public function comparison(Request $request) {
        // Check if requesting unique values for a column
        if ($request->get('action') === 'unique_values' && $request->has('column')) {
            return $this->getUniqueValues($request);
        }
        
        // Check if requesting sort action
        if ($request->get('action') === 'sort') {
            return $this->handleSort($request);
        }
        
        // Check if requesting search action
        if ($request->get('action') === 'search') {
            return $this->handleSearch($request);
        }
        
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }
    
    /**
     * Get unique values for a column (used by both comparison and analytics)
     */
    public function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            $customerCategory = $request->get('customer_category');
            
            // For comparison page (range-based)
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            // For analytics page (simple from/to)
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            
            // Get warehouse filter (for cascading dropdown)
            $warehouse = $request->get('warehouse');
            
            $query = Sales::query();
            
            // Apply date range filter for comparison page
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else if ($range == 2) {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }
            
            // Apply date filter for analytics page
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Apply warehouse filter (for product cascading)
            // When loading products, filter by selected warehouse
            if ($warehouse && $warehouse !== '' && $column === 'product_name') {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            
            if ($customerCategory) {
                $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
                if (empty($categoryCustomers)) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'count' => 0,
                    ]);
                }
                $query->whereIn('customer_name', $categoryCustomers);
            }

            $values = [];
            
            // Handle different column types
            switch ($column) {
                case 'creator':
                    $values = $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                        ->select('users.first_name', 'users.last_name')
                        ->distinct()
                        ->whereNotNull('users.first_name')
                        ->where('users.first_name', '!=', '')
                        ->get()
                        ->map(function($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
                default:
                    $values = $query->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
            }
            
            sort($values);
            
            return response()->json([
                'success' => true,
                'data' => array_values($values),
                'count' => count($values)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Unique Values Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Fetch customer categories for analytics filters.
     */
    public function getCustomerCategories(Request $request)
    {
        try {
            $categories = $this->categoryService->allCategories();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Customer Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle sort action
     */
    private function handleSort(Request $request) {
        // Store sort preferences in session
        $orderby = $request->get('orderby');
        $sortorder = $request->get('sortorder', 'asc');
        $range = $request->get('range', 1);
        
        session(['sales_report_sort_' . $range => [
            'orderby' => $orderby,
            'sortorder' => $sortorder
        ]]);
        
        // Return success - the actual sorting will be applied in comparisonDataTables
        return response()->json(['success' => true]);
    }
    
    /**
     * Handle search action
     */
    private function handleSearch(Request $request) {
        // Store search preferences in session
        $range = $request->get('range', 1);
        $searchParams = [];
        
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $searchParams[$key] = $value;
            }
        }
        
        session(['sales_report_search_' . $range => $searchParams]);
        
        return response()->json(['success' => true]);
    }

    public function comparisonData(Request $request) {
        try {
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info('Comparison Data Request', [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build Range 1 Query
            $range1_query = Sales::query();
            if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                $range1_query->where('document_date', '>=', $range1_from);
            }
            if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                $range1_query->where('document_date', '<=', $range1_to);
            }
            if ($sales_status) {
                $range1_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range1_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range1_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range1_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Build Range 2 Query
            $range2_query = Sales::query();
            if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                $range2_query->where('document_date', '>=', $range2_from);
            }
            if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                $range2_query->where('document_date', '<=', $range2_to);
            }
            if ($sales_status) {
                $range2_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range2_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range2_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range2_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            $result = [
                'range1' => [
                    'count' => (int) $range1_query->count(),
                    'total_sales_amount' => (float) ($range1_query->sum('base_sales_amount') ?? 0),
                ],
                'range2' => [
                    'count' => (int) $range2_query->count(),
                    'total_sales_amount' => (float) ($range2_query->sum('base_sales_amount') ?? 0),
                ],
            ];

            Log::info('Comparison Results', $result);

            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Comparison Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function comparisonDataTables(Request $request) {
        try {
            $range = $request->get('range', 1);
            
            // Get all parameters
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info("DataTables Range $range Request", [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build query based on range
            $query = Sales::query();
            
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }

            // Apply additional filters
            if ($sales_status) {
                $query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Apply column-specific search from request or session
            $searchParams = session('sales_report_search_' . $range, []);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $searchParams[$key] = $value;
                }
            }
            
            foreach ($searchParams as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        // Apply search to various columns
                        $searchableColumns = [
                            'sales_id', 'document_number', 'product_name', 'customer_name',
                            'main_quantity', 'base_price', 'base_sales_amount', 'base_net_amount',
                            'document_type', 'sales_type', 'product_code', 'product_barcode',
                            'tracking_code', 'main_unit', 'warehouse', 'base_tax_amount',
                            'base_duty_amount', 'base_additional_amount', 'base_increasing_factors',
                            'month', 'description', 'sales_status'
                        ];
                        
                        if (in_array($column, $searchableColumns)) {
                            if ($column === 'creator') {
                                // Special handling for creator
                                $query->whereHas('creator', function($q) use ($value) {
                                    $q->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                                });
                            } else {
                                $query->where($column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Apply sorting
            $sortSettings = session('sales_report_sort_' . $range);
            $orderby = $request->get('orderby', $sortSettings['orderby'] ?? 'document_date');
            $sortorder = $request->get('sortorder', $sortSettings['sortorder'] ?? 'desc');
            
            // Handle special sort columns
            if ($orderby === 'creator') {
                $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                      ->orderBy('users.first_name', $sortorder)
                      ->select('sales.*');
            } else {
                $query->orderBy($orderby, $sortorder);
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
                          ->skip($start)
                          ->take($length)
                          ->get();

            // Format data
            $data = [];
            foreach ($sales as $item) {
                $data[] = [
                    'sales_id' => $item->sales_id,
                    'product_name' => $item->product_name ?? '',
                    'customer_name' => $item->customer_name ?? '',
                    'document_number' => $item->document_number ?? '',
                    'main_quantity' => $item->main_quantity ?? 0,
                    'base_price' => $item->base_price ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'base_tax_amount' => $item->base_tax_amount ?? 0,
                    'base_duty_amount' => $item->base_duty_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_type' => $item->document_type ?? '',
                    'sales_type' => $item->sales_type ?? '',
                    'product_code' => $item->product_code ?? '',
                    'product_barcode' => $item->product_barcode ?? '',
                    'warehouse' => $item->warehouse ?? '',
                    'document_date' => $item->document_date,
                    'document_date_persian' => $item->document_date, // Already in Persian format
                    'creator' => $item->creator ? $item->creator->first_name . ' ' . $item->creator->last_name : '',
                    'actions' => '<a href="/sales/' . $item->sales_id . '" class="btn btn-sm btn-outline-primary">مشاهده</a>'
                ];
            }

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        $payload = ['page' => $page];
        return response()->view('pages.reports.sales.aggregates', $payload);
    }

    /**
     * Display sales analytics dashboard
     */
    public function analytics() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') . ' - ' . 'تحلیل‌های فروش' ];
        $payload = ['page' => $page];
        return new \App\Http\Responses\Reports\Sales\SalesAnalyticsResponse($payload);
    }

    /**
     * Build a base query for analytics endpoints with common filters
     */
    private function buildAnalyticsQuery(Request $request) {
        $query = Sales::query();

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $product = $request->get('product');
        $customer = $request->get('customer');
        $warehouse = $request->get('warehouse');
        $status = $request->get('status');
        $customerCategory = $request->get('customer_category');

        if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
            $query->where('document_date', '>=', $from_date);
        }
        if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
            $query->where('document_date', '<=', $to_date);
        }
        if ($product) {
            $query->where('product_name', 'LIKE', '%' . $product . '%');
        }
        if ($customer) {
            $query->where('customer_name', 'LIKE', '%' . $customer . '%');
        }
        if ($warehouse) {
            $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
        }
        if ($status) {
            $query->where('sales_status', $status);
        }

        if ($customerCategory) {
            $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
            if (empty($categoryCustomers)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('customer_name', $categoryCustomers);
            }
        }

        return $query;
    }

    /**
     * Provide summary information when a specific focus (product/customer/warehouse) is selected
     */
    public function getFocusSummary(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده خلاصه، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $orderCount = (clone $baseQuery)->count();
            $totalAmount = (clone $baseQuery)->sum('base_sales_amount') ?? 0;
            $totalNetAmount = (clone $baseQuery)->sum('base_net_amount') ?? 0;
            $totalQuantity = (clone $baseQuery)->sum('main_quantity') ?? 0;

            $firstSale = (clone $baseQuery)->min('document_date');
            $lastSale = (clone $baseQuery)->max('document_date');

            $uniqueCustomers = (clone $baseQuery)->distinct()->count('customer_name');
            $uniqueProducts = (clone $baseQuery)->distinct()->count('product_name');
            $uniqueWarehouses = (clone $baseQuery)->distinct()->count('warehouse');

            $topEntities = [];

            if ($focus === 'product') {
                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'customer') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            }

            $responseData = [
                'focus' => $focus,
                'label' => $focusValue,
                'order_count' => (int) $orderCount,
                'total_amount' => (float) round($totalAmount, 2),
                'total_net_amount' => (float) round($totalNetAmount, 2),
                'total_quantity' => (float) round($totalQuantity, 2),
                'first_sale_date' => $firstSale,
                'last_sale_date' => $lastSale,
                'unique_customers' => (int) $uniqueCustomers,
                'unique_products' => (int) $uniqueProducts,
                'unique_warehouses' => (int) $uniqueWarehouses,
                'top_entities' => [
                    'customers' => $topEntities['customers'] ?? [],
                    'products' => $topEntities['products'] ?? [],
                    'warehouses' => $topEntities['warehouses'] ?? [],
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide distribution breakdowns for the current focus
     */
    public function getFocusDistributions(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده توزیع، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $distributions = [
                'customers' => [],
                'products' => [],
                'warehouses' => [],
                'statuses' => [],
            ];

            $statusDistribution = (clone $baseQuery)
                ->selectRaw('sales_status as label, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('sales_status')
                ->orderByDesc('order_count')
                ->get();

            $distributions['statuses'] = $statusDistribution;

            if ($focus === 'product') {
                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'customer') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'focus' => $focus,
                    'label' => $focusValue,
                    'distributions' => $distributions,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Distribution Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get monthly sales trend
     */
    public function getMonthlyTrend(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Get all sales data
            $sales = $query->select('document_date', 'month', 'base_sales_amount')->get();
            
            // Group by year and month manually
            $grouped = [];
            foreach ($sales as $sale) {
                // Extract year from document_date (format: YYYY/MM/DD or YYYY-MM-DD)
                $dateParts = preg_split('/[-\/]/', $sale->document_date);
                $year = $dateParts[0] ?? '1403';
                $month = $sale->month;
                
                $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'year' => $year,
                        'month' => (int)$month,
                        'year_month' => $key,
                        'count' => 0,
                        'total_amount' => 0,
                    ];
                }

                $grouped[$key]['count']++;
                $grouped[$key]['total_amount'] += $sale->base_sales_amount;
            }
            
            // Sort grouped data by year_month
            $monthlyData = [];
            foreach ($grouped as $key => $data) {
                $monthlyData[] = $data;
            }
            
            // Sort by year_month
            usort($monthlyData, function($a, $b) {
                return strcmp($a['year_month'], $b['year_month']);
            });

            return response()->json([
                'success' => true,
                'data' => $monthlyData
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly Trend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by product
            $topProducts = $query->selectRaw('product_name, COUNT(*) as sales_count, SUM(main_quantity) as total_quantity, SUM(base_sales_amount) as total_amount')
                ->groupBy('product_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Top Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by customer
            $topCustomers = $query->selectRaw('customer_name, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('customer_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topCustomers
            ]);

        } catch (\Exception $e) {
            Log::error('Top Customers Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get customer percentage analysis (Pareto/ABC Analysis)
     */
    public function getCustomerPercentageAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            $totalSales = (clone $query)->sum('base_sales_amount') ?? 0;
            
            if ($totalSales === 0.0) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_sales' => 0,
                        'total_customers' => 0,
                        'top_10_percentage' => 0,
                        'top_20_percentage' => 0,
                        'class_a_customers' => 0,
                        'class_b_customers' => 0,
                        'class_c_customers' => 0,
                    ],
                ]);
            }

            // Group by customer and calculate amounts
            $customerSales = $query->selectRaw('
                    customer_name,
                    COUNT(*) as order_count,
                    SUM(base_sales_amount) as total_amount
                ')
                ->groupBy('customer_name')
                ->orderByDesc('total_amount')
                ->get();

            $cumulativePercentage = 0.0;
            $analysisData = [];
            
            foreach ($customerSales as $index => $customer) {
                $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
                $cumulativePercentage += $percentage;
                
                $classification = 'C';
                if ($cumulativePercentage <= 80) {
                    $classification = 'A';
                } elseif ($cumulativePercentage <= 95) {
                    $classification = 'B';
                }
                
                $analysisData[] = [
                    'rank' => $index + 1,
                    'customer_name' => $customer->customer_name,
                    'order_count' => (int) $customer->order_count,
                    'total_amount' => (float) $customer->total_amount,
                    'percentage' => round($percentage, 2),
                    'cumulative_percentage' => round($cumulativePercentage, 2),
                    'classification' => $classification,
                ];
            }

            $summary = [
                'total_sales' => (float) $totalSales,
                'total_customers' => count($customerSales),
                'top_10_percentage' => 0,
                'top_20_percentage' => 0,
                'class_a_customers' => 0,
                'class_b_customers' => 0,
                'class_c_customers' => 0,
            ];

            if (count($analysisData) > 0) {
                $top10 = array_slice($analysisData, 0, min(10, count($analysisData)));
                $summary['top_10_percentage'] = round(array_sum(array_column($top10, 'percentage')), 2);
                
                $top20Count = max(1, (int) ceil(count($analysisData) * 0.2));
                $top20 = array_slice($analysisData, 0, $top20Count);
                $summary['top_20_percentage'] = round(array_sum(array_column($top20, 'percentage')), 2);
                
                foreach ($analysisData as $item) {
                    if ($item['classification'] === 'A') {
                        $summary['class_a_customers']++;
                    } elseif ($item['classification'] === 'B') {
                        $summary['class_b_customers']++;
                    } else {
                        $summary['class_c_customers']++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $analysisData,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            Log::error('Customer Percentage Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get profit analysis
     */
    public function getProfitAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Calculate profit by product
            $profitData = $query->selectRaw('product_name, SUM(base_net_amount) as net_amount, SUM(base_sales_amount) as sales_amount, SUM(base_net_amount - base_sales_amount) as profit, COUNT(*) as count')
                ->groupBy('product_name')
                ->orderBy('profit', 'desc')
                ->limit(15)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profitData
            ]);

        } catch (\Exception $e) {
            Log::error('Profit Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get seasonal analysis (by quarter/season)
     */
    public function getSeasonalAnalysis(Request $request) {
        try {
            $sales = $this->buildAnalyticsQuery($request)->get();

            // Group by season (فصل)
            $seasonalData = [
                'spring' => ['name' => 'بهار', 'count' => 0, 'total' => 0, 'months' => [1, 2, 3]], // فروردین، اردیبهشت، خرداد
                'summer' => ['name' => 'تابستان', 'count' => 0, 'total' => 0, 'months' => [4, 5, 6]], // تیر، مرداد، شهریور
                'autumn' => ['name' => 'پاییز', 'count' => 0, 'total' => 0, 'months' => [7, 8, 9]], // مهر، آبان، آذر
                'winter' => ['name' => 'زمستان', 'count' => 0, 'total' => 0, 'months' => [10, 11, 12]], // دی، بهمن، اسفند
            ];

            foreach ($sales as $sale) {
                $month = (int) $sale->month;
                foreach ($seasonalData as $key => $season) {
                    if (in_array($month, $season['months'])) {
                        $seasonalData[$key]['count']++;
                        $seasonalData[$key]['total'] += $sale->base_sales_amount;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($seasonalData)
            ]);

        } catch (\Exception $e) {
            Log::error('Seasonal Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivery status analysis
     */
    public function getDeliveryStatus(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = $this->buildAnalyticsQuery($request);

            // Calculate delivery statistics
            $stats = (clone $query)->selectRaw('
                COUNT(*) as total_orders,
                SUM(issued_main_quantity) as total_issued,
                SUM(remaining_main_quantity) as total_remaining,
                SUM(main_quantity) as total_quantity
            ')->first();

            $deliveryRate = 0;
            if ($stats->total_quantity > 0) {
                $deliveryRate = ($stats->total_issued / $stats->total_quantity) * 100;
            }

            // Top products with pending delivery
            $pendingProducts = (clone $query)->selectRaw('product_name, SUM(remaining_main_quantity) as pending_quantity, COUNT(*) as order_count')
                ->where('remaining_main_quantity', '>', 0)
                ->groupBy('product_name')
                ->orderBy('pending_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'delivery_rate' => round($deliveryRate, 2),
                    'pending_products' => $pendingProducts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test analytics data - for debugging
     */
    public function testAnalyticsData(Request $request) {
        try {
            // Get total count
            $totalCount = Sales::count();
            
            // Get date range
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            
            // Sample data
            $sampleData = Sales::orderBy('document_date', 'desc')->limit(5)->get();
            
            // Monthly distribution
            $monthlyCount = Sales::selectRaw('month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return response()->json([
                'success' => true,
                'total_records' => $totalCount,
                'date_range' => [
                    'min' => $minDate,
                    'max' => $maxDate
                ],
                'sample_data' => $sampleData,
                'monthly_distribution' => $monthlyCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
```

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Models\Sales;
use App\Services\CustomerCategoryService;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReports extends Controller {

    /**
     * @var CustomerCategoryService
     */
    private $categoryService;

    public function __construct(CustomerCategoryService $categoryService) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates', 'analytics']);

        $this->categoryService = $categoryService;
    }

    public function comparison(Request $request) {
        // Check if requesting unique values for a column
        if ($request->get('action') === 'unique_values' && $request->has('column')) {
            return $this->getUniqueValues($request);
        }
        
        // Check if requesting sort action
        if ($request->get('action') === 'sort') {
            return $this->handleSort($request);
        }
        
        // Check if requesting search action
        if ($request->get('action') === 'search') {
            return $this->handleSearch($request);
        }
        
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }
    
    /**
     * Get unique values for a column (used by both comparison and analytics)
     */
    public function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            $customerCategory = $request->get('customer_category');
            
            // For comparison page (range-based)
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            // For analytics page (simple from/to)
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            
            // Get warehouse filter (for cascading dropdown)
            $warehouse = $request->get('warehouse');
            
            $query = Sales::query();
            
            // Apply date range filter for comparison page
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else if ($range == 2) {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }
            
            // Apply date filter for analytics page
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Apply warehouse filter (for product cascading)
            // When loading products, filter by selected warehouse
            if ($warehouse && $warehouse !== '' && $column === 'product_name') {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            
            if ($customerCategory) {
                $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
                if (empty($categoryCustomers)) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'count' => 0,
                    ]);
                }
                $query->whereIn('customer_name', $categoryCustomers);
            }

            $values = [];
            
            // Handle different column types
            switch ($column) {
                case 'creator':
                    $values = $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                        ->select('users.first_name', 'users.last_name')
                        ->distinct()
                        ->whereNotNull('users.first_name')
                        ->where('users.first_name', '!=', '')
                        ->get()
                        ->map(function($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
                default:
                    $values = $query->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
            }
            
            sort($values);
            
            return response()->json([
                'success' => true,
                'data' => array_values($values),
                'count' => count($values)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Unique Values Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Fetch customer categories for analytics filters.
     */
    public function getCustomerCategories(Request $request)
    {
        try {
            $categories = $this->categoryService->allCategories();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Customer Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle sort action
     */
    private function handleSort(Request $request) {
        // Store sort preferences in session
        $orderby = $request->get('orderby');
        $sortorder = $request->get('sortorder', 'asc');
        $range = $request->get('range', 1);
        
        session(['sales_report_sort_' . $range => [
            'orderby' => $orderby,
            'sortorder' => $sortorder
        ]]);
        
        // Return success - the actual sorting will be applied in comparisonDataTables
        return response()->json(['success' => true]);
    }
    
    /**
     * Handle search action
     */
    private function handleSearch(Request $request) {
        // Store search preferences in session
        $range = $request->get('range', 1);
        $searchParams = [];
        
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $searchParams[$key] = $value;
            }
        }
        
        session(['sales_report_search_' . $range => $searchParams]);
        
        return response()->json(['success' => true]);
    }

    public function comparisonData(Request $request) {
        try {
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info('Comparison Data Request', [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build Range 1 Query
            $range1_query = Sales::query();
            if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                $range1_query->where('document_date', '>=', $range1_from);
            }
            if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                $range1_query->where('document_date', '<=', $range1_to);
            }
            if ($sales_status) {
                $range1_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range1_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range1_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range1_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Build Range 2 Query
            $range2_query = Sales::query();
            if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                $range2_query->where('document_date', '>=', $range2_from);
            }
            if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                $range2_query->where('document_date', '<=', $range2_to);
            }
            if ($sales_status) {
                $range2_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range2_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range2_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range2_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            $result = [
                'range1' => [
                    'count' => (int) $range1_query->count(),
                    'total_sales_amount' => (float) ($range1_query->sum('base_sales_amount') ?? 0),
                ],
                'range2' => [
                    'count' => (int) $range2_query->count(),
                    'total_sales_amount' => (float) ($range2_query->sum('base_sales_amount') ?? 0),
                ],
            ];

            Log::info('Comparison Results', $result);

            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Comparison Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function comparisonDataTables(Request $request) {
        try {
            $range = $request->get('range', 1);
            
            // Get all parameters
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info("DataTables Range $range Request", [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build query based on range
            $query = Sales::query();
            
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }

            // Apply additional filters
            if ($sales_status) {
                $query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Apply column-specific search from request or session
            $searchParams = session('sales_report_search_' . $range, []);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $searchParams[$key] = $value;
                }
            }
            
            foreach ($searchParams as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        // Apply search to various columns
                        $searchableColumns = [
                            'sales_id', 'document_number', 'product_name', 'customer_name',
                            'main_quantity', 'base_price', 'base_sales_amount', 'base_net_amount',
                            'document_type', 'sales_type', 'product_code', 'product_barcode',
                            'tracking_code', 'main_unit', 'warehouse', 'base_tax_amount',
                            'base_duty_amount', 'base_additional_amount', 'base_increasing_factors',
                            'month', 'description', 'sales_status'
                        ];
                        
                        if (in_array($column, $searchableColumns)) {
                            if ($column === 'creator') {
                                // Special handling for creator
                                $query->whereHas('creator', function($q) use ($value) {
                                    $q->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                                });
                            } else {
                                $query->where($column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Apply sorting
            $sortSettings = session('sales_report_sort_' . $range);
            $orderby = $request->get('orderby', $sortSettings['orderby'] ?? 'document_date');
            $sortorder = $request->get('sortorder', $sortSettings['sortorder'] ?? 'desc');
            
            // Handle special sort columns
            if ($orderby === 'creator') {
                $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                      ->orderBy('users.first_name', $sortorder)
                      ->select('sales.*');
            } else {
                $query->orderBy($orderby, $sortorder);
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
                          ->skip($start)
                          ->take($length)
                          ->get();

            // Format data
            $data = [];
            foreach ($sales as $item) {
                $data[] = [
                    'sales_id' => $item->sales_id,
                    'product_name' => $item->product_name ?? '',
                    'customer_name' => $item->customer_name ?? '',
                    'document_number' => $item->document_number ?? '',
                    'main_quantity' => $item->main_quantity ?? 0,
                    'base_price' => $item->base_price ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'base_tax_amount' => $item->base_tax_amount ?? 0,
                    'base_duty_amount' => $item->base_duty_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_type' => $item->document_type ?? '',
                    'sales_type' => $item->sales_type ?? '',
                    'product_code' => $item->product_code ?? '',
                    'product_barcode' => $item->product_barcode ?? '',
                    'warehouse' => $item->warehouse ?? '',
                    'document_date' => $item->document_date,
                    'document_date_persian' => $item->document_date, // Already in Persian format
                    'creator' => $item->creator ? $item->creator->first_name . ' ' . $item->creator->last_name : '',
                    'actions' => '<a href="/sales/' . $item->sales_id . '" class="btn btn-sm btn-outline-primary">مشاهده</a>'
                ];
            }

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        $payload = ['page' => $page];
        return response()->view('pages.reports.sales.aggregates', $payload);
    }

    /**
     * Display sales analytics dashboard
     */
    public function analytics() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') . ' - ' . 'تحلیل‌های فروش' ];
        $payload = ['page' => $page];
        return new \App\Http\Responses\Reports\Sales\SalesAnalyticsResponse($payload);
    }

    /**
     * Build a base query for analytics endpoints with common filters
     */
    private function buildAnalyticsQuery(Request $request) {
        $query = Sales::query();

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $product = $request->get('product');
        $customer = $request->get('customer');
        $warehouse = $request->get('warehouse');
        $status = $request->get('status');
        $customerCategory = $request->get('customer_category');

        if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
            $query->where('document_date', '>=', $from_date);
        }
        if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
            $query->where('document_date', '<=', $to_date);
        }
        if ($product) {
            $query->where('product_name', 'LIKE', '%' . $product . '%');
        }
        if ($customer) {
            $query->where('customer_name', 'LIKE', '%' . $customer . '%');
        }
        if ($warehouse) {
            $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
        }
        if ($status) {
            $query->where('sales_status', $status);
        }

        if ($customerCategory) {
            $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
            if (empty($categoryCustomers)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('customer_name', $categoryCustomers);
            }
        }

        return $query;
    }

    /**
     * Provide summary information when a specific focus (product/customer/warehouse) is selected
     */
    public function getFocusSummary(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده خلاصه، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $orderCount = (clone $baseQuery)->count();
            $totalAmount = (clone $baseQuery)->sum('base_sales_amount') ?? 0;
            $totalNetAmount = (clone $baseQuery)->sum('base_net_amount') ?? 0;
            $totalQuantity = (clone $baseQuery)->sum('main_quantity') ?? 0;

            $firstSale = (clone $baseQuery)->min('document_date');
            $lastSale = (clone $baseQuery)->max('document_date');

            $uniqueCustomers = (clone $baseQuery)->distinct()->count('customer_name');
            $uniqueProducts = (clone $baseQuery)->distinct()->count('product_name');
            $uniqueWarehouses = (clone $baseQuery)->distinct()->count('warehouse');

            $topEntities = [];

            if ($focus === 'product') {
                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'customer') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            }

            $unitLabel = (clone $baseQuery)
                ->whereNotNull('main_unit')
                ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
                ->groupBy('main_unit')
                ->orderByDesc('quantity_sum')
                ->limit(1)
                ->value('main_unit');

            $responseData = [
                'focus' => $focus,
                'label' => $focusValue,
                'order_count' => (int) $orderCount,
                'total_amount' => (float) round($totalAmount, 2),
                'total_net_amount' => (float) round($totalNetAmount, 2),
                'total_quantity' => (float) round($totalQuantity, 2),
                'unit_label' => $unitLabel,
                'first_sale_date' => $firstSale,
                'last_sale_date' => $lastSale,
                'unique_customers' => (int) $uniqueCustomers,
                'unique_products' => (int) $uniqueProducts,
                'unique_warehouses' => (int) $uniqueWarehouses,
                'top_entities' => [
                    'customers' => $topEntities['customers'] ?? [],
                    'products' => $topEntities['products'] ?? [],
                    'warehouses' => $topEntities['warehouses'] ?? [],
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide distribution breakdowns for the current focus
     */
    public function getFocusDistributions(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده توزیع، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $distributions = [
                'customers' => [],
                'products' => [],
                'warehouses' => [],
                'statuses' => [],
            ];

            $statusDistribution = (clone $baseQuery)
                ->selectRaw('sales_status as label, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('sales_status')
                ->orderByDesc('order_count')
                ->get();

            $distributions['statuses'] = $statusDistribution;

            if ($focus === 'product') {
                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'customer') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'focus' => $focus,
                    'label' => $focusValue,
                    'distributions' => $distributions,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Distribution Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get monthly sales trend
     */
    public function getMonthlyTrend(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Get all sales data
            $sales = $query->select('document_date', 'month', 'base_sales_amount')->get();
            
            // Group by year and month manually
            $grouped = [];
            foreach ($sales as $sale) {
                // Extract year from document_date (format: YYYY/MM/DD or YYYY-MM-DD)
                $dateParts = preg_split('/[-\/]/', $sale->document_date);
                $year = $dateParts[0] ?? '1403';
                $month = $sale->month;
                
                $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'year' => $year,
                        'month' => (int)$month,
                        'year_month' => $key,
                        'count' => 0,
                        'total_amount' => 0,
                    ];
                }

                $grouped[$key]['count']++;
                $grouped[$key]['total_amount'] += $sale->base_sales_amount;
            }
            
            // Sort grouped data by year_month
            $monthlyData = [];
            foreach ($grouped as $key => $data) {
                $monthlyData[] = $data;
            }
            
            // Sort by year_month
            usort($monthlyData, function($a, $b) {
                return strcmp($a['year_month'], $b['year_month']);
            });

            return response()->json([
                'success' => true,
                'data' => $monthlyData
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly Trend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by product
            $topProducts = $query->selectRaw('product_name, COUNT(*) as sales_count, SUM(main_quantity) as total_quantity, SUM(base_sales_amount) as total_amount')
                ->groupBy('product_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Top Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by customer
            $topCustomers = $query->selectRaw('customer_name, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('customer_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topCustomers
            ]);

        } catch (\Exception $e) {
            Log::error('Top Customers Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get customer percentage analysis (Pareto/ABC Analysis)
     */
    public function getCustomerPercentageAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            $totalSales = (clone $query)->sum('base_sales_amount') ?? 0;
            
            if ($totalSales === 0.0) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_sales' => 0,
                        'total_customers' => 0,
                        'top_10_percentage' => 0,
                        'top_20_percentage' => 0,
                        'class_a_customers' => 0,
                        'class_b_customers' => 0,
                        'class_c_customers' => 0,
                    ],
                ]);
            }

            // Group by customer and calculate amounts
            $customerSales = $query->selectRaw('
                    customer_name,
                    COUNT(*) as order_count,
                    SUM(base_sales_amount) as total_amount
                ')
                ->groupBy('customer_name')
                ->orderByDesc('total_amount')
                ->get();

            $cumulativePercentage = 0.0;
            $analysisData = [];
            
            foreach ($customerSales as $index => $customer) {
                $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
                $cumulativePercentage += $percentage;
                
                $classification = 'C';
                if ($cumulativePercentage <= 80) {
                    $classification = 'A';
                } elseif ($cumulativePercentage <= 95) {
                    $classification = 'B';
                }
                
                $analysisData[] = [
                    'rank' => $index + 1,
                    'customer_name' => $customer->customer_name,
                    'order_count' => (int) $customer->order_count,
                    'total_amount' => (float) $customer->total_amount,
                    'percentage' => round($percentage, 2),
                    'cumulative_percentage' => round($cumulativePercentage, 2),
                    'classification' => $classification,
                ];
            }

            $summary = [
                'total_sales' => (float) $totalSales,
                'total_customers' => count($customerSales),
                'top_10_percentage' => 0,
                'top_20_percentage' => 0,
                'class_a_customers' => 0,
                'class_b_customers' => 0,
                'class_c_customers' => 0,
            ];

            if (count($analysisData) > 0) {
                $top10 = array_slice($analysisData, 0, min(10, count($analysisData)));
                $summary['top_10_percentage'] = round(array_sum(array_column($top10, 'percentage')), 2);
                
                $top20Count = max(1, (int) ceil(count($analysisData) * 0.2));
                $top20 = array_slice($analysisData, 0, $top20Count);
                $summary['top_20_percentage'] = round(array_sum(array_column($top20, 'percentage')), 2);
                
                foreach ($analysisData as $item) {
                    if ($item['classification'] === 'A') {
                        $summary['class_a_customers']++;
                    } elseif ($item['classification'] === 'B') {
                        $summary['class_b_customers']++;
                    } else {
                        $summary['class_c_customers']++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $analysisData,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            Log::error('Customer Percentage Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get profit analysis
     */
    public function getProfitAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Calculate profit by product
            $profitData = $query->selectRaw('product_name, SUM(base_net_amount) as net_amount, SUM(base_sales_amount) as sales_amount, SUM(base_net_amount - base_sales_amount) as profit, COUNT(*) as count')
                ->groupBy('product_name')
                ->orderBy('profit', 'desc')
                ->limit(15)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profitData
            ]);

        } catch (\Exception $e) {
            Log::error('Profit Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get seasonal analysis (by quarter/season)
     */
    public function getSeasonalAnalysis(Request $request) {
        try {
            $sales = $this->buildAnalyticsQuery($request)->get();

            // Group by season (فصل)
            $seasonalData = [
                'spring' => ['name' => 'بهار', 'count' => 0, 'total' => 0, 'months' => [1, 2, 3]], // فروردین، اردیبهشت، خرداد
                'summer' => ['name' => 'تابستان', 'count' => 0, 'total' => 0, 'months' => [4, 5, 6]], // تیر، مرداد، شهریور
                'autumn' => ['name' => 'پاییز', 'count' => 0, 'total' => 0, 'months' => [7, 8, 9]], // مهر، آبان، آذر
                'winter' => ['name' => 'زمستان', 'count' => 0, 'total' => 0, 'months' => [10, 11, 12]], // دی، بهمن، اسفند
            ];

            foreach ($sales as $sale) {
                $month = (int) $sale->month;
                foreach ($seasonalData as $key => $season) {
                    if (in_array($month, $season['months'])) {
                        $seasonalData[$key]['count']++;
                        $seasonalData[$key]['total'] += $sale->base_sales_amount;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($seasonalData)
            ]);

        } catch (\Exception $e) {
            Log::error('Seasonal Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivery status analysis
     */
    public function getDeliveryStatus(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = $this->buildAnalyticsQuery($request);

            // Calculate delivery statistics
            $stats = (clone $query)->selectRaw('
                COUNT(*) as total_orders,
                SUM(issued_main_quantity) as total_issued,
                SUM(remaining_main_quantity) as total_remaining,
                SUM(main_quantity) as total_quantity
            ')->first();

            $deliveryRate = 0;
            if ($stats->total_quantity > 0) {
                $deliveryRate = ($stats->total_issued / $stats->total_quantity) * 100;
            }

            // Top products with pending delivery
            $pendingProducts = (clone $query)->selectRaw('product_name, SUM(remaining_main_quantity) as pending_quantity, COUNT(*) as order_count')
                ->where('remaining_main_quantity', '>', 0)
                ->groupBy('product_name')
                ->orderBy('pending_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'delivery_rate' => round($deliveryRate, 2),
                    'pending_products' => $pendingProducts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test analytics data - for debugging
     */
    public function testAnalyticsData(Request $request) {
        try {
            // Get total count
            $totalCount = Sales::count();
            
            // Get date range
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            
            // Sample data
            $sampleData = Sales::orderBy('document_date', 'desc')->limit(5)->get();
            
            // Monthly distribution
            $monthlyCount = Sales::selectRaw('month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return response()->json([
                'success' => true,
                'total_records' => $totalCount,
                'date_range' => [
                    'min' => $minDate,
                    'max' => $maxDate
                ],
                'sample_data' => $sampleData,
                'monthly_distribution' => $monthlyCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
```

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Models\Sales;
use App\Services\CustomerCategoryService;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReports extends Controller {

    /**
     * @var CustomerCategoryService
     */
    private $categoryService;

    public function __construct(CustomerCategoryService $categoryService) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates', 'analytics']);

        $this->categoryService = $categoryService;
    }

    public function comparison(Request $request) {
        // Check if requesting unique values for a column
        if ($request->get('action') === 'unique_values' && $request->has('column')) {
            return $this->getUniqueValues($request);
        }
        
        // Check if requesting sort action
        if ($request->get('action') === 'sort') {
            return $this->handleSort($request);
        }
        
        // Check if requesting search action
        if ($request->get('action') === 'search') {
            return $this->handleSearch($request);
        }
        
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }
    
    /**
     * Get unique values for a column (used by both comparison and analytics)
     */
    public function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            $customerCategory = $request->get('customer_category');
            
            // For comparison page (range-based)
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            // For analytics page (simple from/to)
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            
            // Get warehouse filter (for cascading dropdown)
            $warehouse = $request->get('warehouse');
            
            $query = Sales::query();
            
            // Apply date range filter for comparison page
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else if ($range == 2) {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }
            
            // Apply date filter for analytics page
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Apply warehouse filter (for product cascading)
            // When loading products, filter by selected warehouse
            if ($warehouse && $warehouse !== '' && $column === 'product_name') {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            
            if ($customerCategory) {
                $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
                if (empty($categoryCustomers)) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'count' => 0,
                    ]);
                }
                $query->whereIn('customer_name', $categoryCustomers);
            }

            $values = [];
            
            // Handle different column types
            switch ($column) {
                case 'creator':
                    $values = $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                        ->select('users.first_name', 'users.last_name')
                        ->distinct()
                        ->whereNotNull('users.first_name')
                        ->where('users.first_name', '!=', '')
                        ->get()
                        ->map(function($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
                default:
                    $values = $query->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
            }
            
            sort($values);
            
            return response()->json([
                'success' => true,
                'data' => array_values($values),
                'count' => count($values)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Unique Values Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Fetch customer categories for analytics filters.
     */
    public function getCustomerCategories(Request $request)
    {
        try {
            $categories = $this->categoryService->allCategories();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Customer Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle sort action
     */
    private function handleSort(Request $request) {
        // Store sort preferences in session
        $orderby = $request->get('orderby');
        $sortorder = $request->get('sortorder', 'asc');
        $range = $request->get('range', 1);
        
        session(['sales_report_sort_' . $range => [
            'orderby' => $orderby,
            'sortorder' => $sortorder
        ]]);
        
        // Return success - the actual sorting will be applied in comparisonDataTables
        return response()->json(['success' => true]);
    }
    
    /**
     * Handle search action
     */
    private function handleSearch(Request $request) {
        // Store search preferences in session
        $range = $request->get('range', 1);
        $searchParams = [];
        
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $searchParams[$key] = $value;
            }
        }
        
        session(['sales_report_search_' . $range => $searchParams]);
        
        return response()->json(['success' => true]);
    }

    public function comparisonData(Request $request) {
        try {
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info('Comparison Data Request', [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build Range 1 Query
            $range1_query = Sales::query();
            if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                $range1_query->where('document_date', '>=', $range1_from);
            }
            if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                $range1_query->where('document_date', '<=', $range1_to);
            }
            if ($sales_status) {
                $range1_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range1_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range1_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range1_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Build Range 2 Query
            $range2_query = Sales::query();
            if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                $range2_query->where('document_date', '>=', $range2_from);
            }
            if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                $range2_query->where('document_date', '<=', $range2_to);
            }
            if ($sales_status) {
                $range2_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range2_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range2_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range2_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            $result = [
                'range1' => [
                    'count' => (int) $range1_query->count(),
                    'total_sales_amount' => (float) ($range1_query->sum('base_sales_amount') ?? 0),
                ],
                'range2' => [
                    'count' => (int) $range2_query->count(),
                    'total_sales_amount' => (float) ($range2_query->sum('base_sales_amount') ?? 0),
                ],
            ];

            Log::info('Comparison Results', $result);

            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Comparison Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function comparisonDataTables(Request $request) {
        try {
            $range = $request->get('range', 1);
            
            // Get all parameters
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info("DataTables Range $range Request", [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build query based on range
            $query = Sales::query();
            
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }

            // Apply additional filters
            if ($sales_status) {
                $query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Apply column-specific search from request or session
            $searchParams = session('sales_report_search_' . $range, []);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $searchParams[$key] = $value;
                }
            }
            
            foreach ($searchParams as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        // Apply search to various columns
                        $searchableColumns = [
                            'sales_id', 'document_number', 'product_name', 'customer_name',
                            'main_quantity', 'base_price', 'base_sales_amount', 'base_net_amount',
                            'document_type', 'sales_type', 'product_code', 'product_barcode',
                            'tracking_code', 'main_unit', 'warehouse', 'base_tax_amount',
                            'base_duty_amount', 'base_additional_amount', 'base_increasing_factors',
                            'month', 'description', 'sales_status'
                        ];
                        
                        if (in_array($column, $searchableColumns)) {
                            if ($column === 'creator') {
                                // Special handling for creator
                                $query->whereHas('creator', function($q) use ($value) {
                                    $q->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                                });
                            } else {
                                $query->where($column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Apply sorting
            $sortSettings = session('sales_report_sort_' . $range);
            $orderby = $request->get('orderby', $sortSettings['orderby'] ?? 'document_date');
            $sortorder = $request->get('sortorder', $sortSettings['sortorder'] ?? 'desc');
            
            // Handle special sort columns
            if ($orderby === 'creator') {
                $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                      ->orderBy('users.first_name', $sortorder)
                      ->select('sales.*');
            } else {
                $query->orderBy($orderby, $sortorder);
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
                          ->skip($start)
                          ->take($length)
                          ->get();

            // Format data
            $data = [];
            foreach ($sales as $item) {
                $data[] = [
                    'sales_id' => $item->sales_id,
                    'product_name' => $item->product_name ?? '',
                    'customer_name' => $item->customer_name ?? '',
                    'document_number' => $item->document_number ?? '',
                    'main_quantity' => $item->main_quantity ?? 0,
                    'base_price' => $item->base_price ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'base_tax_amount' => $item->base_tax_amount ?? 0,
                    'base_duty_amount' => $item->base_duty_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_type' => $item->document_type ?? '',
                    'sales_type' => $item->sales_type ?? '',
                    'product_code' => $item->product_code ?? '',
                    'product_barcode' => $item->product_barcode ?? '',
                    'warehouse' => $item->warehouse ?? '',
                    'document_date' => $item->document_date,
                    'document_date_persian' => $item->document_date, // Already in Persian format
                    'creator' => $item->creator ? $item->creator->first_name . ' ' . $item->creator->last_name : '',
                    'actions' => '<a href="/sales/' . $item->sales_id . '" class="btn btn-sm btn-outline-primary">مشاهده</a>'
                ];
            }

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        $payload = ['page' => $page];
        return response()->view('pages.reports.sales.aggregates', $payload);
    }

    /**
     * Display sales analytics dashboard
     */
    public function analytics() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') . ' - ' . 'تحلیل‌های فروش' ];
        $payload = ['page' => $page];
        return new \App\Http\Responses\Reports\Sales\SalesAnalyticsResponse($payload);
    }

    /**
     * Build a base query for analytics endpoints with common filters
     */
    private function buildAnalyticsQuery(Request $request) {
        $query = Sales::query();

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $product = $request->get('product');
        $customer = $request->get('customer');
        $warehouse = $request->get('warehouse');
        $status = $request->get('status');
        $customerCategory = $request->get('customer_category');

        if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
            $query->where('document_date', '>=', $from_date);
        }
        if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
            $query->where('document_date', '<=', $to_date);
        }
        if ($product) {
            $query->where('product_name', 'LIKE', '%' . $product . '%');
        }
        if ($customer) {
            $query->where('customer_name', 'LIKE', '%' . $customer . '%');
        }
        if ($warehouse) {
            $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
        }
        if ($status) {
            $query->where('sales_status', $status);
        }

        if ($customerCategory) {
            $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
            if (empty($categoryCustomers)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('customer_name', $categoryCustomers);
            }
        }

        return $query;
    }

    /**
     * Provide summary information when a specific focus (product/customer/warehouse) is selected
     */
    public function getFocusSummary(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده خلاصه، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $orderCount = (clone $baseQuery)->count();
            $totalAmount = (clone $baseQuery)->sum('base_sales_amount') ?? 0;
            $totalNetAmount = (clone $baseQuery)->sum('base_net_amount') ?? 0;
            $totalQuantity = (clone $baseQuery)->sum('main_quantity') ?? 0;

            $firstSale = (clone $baseQuery)->min('document_date');
            $lastSale = (clone $baseQuery)->max('document_date');

            $uniqueCustomers = (clone $baseQuery)->distinct()->count('customer_name');
            $uniqueProducts = (clone $baseQuery)->distinct()->count('product_name');
            $uniqueWarehouses = (clone $baseQuery)->distinct()->count('warehouse');

            $topEntities = [];

            if ($focus === 'product') {
                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'customer') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            }

            $unitLabel = (clone $baseQuery)
                ->whereNotNull('main_unit')
                ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
                ->groupBy('main_unit')
                ->orderByDesc('quantity_sum')
                ->limit(1)
                ->value('main_unit');

            $responseData = [
                'focus' => $focus,
                'label' => $focusValue,
                'order_count' => (int) $orderCount,
                'total_amount' => (float) round($totalAmount, 2),
                'total_net_amount' => (float) round($totalNetAmount, 2),
                'total_quantity' => (float) round($totalQuantity, 2),
                'unit_label' => $unitLabel,
                'first_sale_date' => $firstSale,
                'last_sale_date' => $lastSale,
                'unique_customers' => (int) $uniqueCustomers,
                'unique_products' => (int) $uniqueProducts,
                'unique_warehouses' => (int) $uniqueWarehouses,
                'top_entities' => [
                    'customers' => $topEntities['customers'] ?? [],
                    'products' => $topEntities['products'] ?? [],
                    'warehouses' => $topEntities['warehouses'] ?? [],
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide distribution breakdowns for the current focus
     */
    public function getFocusDistributions(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده توزیع، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $distributions = [
                'customers' => [],
                'products' => [],
                'warehouses' => [],
                'statuses' => [],
            ];

            $statusDistribution = (clone $baseQuery)
                ->selectRaw('sales_status as label, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount, MAX(main_unit) as unit_label')
                ->groupBy('sales_status')
                ->orderByDesc('order_count')
                ->get();

            $distributions['statuses'] = $statusDistribution;

            if ($focus === 'product') {
                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'customer') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'focus' => $focus,
                    'label' => $focusValue,
                    'distributions' => $distributions,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Distribution Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get monthly sales trend
     */
    public function getMonthlyTrend(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Get all sales data
            $sales = $query->select('document_date', 'month', 'base_sales_amount')->get();
            
            // Group by year and month manually
            $grouped = [];
            foreach ($sales as $sale) {
                // Extract year from document_date (format: YYYY/MM/DD or YYYY-MM-DD)
                $dateParts = preg_split('/[-\/]/', $sale->document_date);
                $year = $dateParts[0] ?? '1403';
                $month = $sale->month;
                
                $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'year' => $year,
                        'month' => (int)$month,
                        'year_month' => $key,
                        'count' => 0,
                        'total_amount' => 0,
                    ];
                }

                $grouped[$key]['count']++;
                $grouped[$key]['total_amount'] += $sale->base_sales_amount;
            }
            
            // Sort grouped data by year_month
            $monthlyData = [];
            foreach ($grouped as $key => $data) {
                $monthlyData[] = $data;
            }
            
            // Sort by year_month
            usort($monthlyData, function($a, $b) {
                return strcmp($a['year_month'], $b['year_month']);
            });

            return response()->json([
                'success' => true,
                'data' => $monthlyData
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly Trend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by product
            $topProducts = $query->selectRaw('product_name, COUNT(*) as sales_count, SUM(main_quantity) as total_quantity, SUM(base_sales_amount) as total_amount')
                ->groupBy('product_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Top Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by customer
            $topCustomers = $query->selectRaw('customer_name, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('customer_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topCustomers
            ]);

        } catch (\Exception $e) {
            Log::error('Top Customers Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get customer percentage analysis (Pareto/ABC Analysis)
     */
    public function getCustomerPercentageAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            $totalSales = (clone $query)->sum('base_sales_amount') ?? 0;
            
            if ($totalSales === 0.0) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_sales' => 0,
                        'total_customers' => 0,
                        'top_10_percentage' => 0,
                        'top_20_percentage' => 0,
                        'class_a_customers' => 0,
                        'class_b_customers' => 0,
                        'class_c_customers' => 0,
                    ],
                ]);
            }

            // Group by customer and calculate amounts
            $customerSales = $query->selectRaw('
                    customer_name,
                    COUNT(*) as order_count,
                    SUM(base_sales_amount) as total_amount
                ')
                ->groupBy('customer_name')
                ->orderByDesc('total_amount')
                ->get();

            $cumulativePercentage = 0.0;
            $analysisData = [];
            
            foreach ($customerSales as $index => $customer) {
                $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
                $cumulativePercentage += $percentage;
                
                $classification = 'C';
                if ($cumulativePercentage <= 80) {
                    $classification = 'A';
                } elseif ($cumulativePercentage <= 95) {
                    $classification = 'B';
                }
                
                $analysisData[] = [
                    'rank' => $index + 1,
                    'customer_name' => $customer->customer_name,
                    'order_count' => (int) $customer->order_count,
                    'total_amount' => (float) $customer->total_amount,
                    'percentage' => round($percentage, 2),
                    'cumulative_percentage' => round($cumulativePercentage, 2),
                    'classification' => $classification,
                ];
            }

            $summary = [
                'total_sales' => (float) $totalSales,
                'total_customers' => count($customerSales),
                'top_10_percentage' => 0,
                'top_20_percentage' => 0,
                'class_a_customers' => 0,
                'class_b_customers' => 0,
                'class_c_customers' => 0,
            ];

            if (count($analysisData) > 0) {
                $top10 = array_slice($analysisData, 0, min(10, count($analysisData)));
                $summary['top_10_percentage'] = round(array_sum(array_column($top10, 'percentage')), 2);
                
                $top20Count = max(1, (int) ceil(count($analysisData) * 0.2));
                $top20 = array_slice($analysisData, 0, $top20Count);
                $summary['top_20_percentage'] = round(array_sum(array_column($top20, 'percentage')), 2);
                
                foreach ($analysisData as $item) {
                    if ($item['classification'] === 'A') {
                        $summary['class_a_customers']++;
                    } elseif ($item['classification'] === 'B') {
                        $summary['class_b_customers']++;
                    } else {
                        $summary['class_c_customers']++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $analysisData,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            Log::error('Customer Percentage Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get profit analysis
     */
    public function getProfitAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Calculate profit by product
            $profitData = $query->selectRaw('product_name, SUM(base_net_amount) as net_amount, SUM(base_sales_amount) as sales_amount, SUM(base_net_amount - base_sales_amount) as profit, COUNT(*) as count')
                ->groupBy('product_name')
                ->orderBy('profit', 'desc')
                ->limit(15)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profitData
            ]);

        } catch (\Exception $e) {
            Log::error('Profit Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get seasonal analysis (by quarter/season)
     */
    public function getSeasonalAnalysis(Request $request) {
        try {
            $sales = $this->buildAnalyticsQuery($request)->get();

            // Group by season (فصل)
            $seasonalData = [
                'spring' => ['name' => 'بهار', 'count' => 0, 'total' => 0, 'months' => [1, 2, 3]], // فروردین، اردیبهشت، خرداد
                'summer' => ['name' => 'تابستان', 'count' => 0, 'total' => 0, 'months' => [4, 5, 6]], // تیر، مرداد، شهریور
                'autumn' => ['name' => 'پاییز', 'count' => 0, 'total' => 0, 'months' => [7, 8, 9]], // مهر، آبان، آذر
                'winter' => ['name' => 'زمستان', 'count' => 0, 'total' => 0, 'months' => [10, 11, 12]], // دی، بهمن، اسفند
            ];

            foreach ($sales as $sale) {
                $month = (int) $sale->month;
                foreach ($seasonalData as $key => $season) {
                    if (in_array($month, $season['months'])) {
                        $seasonalData[$key]['count']++;
                        $seasonalData[$key]['total'] += $sale->base_sales_amount;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($seasonalData)
            ]);

        } catch (\Exception $e) {
            Log::error('Seasonal Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivery status analysis
     */
    public function getDeliveryStatus(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = $this->buildAnalyticsQuery($request);

            // Calculate delivery statistics
            $stats = (clone $query)->selectRaw('
                COUNT(*) as total_orders,
                SUM(issued_main_quantity) as total_issued,
                SUM(remaining_main_quantity) as total_remaining,
                SUM(main_quantity) as total_quantity
            ')->first();

            $deliveryRate = 0;
            if ($stats->total_quantity > 0) {
                $deliveryRate = ($stats->total_issued / $stats->total_quantity) * 100;
            }

            // Top products with pending delivery
            $pendingProducts = (clone $query)->selectRaw('product_name, SUM(remaining_main_quantity) as pending_quantity, COUNT(*) as order_count')
                ->where('remaining_main_quantity', '>', 0)
                ->groupBy('product_name')
                ->orderBy('pending_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'delivery_rate' => round($deliveryRate, 2),
                    'pending_products' => $pendingProducts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test analytics data - for debugging
     */
    public function testAnalyticsData(Request $request) {
        try {
            // Get total count
            $totalCount = Sales::count();
            
            // Get date range
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            
            // Sample data
            $sampleData = Sales::orderBy('document_date', 'desc')->limit(5)->get();
            
            // Monthly distribution
            $monthlyCount = Sales::selectRaw('month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return response()->json([
                'success' => true,
                'total_records' => $totalCount,
                'date_range' => [
                    'min' => $minDate,
                    'max' => $maxDate
                ],
                'sample_data' => $sampleData,
                'monthly_distribution' => $monthlyCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
```

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Models\Sales;
use App\Services\CustomerCategoryService;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReports extends Controller {

    /**
     * @var CustomerCategoryService
     */
    private $categoryService;

    public function __construct(CustomerCategoryService $categoryService) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates', 'analytics']);

        $this->categoryService = $categoryService;
    }

    public function comparison(Request $request) {
        // Check if requesting unique values for a column
        if ($request->get('action') === 'unique_values' && $request->has('column')) {
            return $this->getUniqueValues($request);
        }
        
        // Check if requesting sort action
        if ($request->get('action') === 'sort') {
            return $this->handleSort($request);
        }
        
        // Check if requesting search action
        if ($request->get('action') === 'search') {
            return $this->handleSearch($request);
        }
        
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }
    
    /**
     * Get unique values for a column (used by both comparison and analytics)
     */
    public function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            $customerCategory = $request->get('customer_category');
            
            // For comparison page (range-based)
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            // For analytics page (simple from/to)
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            
            // Get warehouse filter (for cascading dropdown)
            $warehouse = $request->get('warehouse');
            
            $query = Sales::query();
            
            // Apply date range filter for comparison page
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else if ($range == 2) {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }
            
            // Apply date filter for analytics page
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Apply warehouse filter (for product cascading)
            // When loading products, filter by selected warehouse
            if ($warehouse && $warehouse !== '' && $column === 'product_name') {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            
            if ($customerCategory) {
                $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
                if (empty($categoryCustomers)) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'count' => 0,
                    ]);
                }
                $query->whereIn('customer_name', $categoryCustomers);
            }

            $values = [];
            
            // Handle different column types
            switch ($column) {
                case 'creator':
                    $values = $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                        ->select('users.first_name', 'users.last_name')
                        ->distinct()
                        ->whereNotNull('users.first_name')
                        ->where('users.first_name', '!=', '')
                        ->get()
                        ->map(function($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
                default:
                    $values = $query->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
            }
            
            sort($values);
            
            return response()->json([
                'success' => true,
                'data' => array_values($values),
                'count' => count($values)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Unique Values Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Fetch customer categories for analytics filters.
     */
    public function getCustomerCategories(Request $request)
    {
        try {
            $categories = $this->categoryService->allCategories();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Customer Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle sort action
     */
    private function handleSort(Request $request) {
        // Store sort preferences in session
        $orderby = $request->get('orderby');
        $sortorder = $request->get('sortorder', 'asc');
        $range = $request->get('range', 1);
        
        session(['sales_report_sort_' . $range => [
            'orderby' => $orderby,
            'sortorder' => $sortorder
        ]]);
        
        // Return success - the actual sorting will be applied in comparisonDataTables
        return response()->json(['success' => true]);
    }
    
    /**
     * Handle search action
     */
    private function handleSearch(Request $request) {
        // Store search preferences in session
        $range = $request->get('range', 1);
        $searchParams = [];
        
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $searchParams[$key] = $value;
            }
        }
        
        session(['sales_report_search_' . $range => $searchParams]);
        
        return response()->json(['success' => true]);
    }

    public function comparisonData(Request $request) {
        try {
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info('Comparison Data Request', [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build Range 1 Query
            $range1_query = Sales::query();
            if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                $range1_query->where('document_date', '>=', $range1_from);
            }
            if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                $range1_query->where('document_date', '<=', $range1_to);
            }
            if ($sales_status) {
                $range1_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range1_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range1_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range1_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Build Range 2 Query
            $range2_query = Sales::query();
            if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                $range2_query->where('document_date', '>=', $range2_from);
            }
            if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                $range2_query->where('document_date', '<=', $range2_to);
            }
            if ($sales_status) {
                $range2_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range2_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range2_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range2_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            $result = [
                'range1' => [
                    'count' => (int) $range1_query->count(),
                    'total_sales_amount' => (float) ($range1_query->sum('base_sales_amount') ?? 0),
                ],
                'range2' => [
                    'count' => (int) $range2_query->count(),
                    'total_sales_amount' => (float) ($range2_query->sum('base_sales_amount') ?? 0),
                ],
            ];

            Log::info('Comparison Results', $result);

            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Comparison Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function comparisonDataTables(Request $request) {
        try {
            $range = $request->get('range', 1);
            
            // Get all parameters
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info("DataTables Range $range Request", [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build query based on range
            $query = Sales::query();
            
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }

            // Apply additional filters
            if ($sales_status) {
                $query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Apply column-specific search from request or session
            $searchParams = session('sales_report_search_' . $range, []);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $searchParams[$key] = $value;
                }
            }
            
            foreach ($searchParams as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        // Apply search to various columns
                        $searchableColumns = [
                            'sales_id', 'document_number', 'product_name', 'customer_name',
                            'main_quantity', 'base_price', 'base_sales_amount', 'base_net_amount',
                            'document_type', 'sales_type', 'product_code', 'product_barcode',
                            'tracking_code', 'main_unit', 'warehouse', 'base_tax_amount',
                            'base_duty_amount', 'base_additional_amount', 'base_increasing_factors',
                            'month', 'description', 'sales_status'
                        ];
                        
                        if (in_array($column, $searchableColumns)) {
                            if ($column === 'creator') {
                                // Special handling for creator
                                $query->whereHas('creator', function($q) use ($value) {
                                    $q->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                                });
                            } else {
                                $query->where($column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Apply sorting
            $sortSettings = session('sales_report_sort_' . $range);
            $orderby = $request->get('orderby', $sortSettings['orderby'] ?? 'document_date');
            $sortorder = $request->get('sortorder', $sortSettings['sortorder'] ?? 'desc');
            
            // Handle special sort columns
            if ($orderby === 'creator') {
                $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                      ->orderBy('users.first_name', $sortorder)
                      ->select('sales.*');
            } else {
                $query->orderBy($orderby, $sortorder);
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
                          ->skip($start)
                          ->take($length)
                          ->get();

            // Format data
            $data = [];
            foreach ($sales as $item) {
                $data[] = [
                    'sales_id' => $item->sales_id,
                    'product_name' => $item->product_name ?? '',
                    'customer_name' => $item->customer_name ?? '',
                    'document_number' => $item->document_number ?? '',
                    'main_quantity' => $item->main_quantity ?? 0,
                    'base_price' => $item->base_price ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'base_tax_amount' => $item->base_tax_amount ?? 0,
                    'base_duty_amount' => $item->base_duty_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_type' => $item->document_type ?? '',
                    'sales_type' => $item->sales_type ?? '',
                    'product_code' => $item->product_code ?? '',
                    'product_barcode' => $item->product_barcode ?? '',
                    'warehouse' => $item->warehouse ?? '',
                    'document_date' => $item->document_date,
                    'document_date_persian' => $item->document_date, // Already in Persian format
                    'creator' => $item->creator ? $item->creator->first_name . ' ' . $item->creator->last_name : '',
                    'actions' => '<a href="/sales/' . $item->sales_id . '" class="btn btn-sm btn-outline-primary">مشاهده</a>'
                ];
            }

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        $payload = ['page' => $page];
        return response()->view('pages.reports.sales.aggregates', $payload);
    }

    /**
     * Display sales analytics dashboard
     */
    public function analytics() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') . ' - ' . 'تحلیل‌های فروش' ];
        $payload = ['page' => $page];
        return new \App\Http\Responses\Reports\Sales\SalesAnalyticsResponse($payload);
    }

    /**
     * Build a base query for analytics endpoints with common filters
     */
    private function buildAnalyticsQuery(Request $request) {
        $query = Sales::query();

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $product = $request->get('product');
        $customer = $request->get('customer');
        $warehouse = $request->get('warehouse');
        $status = $request->get('status');
        $customerCategory = $request->get('customer_category');

        if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
            $query->where('document_date', '>=', $from_date);
        }
        if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
            $query->where('document_date', '<=', $to_date);
        }
        if ($product) {
            $query->where('product_name', 'LIKE', '%' . $product . '%');
        }
        if ($customer) {
            $query->where('customer_name', 'LIKE', '%' . $customer . '%');
        }
        if ($warehouse) {
            $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
        }
        if ($status) {
            $query->where('sales_status', $status);
        }

        if ($customerCategory) {
            $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
            if (empty($categoryCustomers)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('customer_name', $categoryCustomers);
            }
        }

        return $query;
    }

    /**
     * Provide summary information when a specific focus (product/customer/warehouse) is selected
     */
    public function getFocusSummary(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده خلاصه، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $orderCount = (clone $baseQuery)->count();
            $totalAmount = (clone $baseQuery)->sum('base_sales_amount') ?? 0;
            $totalNetAmount = (clone $baseQuery)->sum('base_net_amount') ?? 0;
            $totalQuantity = (clone $baseQuery)->sum('main_quantity') ?? 0;

            $firstSale = (clone $baseQuery)->min('document_date');
            $lastSale = (clone $baseQuery)->max('document_date');

            $uniqueCustomers = (clone $baseQuery)->distinct()->count('customer_name');
            $uniqueProducts = (clone $baseQuery)->distinct()->count('product_name');
            $uniqueWarehouses = (clone $baseQuery)->distinct()->count('warehouse');

            $topEntities = [];

            if ($focus === 'product') {
                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'customer') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            }

            $unitLabel = (clone $baseQuery)
                ->whereNotNull('main_unit')
                ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
                ->groupBy('main_unit')
                ->orderByDesc('quantity_sum')
                ->limit(1)
                ->value('main_unit');

            $responseData = [
                'focus' => $focus,
                'label' => $focusValue,
                'order_count' => (int) $orderCount,
                'total_amount' => (float) round($totalAmount, 2),
                'total_net_amount' => (float) round($totalNetAmount, 2),
                'total_quantity' => (float) round($totalQuantity, 2),
                'unit_label' => $unitLabel,
                'first_sale_date' => $firstSale,
                'last_sale_date' => $lastSale,
                'unique_customers' => (int) $uniqueCustomers,
                'unique_products' => (int) $uniqueProducts,
                'unique_warehouses' => (int) $uniqueWarehouses,
                'top_entities' => [
                    'customers' => $topEntities['customers'] ?? [],
                    'products' => $topEntities['products'] ?? [],
                    'warehouses' => $topEntities['warehouses'] ?? [],
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide distribution breakdowns for the current focus
     */
    public function getFocusDistributions(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده توزیع، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $distributions = [
                'customers' => [],
                'products' => [],
                'warehouses' => [],
                'statuses' => [],
            ];

            $statusDistribution = (clone $baseQuery)
                ->selectRaw('sales_status as label, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount, MAX(main_unit) as unit_label')
                ->groupBy('sales_status')
                ->orderByDesc('order_count')
                ->get();

            $distributions['statuses'] = $statusDistribution;

            if ($focus === 'product') {
                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'customer') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'focus' => $focus,
                    'label' => $focusValue,
                    'distributions' => $distributions,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Distribution Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get monthly sales trend
     */
    public function getMonthlyTrend(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Get all sales data
            $sales = $query->select('document_date', 'month', 'base_sales_amount')->get();
            
            // Group by year and month manually
            $grouped = [];
            foreach ($sales as $sale) {
                // Extract year from document_date (format: YYYY/MM/DD or YYYY-MM-DD)
                $dateParts = preg_split('/[-\/]/', $sale->document_date);
                $year = $dateParts[0] ?? '1403';
                $month = $sale->month;
                
                $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'year' => $year,
                        'month' => (int)$month,
                        'year_month' => $key,
                        'count' => 0,
                        'total_amount' => 0,
                    ];
                }

                $grouped[$key]['count']++;
                $grouped[$key]['total_amount'] += $sale->base_sales_amount;
            }
            
            // Sort grouped data by year_month
            $monthlyData = [];
            foreach ($grouped as $key => $data) {
                $monthlyData[] = $data;
            }
            
            // Sort by year_month
            usort($monthlyData, function($a, $b) {
                return strcmp($a['year_month'], $b['year_month']);
            });

            return response()->json([
                'success' => true,
                'data' => $monthlyData
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly Trend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by product
            $topProducts = $query->selectRaw('product_name, COUNT(*) as sales_count, SUM(main_quantity) as total_quantity, SUM(base_sales_amount) as total_amount, MAX(main_unit) as unit_label')
                ->groupBy('product_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Top Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by customer
            $topCustomers = $query->selectRaw('customer_name, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('customer_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topCustomers
            ]);

        } catch (\Exception $e) {
            Log::error('Top Customers Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get customer percentage analysis (Pareto/ABC Analysis)
     */
    public function getCustomerPercentageAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            $totalSales = (clone $query)->sum('base_sales_amount') ?? 0;
            
            if ($totalSales === 0.0) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_sales' => 0,
                        'total_customers' => 0,
                        'top_10_percentage' => 0,
                        'top_20_percentage' => 0,
                        'class_a_customers' => 0,
                        'class_b_customers' => 0,
                        'class_c_customers' => 0,
                    ],
                ]);
            }

            // Group by customer and calculate amounts
            $customerSales = $query->selectRaw('
                    customer_name,
                    COUNT(*) as order_count,
                    SUM(base_sales_amount) as total_amount
                ')
                ->groupBy('customer_name')
                ->orderByDesc('total_amount')
                ->get();

            $cumulativePercentage = 0.0;
            $analysisData = [];
            
            foreach ($customerSales as $index => $customer) {
                $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
                $cumulativePercentage += $percentage;
                
                $classification = 'C';
                if ($cumulativePercentage <= 80) {
                    $classification = 'A';
                } elseif ($cumulativePercentage <= 95) {
                    $classification = 'B';
                }
                
                $analysisData[] = [
                    'rank' => $index + 1,
                    'customer_name' => $customer->customer_name,
                    'order_count' => (int) $customer->order_count,
                    'total_amount' => (float) $customer->total_amount,
                    'percentage' => round($percentage, 2),
                    'cumulative_percentage' => round($cumulativePercentage, 2),
                    'classification' => $classification,
                ];
            }

            $summary = [
                'total_sales' => (float) $totalSales,
                'total_customers' => count($customerSales),
                'top_10_percentage' => 0,
                'top_20_percentage' => 0,
                'class_a_customers' => 0,
                'class_b_customers' => 0,
                'class_c_customers' => 0,
            ];

            if (count($analysisData) > 0) {
                $top10 = array_slice($analysisData, 0, min(10, count($analysisData)));
                $summary['top_10_percentage'] = round(array_sum(array_column($top10, 'percentage')), 2);
                
                $top20Count = max(1, (int) ceil(count($analysisData) * 0.2));
                $top20 = array_slice($analysisData, 0, $top20Count);
                $summary['top_20_percentage'] = round(array_sum(array_column($top20, 'percentage')), 2);
                
                foreach ($analysisData as $item) {
                    if ($item['classification'] === 'A') {
                        $summary['class_a_customers']++;
                    } elseif ($item['classification'] === 'B') {
                        $summary['class_b_customers']++;
                    } else {
                        $summary['class_c_customers']++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $analysisData,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            Log::error('Customer Percentage Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get profit analysis
     */
    public function getProfitAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Calculate profit by product
            $profitData = $query->selectRaw('product_name, SUM(base_net_amount) as net_amount, SUM(base_sales_amount) as sales_amount, SUM(base_net_amount - base_sales_amount) as profit, COUNT(*) as count')
                ->groupBy('product_name')
                ->orderBy('profit', 'desc')
                ->limit(15)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profitData
            ]);

        } catch (\Exception $e) {
            Log::error('Profit Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get seasonal analysis (by quarter/season)
     */
    public function getSeasonalAnalysis(Request $request) {
        try {
            $sales = $this->buildAnalyticsQuery($request)->get();

            // Group by season (فصل)
            $seasonalData = [
                'spring' => ['name' => 'بهار', 'count' => 0, 'total' => 0, 'months' => [1, 2, 3]], // فروردین، اردیبهشت، خرداد
                'summer' => ['name' => 'تابستان', 'count' => 0, 'total' => 0, 'months' => [4, 5, 6]], // تیر، مرداد، شهریور
                'autumn' => ['name' => 'پاییز', 'count' => 0, 'total' => 0, 'months' => [7, 8, 9]], // مهر، آبان، آذر
                'winter' => ['name' => 'زمستان', 'count' => 0, 'total' => 0, 'months' => [10, 11, 12]], // دی، بهمن، اسفند
            ];

            foreach ($sales as $sale) {
                $month = (int) $sale->month;
                foreach ($seasonalData as $key => $season) {
                    if (in_array($month, $season['months'])) {
                        $seasonalData[$key]['count']++;
                        $seasonalData[$key]['total'] += $sale->base_sales_amount;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($seasonalData)
            ]);

        } catch (\Exception $e) {
            Log::error('Seasonal Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivery status analysis
     */
    public function getDeliveryStatus(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = $this->buildAnalyticsQuery($request);

            // Calculate delivery statistics
            $stats = (clone $query)->selectRaw('
                COUNT(*) as total_orders,
                SUM(issued_main_quantity) as total_issued,
                SUM(remaining_main_quantity) as total_remaining,
                SUM(main_quantity) as total_quantity
            ')->first();

            $deliveryRate = 0;
            if ($stats->total_quantity > 0) {
                $deliveryRate = ($stats->total_issued / $stats->total_quantity) * 100;
            }

            // Top products with pending delivery
            $pendingProducts = (clone $query)->selectRaw('product_name, SUM(remaining_main_quantity) as pending_quantity, COUNT(*) as order_count')
                ->where('remaining_main_quantity', '>', 0)
                ->groupBy('product_name')
                ->orderBy('pending_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'delivery_rate' => round($deliveryRate, 2),
                    'pending_products' => $pendingProducts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test analytics data - for debugging
     */
    public function testAnalyticsData(Request $request) {
        try {
            // Get total count
            $totalCount = Sales::count();
            
            // Get date range
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            
            // Sample data
            $sampleData = Sales::orderBy('document_date', 'desc')->limit(5)->get();
            
            // Monthly distribution
            $monthlyCount = Sales::selectRaw('month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return response()->json([
                'success' => true,
                'total_records' => $totalCount,
                'date_range' => [
                    'min' => $minDate,
                    'max' => $maxDate
                ],
                'sample_data' => $sampleData,
                'monthly_distribution' => $monthlyCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
```

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Responses\Reports\Sales\SalesComparisonResponse;
use App\Models\Sales;
use App\Services\CustomerCategoryService;
use App\Helpers\PersianCalendarHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesReports extends Controller {

    /**
     * @var CustomerCategoryService
     */
    private $categoryService;

    public function __construct(CustomerCategoryService $categoryService) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('reportsMiddlewareShow')->only(['comparison', 'aggregates', 'analytics']);

        $this->categoryService = $categoryService;
    }

    public function comparison(Request $request) {
        // Check if requesting unique values for a column
        if ($request->get('action') === 'unique_values' && $request->has('column')) {
            return $this->getUniqueValues($request);
        }
        
        // Check if requesting sort action
        if ($request->get('action') === 'sort') {
            return $this->handleSort($request);
        }
        
        // Check if requesting search action
        if ($request->get('action') === 'search') {
            return $this->handleSearch($request);
        }
        
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        
        $report = [
            'range1' => ['count' => 0, 'total_sales_amount' => 0],
            'range2' => ['count' => 0, 'total_sales_amount' => 0],
        ];

        $payload = ['page' => $page, 'report' => $report];
        return new SalesComparisonResponse($payload);
    }
    
    /**
     * Get unique values for a column (used by both comparison and analytics)
     */
    public function getUniqueValues(Request $request) {
        try {
            $column = $request->get('column');
            $customerCategory = $request->get('customer_category');
            
            // For comparison page (range-based)
            $range = $request->get('range', 1);
            $range1_from = $request->get('range1_from');
            $range1_to = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to = $request->get('range2_to');
            
            // For analytics page (simple from/to)
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            
            // Get warehouse filter (for cascading dropdown)
            $warehouse = $request->get('warehouse');
            
            $query = Sales::query();
            
            // Apply date range filter for comparison page
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else if ($range == 2) {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }
            
            // Apply date filter for analytics page
            if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
                $query->where('document_date', '>=', $from_date);
            }
            if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
                $query->where('document_date', '<=', $to_date);
            }
            
            // Apply warehouse filter (for product cascading)
            // When loading products, filter by selected warehouse
            if ($warehouse && $warehouse !== '' && $column === 'product_name') {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }
            
            if ($customerCategory) {
                $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
                if (empty($categoryCustomers)) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'count' => 0,
                    ]);
                }
                $query->whereIn('customer_name', $categoryCustomers);
            }

            $values = [];
            
            // Handle different column types
            switch ($column) {
                case 'creator':
                    $values = $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                        ->select('users.first_name', 'users.last_name')
                        ->distinct()
                        ->whereNotNull('users.first_name')
                        ->where('users.first_name', '!=', '')
                        ->get()
                        ->map(function($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
                default:
                    $values = $query->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->unique()
                        ->values()
                        ->toArray();
                    break;
            }
            
            sort($values);
            
            return response()->json([
                'success' => true,
                'data' => array_values($values),
                'count' => count($values)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Unique Values Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Fetch customer categories for analytics filters.
     */
    public function getCustomerCategories(Request $request)
    {
        try {
            $categories = $this->categoryService->allCategories();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Customer Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle sort action
     */
    private function handleSort(Request $request) {
        // Store sort preferences in session
        $orderby = $request->get('orderby');
        $sortorder = $request->get('sortorder', 'asc');
        $range = $request->get('range', 1);
        
        session(['sales_report_sort_' . $range => [
            'orderby' => $orderby,
            'sortorder' => $sortorder
        ]]);
        
        // Return success - the actual sorting will be applied in comparisonDataTables
        return response()->json(['success' => true]);
    }
    
    /**
     * Handle search action
     */
    private function handleSearch(Request $request) {
        // Store search preferences in session
        $range = $request->get('range', 1);
        $searchParams = [];
        
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $searchParams[$key] = $value;
            }
        }
        
        session(['sales_report_search_' . $range => $searchParams]);
        
        return response()->json(['success' => true]);
    }

    public function comparisonData(Request $request) {
        try {
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info('Comparison Data Request', [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build Range 1 Query
            $range1_query = Sales::query();
            if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                $range1_query->where('document_date', '>=', $range1_from);
            }
            if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                $range1_query->where('document_date', '<=', $range1_to);
            }
            if ($sales_status) {
                $range1_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range1_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range1_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range1_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Build Range 2 Query
            $range2_query = Sales::query();
            if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                $range2_query->where('document_date', '>=', $range2_from);
            }
            if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                $range2_query->where('document_date', '<=', $range2_to);
            }
            if ($sales_status) {
                $range2_query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $range2_query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $range2_query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $range2_query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            $result = [
                'range1' => [
                    'count' => (int) $range1_query->count(),
                    'total_sales_amount' => (float) ($range1_query->sum('base_sales_amount') ?? 0),
                ],
                'range2' => [
                    'count' => (int) $range2_query->count(),
                    'total_sales_amount' => (float) ($range2_query->sum('base_sales_amount') ?? 0),
                ],
            ];

            Log::info('Comparison Results', $result);

            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            Log::error('Comparison Data Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function comparisonDataTables(Request $request) {
        try {
            $range = $request->get('range', 1);
            
            // Get all parameters
            $range1_from = $request->get('range1_from');
            $range1_to   = $request->get('range1_to');
            $range2_from = $request->get('range2_from');
            $range2_to   = $request->get('range2_to');
            $sales_status = $request->get('sales_status');
            $customer = $request->get('customer');
            $product = $request->get('product');
            $warehouse = $request->get('warehouse');

            Log::info("DataTables Range $range Request", [
                'range1_from' => $range1_from,
                'range1_to' => $range1_to,
                'range2_from' => $range2_from,
                'range2_to' => $range2_to,
                'sales_status' => $sales_status,
                'customer' => $customer,
                'product' => $product,
                'warehouse' => $warehouse
            ]);

            // Build query based on range
            $query = Sales::query();
            
            if ($range == 1) {
                if ($range1_from && PersianCalendarHelper::isValidPersianDate($range1_from)) {
                    $query->where('document_date', '>=', $range1_from);
                }
                if ($range1_to && PersianCalendarHelper::isValidPersianDate($range1_to)) {
                    $query->where('document_date', '<=', $range1_to);
                }
            } else {
                if ($range2_from && PersianCalendarHelper::isValidPersianDate($range2_from)) {
                    $query->where('document_date', '>=', $range2_from);
                }
                if ($range2_to && PersianCalendarHelper::isValidPersianDate($range2_to)) {
                    $query->where('document_date', '<=', $range2_to);
                }
            }

            // Apply additional filters
            if ($sales_status) {
                $query->where('sales_status', $sales_status);
            }
            if ($customer) {
                $query->where('customer_name', 'LIKE', '%' . $customer . '%');
            }
            if ($product) {
                $query->where('product_name', 'LIKE', '%' . $product . '%');
            }
            if ($warehouse) {
                $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
            }

            // Apply column-specific search from request or session
            $searchParams = session('sales_report_search_' . $range, []);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $searchParams[$key] = $value;
                }
            }
            
            foreach ($searchParams as $key => $value) {
                if (strpos($key, 'column_search_') === 0) {
                    $column = str_replace('column_search_', '', $key);
                    if (!empty($value)) {
                        $value = urldecode($value);
                        // Apply search to various columns
                        $searchableColumns = [
                            'sales_id', 'document_number', 'product_name', 'customer_name',
                            'main_quantity', 'base_price', 'base_sales_amount', 'base_net_amount',
                            'document_type', 'sales_type', 'product_code', 'product_barcode',
                            'tracking_code', 'main_unit', 'warehouse', 'base_tax_amount',
                            'base_duty_amount', 'base_additional_amount', 'base_increasing_factors',
                            'month', 'description', 'sales_status'
                        ];
                        
                        if (in_array($column, $searchableColumns)) {
                            if ($column === 'creator') {
                                // Special handling for creator
                                $query->whereHas('creator', function($q) use ($value) {
                                    $q->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                                });
                            } else {
                                $query->where($column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                }
            }

            $totalRecords = $query->count();
            
            Log::info("Range $range Total Records", ['total' => $totalRecords]);

            // Apply sorting
            $sortSettings = session('sales_report_sort_' . $range);
            $orderby = $request->get('orderby', $sortSettings['orderby'] ?? 'document_date');
            $sortorder = $request->get('sortorder', $sortSettings['sortorder'] ?? 'desc');
            
            // Handle special sort columns
            if ($orderby === 'creator') {
                $query->join('users', 'sales.sales_creatorid', '=', 'users.id')
                      ->orderBy('users.first_name', $sortorder)
                      ->select('sales.*');
            } else {
                $query->orderBy($orderby, $sortorder);
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            
            $sales = $query->with(['creator:id,first_name,last_name'])
                          ->skip($start)
                          ->take($length)
                          ->get();

            // Format data
            $data = [];
            foreach ($sales as $item) {
                $data[] = [
                    'sales_id' => $item->sales_id,
                    'product_name' => $item->product_name ?? '',
                    'customer_name' => $item->customer_name ?? '',
                    'document_number' => $item->document_number ?? '',
                    'main_quantity' => $item->main_quantity ?? 0,
                    'base_price' => $item->base_price ?? 0,
                    'base_sales_amount' => $item->base_sales_amount ?? 0,
                    'base_net_amount' => $item->base_net_amount ?? 0,
                    'base_tax_amount' => $item->base_tax_amount ?? 0,
                    'base_duty_amount' => $item->base_duty_amount ?? 0,
                    'sales_status' => $item->sales_status ?? '',
                    'document_type' => $item->document_type ?? '',
                    'sales_type' => $item->sales_type ?? '',
                    'product_code' => $item->product_code ?? '',
                    'product_barcode' => $item->product_barcode ?? '',
                    'warehouse' => $item->warehouse ?? '',
                    'document_date' => $item->document_date,
                    'document_date_persian' => $item->document_date, // Already in Persian format
                    'creator' => $item->creator ? $item->creator->first_name . ' ' . $item->creator->last_name : '',
                    'actions' => '<a href="/sales/' . $item->sales_id . '" class="btn btn-sm btn-outline-primary">مشاهده</a>'
                ];
            }

            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function aggregates() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') ];
        $payload = ['page' => $page];
        return response()->view('pages.reports.sales.aggregates', $payload);
    }

    /**
     * Display sales analytics dashboard
     */
    public function analytics() {
        $page = [ 'title' => __('lang.sales') . ' - ' . __('lang.reports') . ' - ' . 'تحلیل‌های فروش' ];
        $payload = ['page' => $page];
        return new \App\Http\Responses\Reports\Sales\SalesAnalyticsResponse($payload);
    }

    /**
     * Build a base query for analytics endpoints with common filters
     */
    private function buildAnalyticsQuery(Request $request) {
        $query = Sales::query();

        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $product = $request->get('product');
        $customer = $request->get('customer');
        $warehouse = $request->get('warehouse');
        $status = $request->get('status');
        $customerCategory = $request->get('customer_category');

        if ($from_date && PersianCalendarHelper::isValidPersianDate($from_date)) {
            $query->where('document_date', '>=', $from_date);
        }
        if ($to_date && PersianCalendarHelper::isValidPersianDate($to_date)) {
            $query->where('document_date', '<=', $to_date);
        }
        if ($product) {
            $query->where('product_name', 'LIKE', '%' . $product . '%');
        }
        if ($customer) {
            $query->where('customer_name', 'LIKE', '%' . $customer . '%');
        }
        if ($warehouse) {
            $query->where('warehouse', 'LIKE', '%' . $warehouse . '%');
        }
        if ($status) {
            $query->where('sales_status', $status);
        }

        if ($customerCategory) {
            $categoryCustomers = $this->categoryService->customersForSlug($customerCategory);
            if (empty($categoryCustomers)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('customer_name', $categoryCustomers);
            }
        }

        return $query;
    }

    /**
     * Provide summary information when a specific focus (product/customer/warehouse) is selected
     */
    public function getFocusSummary(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده خلاصه، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $orderCount = (clone $baseQuery)->count();
            $totalAmount = (clone $baseQuery)->sum('base_sales_amount') ?? 0;
            $totalNetAmount = (clone $baseQuery)->sum('base_net_amount') ?? 0;
            $totalQuantity = (clone $baseQuery)->sum('main_quantity') ?? 0;

            $firstSale = (clone $baseQuery)->min('document_date');
            $lastSale = (clone $baseQuery)->max('document_date');

            $uniqueCustomers = (clone $baseQuery)->distinct()->count('customer_name');
            $uniqueProducts = (clone $baseQuery)->distinct()->count('product_name');
            $uniqueWarehouses = (clone $baseQuery)->distinct()->count('warehouse');

            $topEntities = [];

            if ($focus === 'product') {
                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'customer') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $topEntities['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();

                $topEntities['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(5)
                    ->get();
            }

            $unitLabel = (clone $baseQuery)
                ->whereNotNull('main_unit')
                ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
                ->groupBy('main_unit')
                ->orderByDesc('quantity_sum')
                ->limit(1)
                ->value('main_unit');

            $responseData = [
                'focus' => $focus,
                'label' => $focusValue,
                'order_count' => (int) $orderCount,
                'total_amount' => (float) round($totalAmount, 2),
                'total_net_amount' => (float) round($totalNetAmount, 2),
                'total_quantity' => (float) round($totalQuantity, 2),
                'unit_label' => $unitLabel,
                'first_sale_date' => $firstSale,
                'last_sale_date' => $lastSale,
                'unique_customers' => (int) $uniqueCustomers,
                'unique_products' => (int) $uniqueProducts,
                'unique_warehouses' => (int) $uniqueWarehouses,
                'top_entities' => [
                    'customers' => $topEntities['customers'] ?? [],
                    'products' => $topEntities['products'] ?? [],
                    'warehouses' => $topEntities['warehouses'] ?? [],
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Provide distribution breakdowns for the current focus
     */
    public function getFocusDistributions(Request $request) {
        try {
            $focus = $request->get('focus');

            if (!in_array($focus, ['product', 'customer', 'warehouse'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Focus پارامتر نامعتبر است.',
                ], 422);
            }

            $focusValue = $request->get($focus);

            if (!$focusValue) {
                return response()->json([
                    'success' => false,
                    'error' => 'برای مشاهده توزیع، مقدار تمرکز باید مشخص شود.',
                ], 422);
            }

            $baseQuery = $this->buildAnalyticsQuery($request);

            $distributions = [
                'customers' => [],
                'products' => [],
                'warehouses' => [],
                'statuses' => [],
            ];

            $statusDistribution = (clone $baseQuery)
                ->selectRaw('sales_status as label, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount, MAX(main_unit) as unit_label')
                ->groupBy('sales_status')
                ->orderByDesc('order_count')
                ->get();

            $distributions['statuses'] = $statusDistribution;

            if ($focus === 'product') {
                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'customer') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['warehouses'] = (clone $baseQuery)
                    ->selectRaw('warehouse as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('warehouse')
                    ->where('warehouse', '!=', '')
                    ->groupBy('warehouse')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            } elseif ($focus === 'warehouse') {
                $distributions['products'] = (clone $baseQuery)
                    ->selectRaw('product_name as label, SUM(base_sales_amount) as total_amount, SUM(main_quantity) as total_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('product_name')
                    ->where('product_name', '!=', '')
                    ->groupBy('product_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();

                $distributions['customers'] = (clone $baseQuery)
                    ->selectRaw('customer_name as label, SUM(base_sales_amount) as total_amount, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '')
                    ->groupBy('customer_name')
                    ->orderByDesc('total_amount')
                    ->limit(10)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'focus' => $focus,
                    'label' => $focusValue,
                    'distributions' => $distributions,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Focus Distribution Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get monthly sales trend
     */
    public function getMonthlyTrend(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Get all sales data
            $sales = $query->select('document_date', 'month', 'base_sales_amount')->get();
            
            // Group by year and month manually
            $grouped = [];
            foreach ($sales as $sale) {
                // Extract year from document_date (format: YYYY/MM/DD or YYYY-MM-DD)
                $dateParts = preg_split('/[-\/]/', $sale->document_date);
                $year = $dateParts[0] ?? '1403';
                $month = $sale->month;
                
                $key = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'year' => $year,
                        'month' => (int)$month,
                        'year_month' => $key,
                        'count' => 0,
                        'total_amount' => 0,
                    ];
                }

                $grouped[$key]['count']++;
                $grouped[$key]['total_amount'] += $sale->base_sales_amount;
            }
            
            // Sort grouped data by year_month
            $monthlyData = [];
            foreach ($grouped as $key => $data) {
                $monthlyData[] = $data;
            }
            
            // Sort by year_month
            usort($monthlyData, function($a, $b) {
                return strcmp($a['year_month'], $b['year_month']);
            });

            return response()->json([
                'success' => true,
                'data' => $monthlyData
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly Trend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by product
            $topProducts = $query->selectRaw('product_name, COUNT(*) as sales_count, SUM(main_quantity) as total_quantity, SUM(base_sales_amount) as total_amount, MAX(main_unit) as unit_label')
                ->groupBy('product_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Top Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(Request $request) {
        try {
            $limit = $request->get('limit', 10);
            $query = $this->buildAnalyticsQuery($request);

            // Group by customer
            $topCustomers = $query->selectRaw('customer_name, COUNT(*) as order_count, SUM(base_sales_amount) as total_amount')
                ->groupBy('customer_name')
                ->orderBy('total_amount', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topCustomers
            ]);

        } catch (\Exception $e) {
            Log::error('Top Customers Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get customer percentage analysis (Pareto/ABC Analysis)
     */
    public function getCustomerPercentageAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            $totalSales = (clone $query)->sum('base_sales_amount') ?? 0;
            
            if ($totalSales === 0.0) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_sales' => 0,
                        'total_customers' => 0,
                        'top_10_percentage' => 0,
                        'top_20_percentage' => 0,
                        'class_a_customers' => 0,
                        'class_b_customers' => 0,
                        'class_c_customers' => 0,
                    ],
                ]);
            }

            // Group by customer and calculate amounts
            $customerSales = $query->selectRaw('
                    customer_name,
                    COUNT(*) as order_count,
                    SUM(base_sales_amount) as total_amount
                ')
                ->groupBy('customer_name')
                ->orderByDesc('total_amount')
                ->get();

            $cumulativePercentage = 0.0;
            $analysisData = [];
            
            foreach ($customerSales as $index => $customer) {
                $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
                $cumulativePercentage += $percentage;
                
                $classification = 'C';
                if ($cumulativePercentage <= 80) {
                    $classification = 'A';
                } elseif ($cumulativePercentage <= 95) {
                    $classification = 'B';
                }
                
                $analysisData[] = [
                    'rank' => $index + 1,
                    'customer_name' => $customer->customer_name,
                    'order_count' => (int) $customer->order_count,
                    'total_amount' => (float) $customer->total_amount,
                    'percentage' => round($percentage, 2),
                    'cumulative_percentage' => round($cumulativePercentage, 2),
                    'classification' => $classification,
                ];
            }

            $summary = [
                'total_sales' => (float) $totalSales,
                'total_customers' => count($customerSales),
                'top_10_percentage' => 0,
                'top_20_percentage' => 0,
                'class_a_customers' => 0,
                'class_b_customers' => 0,
                'class_c_customers' => 0,
            ];

            if (count($analysisData) > 0) {
                $top10 = array_slice($analysisData, 0, min(10, count($analysisData)));
                $summary['top_10_percentage'] = round(array_sum(array_column($top10, 'percentage')), 2);
                
                $top20Count = max(1, (int) ceil(count($analysisData) * 0.2));
                $top20 = array_slice($analysisData, 0, $top20Count);
                $summary['top_20_percentage'] = round(array_sum(array_column($top20, 'percentage')), 2);
                
                foreach ($analysisData as $item) {
                    if ($item['classification'] === 'A') {
                        $summary['class_a_customers']++;
                    } elseif ($item['classification'] === 'B') {
                        $summary['class_b_customers']++;
                    } else {
                        $summary['class_c_customers']++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $analysisData,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            Log::error('Customer Percentage Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get profit analysis
     */
    public function getProfitAnalysis(Request $request) {
        try {
            $query = $this->buildAnalyticsQuery($request);

            // Calculate profit by product
            $profitData = $query->selectRaw('product_name, SUM(base_net_amount) as net_amount, SUM(base_sales_amount) as sales_amount, SUM(base_net_amount - base_sales_amount) as profit, COUNT(*) as count')
                ->groupBy('product_name')
                ->orderBy('profit', 'desc')
                ->limit(15)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $profitData
            ]);

        } catch (\Exception $e) {
            Log::error('Profit Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get seasonal analysis (by quarter/season)
     */
    public function getSeasonalAnalysis(Request $request) {
        try {
            $sales = $this->buildAnalyticsQuery($request)->get();

            // Group by season (فصل)
            $seasonalData = [
                'spring' => ['name' => 'بهار', 'count' => 0, 'total' => 0, 'months' => [1, 2, 3]], // فروردین، اردیبهشت، خرداد
                'summer' => ['name' => 'تابستان', 'count' => 0, 'total' => 0, 'months' => [4, 5, 6]], // تیر، مرداد، شهریور
                'autumn' => ['name' => 'پاییز', 'count' => 0, 'total' => 0, 'months' => [7, 8, 9]], // مهر، آبان، آذر
                'winter' => ['name' => 'زمستان', 'count' => 0, 'total' => 0, 'months' => [10, 11, 12]], // دی، بهمن، اسفند
            ];

            foreach ($sales as $sale) {
                $month = (int) $sale->month;
                foreach ($seasonalData as $key => $season) {
                    if (in_array($month, $season['months'])) {
                        $seasonalData[$key]['count']++;
                        $seasonalData[$key]['total'] += $sale->base_sales_amount;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => array_values($seasonalData)
            ]);

        } catch (\Exception $e) {
            Log::error('Seasonal Analysis Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivery status analysis
     */
    public function getDeliveryStatus(Request $request) {
        try {
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');
            $product = $request->get('product');
            $customer = $request->get('customer');
            $warehouse = $request->get('warehouse');
            $status = $request->get('status');

            $query = $this->buildAnalyticsQuery($request);

            // Calculate delivery statistics
            $stats = (clone $query)->selectRaw('
                COUNT(*) as total_orders,
                SUM(issued_main_quantity) as total_issued,
                SUM(remaining_main_quantity) as total_remaining,
                SUM(main_quantity) as total_quantity
            ')->first();

            $deliveryRate = 0;
            if ($stats->total_quantity > 0) {
                $deliveryRate = ($stats->total_issued / $stats->total_quantity) * 100;
            }

            $unitLabel = (clone $query)
                ->whereNotNull('main_unit')
                ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
                ->groupBy('main_unit')
                ->orderByDesc('quantity_sum')
                ->limit(1)
                ->value('main_unit');

            if ($stats) {
                $stats->unit_label = $unitLabel;
            }

            // Top products with pending delivery
            $pendingProducts = (clone $query)->selectRaw('product_name, SUM(remaining_main_quantity) as pending_quantity, COUNT(*) as order_count, MAX(main_unit) as unit_label')
                ->where('remaining_main_quantity', '>', 0)
                ->groupBy('product_name')
                ->orderBy('pending_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'delivery_rate' => round($deliveryRate, 2),
                    'pending_products' => $pendingProducts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test analytics data - for debugging
     */
    public function testAnalyticsData(Request $request) {
        try {
            // Get total count
            $totalCount = Sales::count();
            
            // Get date range
            $minDate = Sales::min('document_date');
            $maxDate = Sales::max('document_date');
            
            // Sample data
            $sampleData = Sales::orderBy('document_date', 'desc')->limit(5)->get();
            
            // Monthly distribution
            $monthlyCount = Sales::selectRaw('month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            return response()->json([
                'success' => true,
                'total_records' => $totalCount,
                'date_range' => [
                    'min' => $minDate,
                    'max' => $maxDate
                ],
                'sample_data' => $sampleData,
                'monthly_distribution' => $monthlyCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
```

```blade
<!-- Sales Analytics Dashboard -->
<div class="row" id="sales-analytics-dashboard">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="ti-bar-chart"></i> تحلیل‌های فروش و نمودارهای تحلیلی
                    </h4>
                    <div class="d-flex align-items-center">
                        <div id="filter-status" class="badge bg-info mr-2" style="display: none;">
                            <i class="ti-filter"></i> <span id="filter-text">فیلتر اعمال شده</span>
                        </div>
                        <div id="focus-active-badge" class="badge bg-warning mr-2" style="display: none;">
                            <i class="ti-target"></i> <span id="focus-active-text">تمرکز فعال</span>
                        </div>
                        <button id="test-data-btn" class="btn btn-sm btn-outline-secondary" title="تست داده‌ها">
                            <i class="ti-bug"></i> Debug
                        </button>
                    </div>
                </div>
                
                <!-- Date Range Filters -->
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label">شیوه فیلتر تاریخ</label>
                        <div class="btn-group btn-group-sm w-100" role="group" aria-label="حالت فیلتر تاریخ">
                            <button type="button" class="btn btn-primary" id="filter-mode-year-month" data-mode="year_month">
                                <i class="ti-calendar"></i> سال / ماه
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="filter-mode-exact" data-mode="exact">
                                <i class="ti-time"></i> تاریخ دقیق
                            </button>
                        </div>
                    </div>
                    <!-- Year Selector -->
                    <div class="col-md-3 filter-year-month-group">
                        <label class="form-label">انتخاب سال</label>
                        <select id="analytics_year" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="1400">1400</option>
                            <option value="1401">1401</option>
                            <option value="1402">1402</option>
                            <option value="1403">1403</option>
                            <option value="1404">1404</option>
                            <option value="1405">1405</option>
                        </select>
                    </div>
                    
                    <!-- Month Range -->
                    <div class="col-md-3 filter-year-month-group">
                        <label class="form-label">از ماه</label>
                        <select id="analytics_from_month" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="1">فروردین</option>
                            <option value="2">اردیبهشت</option>
                            <option value="3">خرداد</option>
                            <option value="4">تیر</option>
                            <option value="5">مرداد</option>
                            <option value="6">شهریور</option>
                            <option value="7">مهر</option>
                            <option value="8">آبان</option>
                            <option value="9">آذر</option>
                            <option value="10">دی</option>
                            <option value="11">بهمن</option>
                            <option value="12">اسفند</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 filter-year-month-group">
                        <label class="form-label">تا ماه</label>
                        <select id="analytics_to_month" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="1">فروردین</option>
                            <option value="2">اردیبهشت</option>
                            <option value="3">خرداد</option>
                            <option value="4">تیر</option>
                            <option value="5">مرداد</option>
                            <option value="6">شهریور</option>
                            <option value="7">مهر</option>
                            <option value="8">آبان</option>
                            <option value="9">آذر</option>
                            <option value="10">دی</option>
                            <option value="11">بهمن</option>
                            <option value="12">اسفند</option>
                        </select>
                    </div>
</div>

                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-4 filter-exact-group">
                        <label class="form-label">از تاریخ (دقیق)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="analytics_from_date" class="form-control persian-date-input" 
                                   placeholder="لطفاً تاریخ شروع دقیق را وارد کنید" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_from_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4 filter-exact-group">
                        <label class="form-label">تا تاریخ (دقیق)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="analytics_to_date" class="form-control persian-date-input" 
                                   placeholder="لطفاً تاریخ پایان دقیق را وارد کنید" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_to_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end justify-content-md-end">
                        <div class="w-100 w-md-auto">
                            <button id="update-analytics" class="btn btn-primary w-100">
                                <i class="ti-reload"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Filters -->
                <div class="row mb-3">
                    <div class="col-md-3 order-md-1">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>دسته‌بندی مشتری</span>
                            <small class="text-muted" id="customer-category-count"></small>
                        </label>
                        <select id="filter_customer_category" class="form-control form-control-sm">
                            <option value="">تمام دسته‌ها</option>
                        </select>
                    </div>
                    <div class="col-md-3 order-md-2">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر مشتری</span>
                            <small class="text-muted" id="customer-count"></small>
                        </label>
                        <select id="filter_customer" class="form-control form-control-sm">
                            <option value="">انتخاب کنید</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3 order-md-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر انبار</span>
                            <small class="text-muted" id="warehouse-count"></small>
                        </label>
                        <select id="filter_warehouse" class="form-control form-control-sm">
                            <option value="">انتخاب کنید</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3 order-md-4">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر محصول</span>
                            <small class="text-muted" id="product-count"></small>
                        </label>
                        <select id="filter_product" class="form-control form-control-sm">
                            <option value="">انتخاب کنید</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary" id="set-current-year">
                                <i class="ti-calendar"></i> سال جاری
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="set-last-year">
                                <i class="ti-back-left"></i> سال گذشته
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="set-current-quarter">
                                <i class="ti-layout-grid2"></i> فصل جاری
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="refresh-filters">
                                <i class="ti-reload"></i> بروزرسانی لیست‌ها
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clear-filters">
                                <i class="ti-close"></i> پاک کردن فیلترها
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-link" id="toggle-help-text">
                            <i class="ti-help-alt"></i> راهنما
                        </button>
                    </div>
                </div>
                
                <!-- Help Text (Collapsible) -->
                <div class="row mb-2" id="filter-help" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="ti-info-alt"></i> نحوه استفاده از فیلترها:</strong>
                            <ul class="mb-0 mt-2" style="font-size: 13px;">
                                <li><strong>فیلتر زمانی - روش 1 (پیشنهادی):</strong> سال و بازه ماه را انتخاب کنید → مناسب برای نمایش یک سال کامل</li>
                                <li><strong>فیلتر زمانی - روش 2:</strong> تاریخ دقیق "از" و "تا" را وارد کنید → مناسب برای بازه‌های سفارشی (چند ساله)</li>
                                <li><strong>فیلتر محصول:</strong> از لیست ComboBox محصول مورد نظر را انتخاب کنید → تحلیل فقط برای این محصول</li>
                                <li><strong>فیلتر مشتری:</strong> از لیست ComboBox مشتری مورد نظر را انتخاب کنید → تحلیل فقط برای این مشتری</li>
                                <li><strong>فیلتر انبار:</strong> از لیست ComboBox انبار مورد نظر را انتخاب کنید → تحلیل فقط برای این انبار</li>
                                <li><strong>وضعیت فروش:</strong> فیلتر بر اساس وضعیت (تکمیل شده، در انتظار، لغو شده)</li>
                                <li><strong>بروزرسانی لیست‌ها:</strong> اگر تاریخ را تغییر دادید، روی این دکمه کلیک کنید تا لیست محصولات/مشتریان/انبارها بر اساس بازه تاریخی جدید به‌روز شوند</li>
                                <li><strong>نکته:</strong> می‌توانید چند فیلتر را با هم ترکیب کنید (مثلاً: سال 1403 + محصول خاص + انبار مرکزی)</li>
                                <li><strong>تعداد موارد:</strong> کنار هر فیلتر تعداد موارد موجود نمایش داده می‌شود</li>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="mb-4">

                <!-- Focus Summary -->
                <div id="focus-summary-wrapper" class="card mb-4 focus-summary-card" style="display: none;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1" id="focus-summary-title">تمرکز فعال</h5>
                            <small class="text-muted" id="focus-summary-subtitle"></small>
                        </div>
                        <span class="badge badge-warning">
                            <i class="ti-target"></i> تمرکز فعال
                        </span>
                    </div>
                    <div class="card-body position-relative">
                        <div id="focus-summary-loading" class="focus-summary-loading" style="display: none;">
                            <div class="text-center">
                                <div class="spinner-border text-primary"></div>
                                <p class="mt-2 mb-0">در حال بارگذاری خلاصه تمرکز...</p>
                            </div>
                        </div>
                        <div id="focus-summary-content">
                            <div class="row focus-summary-metrics">
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="focus-metric-card">
                                        <span class="focus-metric-label">کل فروش</span>
                                        <h4 class="focus-metric-value" id="focus-total-amount">-</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="focus-metric-card">
                                        <span class="focus-metric-label">تعداد سفارش</span>
                                        <h4 class="focus-metric-value" id="focus-order-count">-</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="focus-metric-card">
                                        <span class="focus-metric-label">کل مقدار</span>
                                        <h4 class="focus-metric-value" id="focus-total-quantity">-</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row focus-summary-meta">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">مشتریان یکتا</span>
                                        <span class="focus-meta-value" id="focus-unique-customers">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">محصولات یکتا</span>
                                        <span class="focus-meta-value" id="focus-unique-products">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">انبارهای یکتا</span>
                                        <span class="focus-meta-value" id="focus-unique-warehouses">-</span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="focus-meta-card">
                                        <span class="focus-meta-label">دوره زمانی</span>
                                        <span class="focus-meta-value" id="focus-date-range">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3" id="focus-top-entities">
                                <div class="col-md-4 mb-3" id="focus-top-customers" style="display: none;">
                                    <h6 class="focus-top-title">
                                        <i class="ti-user"></i> مشتریان برتر
                                    </h6>
                                    <ul class="list-group list-group-sm" id="focus-top-customers-list"></ul>
                                </div>
                                <div class="col-md-4 mb-3" id="focus-top-products" style="display: none;">
                                    <h6 class="focus-top-title">
                                        <i class="ti-package"></i> محصولات برتر
                                    </h6>
                                    <ul class="list-group list-group-sm" id="focus-top-products-list"></ul>
                                </div>
                                <div class="col-md-4 mb-3" id="focus-top-warehouses" style="display: none;">
                                    <h6 class="focus-top-title">
                                        <i class="ti-home"></i> انبارهای برتر
                                    </h6>
                                    <ul class="list-group list-group-sm" id="focus-top-warehouses-list"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="analyticsTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="time-tab" data-toggle="tab" href="#time-analytics" 
                           role="tab" aria-controls="time-analytics" aria-selected="true">
                            <i class="ti-time"></i> تحلیل زمانی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="products-tab" data-toggle="tab" href="#products-analytics" 
                           role="tab" aria-controls="products-analytics" aria-selected="false">
                            <i class="ti-package"></i> تحلیل محصولات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers-analytics" 
                           role="tab" aria-controls="customers-analytics" aria-selected="false">
                            <i class="ti-user"></i> تحلیل مشتریان
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial-analytics" 
                           role="tab" aria-controls="financial-analytics" aria-selected="false">
                            <i class="ti-money"></i> تحلیل مالی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="logistics-tab" data-toggle="tab" href="#logistics-analytics" 
                           role="tab" aria-controls="logistics-analytics" aria-selected="false">
                            <i class="ti-truck"></i> تحلیل لجستیک
                        </a>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content mt-4" id="analyticsTabContent">
                    <!-- Time Analytics Tab -->
                    <div class="tab-pane fade show active" id="time-analytics" role="tabpanel" aria-labelledby="time-tab">
                        @include('pages.reports.sales.analytics-time')
                    </div>

                    <!-- Products Analytics Tab -->
                    <div class="tab-pane fade" id="products-analytics" role="tabpanel" aria-labelledby="products-tab">
                        @include('pages.reports.sales.analytics-products')
                    </div>

                    <!-- Customers Analytics Tab -->
                    <div class="tab-pane fade" id="customers-analytics" role="tabpanel" aria-labelledby="customers-tab">
                        @include('pages.reports.sales.analytics-customers')
                    </div>

                    <!-- Financial Analytics Tab -->
                    <div class="tab-pane fade" id="financial-analytics" role="tabpanel" aria-labelledby="financial-tab">
                        @include('pages.reports.sales.analytics-financial')
                    </div>

                    <!-- Logistics Analytics Tab -->
                    <div class="tab-pane fade" id="logistics-analytics" role="tabpanel" aria-labelledby="logistics-tab">
                        @include('pages.reports.sales.analytics-logistics')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Persian Date Picker and Analytics Scripts -->
<script>
// Setup CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Persian months for labels
const persianMonths = [
    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
];

// Ensure numeric values are safe before using them inside charts or UI components
window.toFiniteNumber = window.toFiniteNumber || function(value, fallback = 0) {
    if (value === null || value === undefined || value === '') {
        return fallback;
    }

    let numericValue = value;
    if (typeof numericValue === 'string') {
        numericValue = numericValue.replace(/[,\s]/g, '');
    }

    const parsed = Number(numericValue);
    return Number.isFinite(parsed) ? parsed : fallback;
};

window.toPercentageValue = window.toPercentageValue || function(value, digits = 1) {
    const base = window.toFiniteNumber(value, 0);
    const factor = Math.pow(10, digits);
    return Math.round(base * factor) / factor;
};

window.clampPercentage = window.clampPercentage || function(value, digits = 1) {
    const percentage = window.toPercentageValue(value, digits);
    return Math.min(100, Math.max(0, percentage));
};

// Format numbers with Persian separators
function formatNumber(num) {
    const safeNum = window.toFiniteNumber(num, 0);
    return new Intl.NumberFormat('fa-IR').format(safeNum);
}

function formatCurrency(num) {
    const safeNum = Math.round(window.toFiniteNumber(num, 0));
    return formatNumber(safeNum) + ' ریال';
}

function formatDecimal(num, digits = 2) {
    const safeNum = window.toFiniteNumber(num, 0);
    return new Intl.NumberFormat('fa-IR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: digits
    }).format(safeNum);
}

function formatPercentageValue(value, digits = 1) {
    const safePercentage = window.clampPercentage(value, digits);
    return new Intl.NumberFormat('fa-IR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: digits
    }).format(safePercentage);
}

function getUnitLabel(source, fallback = 'واحد') {
    if (!source || typeof source !== 'object') {
        return fallback;
    }

    const candidateKeys = ['unit_label', 'main_unit', 'unit', 'measurement_unit', 'unitName'];
    for (let i = 0; i < candidateKeys.length; i++) {
        const key = candidateKeys[i];
        if (source[key] && typeof source[key] === 'string' && source[key].trim() !== '') {
            return source[key].trim();
        }
    }

    return fallback;
}

function getDominantUnitLabel(items = [], fallback = 'واحد') {
    if (!Array.isArray(items) || items.length === 0) {
        return fallback;
    }

    const unitCounter = items.reduce((acc, item) => {
        const unit = getUnitLabel(item, fallback);
        acc[unit] = (acc[unit] || 0) + window.toFiniteNumber(item.total_quantity || item.quantity || 1, 0);
        return acc;
    }, {});

    const dominantUnit = Object.entries(unitCounter)
        .sort((a, b) => b[1] - a[1])
        .map(entry => entry[0])[0];

    return dominantUnit || fallback;
}

function formatQuantityValue(quantity, unitLabel = 'واحد', digits = 2) {
    const value = window.toFiniteNumber(quantity, 0);
    if (value === 0) {
        return `0 ${unitLabel}`;
    }
    return `${formatDecimal(value, digits)} ${unitLabel}`;
}

let focusDataLoading = false;
let currentFilterMode = 'year_month';
let hasTriggeredUpdate = false;
const analyticsCsrfToken = $('meta[name="csrf-token"]').attr('content');

function getActiveFocus() {
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();

    // Only one focus at a time
    const focusSelections = [
        product ? 'product' : null,
        customer ? 'customer' : null,
        warehouse ? 'warehouse' : null
    ].filter(Boolean);

    if (focusSelections.length !== 1) {
        return null;
    }

    const focusType = focusSelections[0];
    const valueMap = {
        product: product,
        customer: customer,
        warehouse: warehouse
    };

    return {
        type: focusType,
        value: valueMap[focusType]
    };
}

function truncateLabel(label, maxLength = 40) {
    if (!label || typeof label !== 'string') {
        return '-';
    }
    return label.length > maxLength ? label.substring(0, maxLength) + '…' : label;
}

function getFocusTypeLabel(focusType) {
    switch (focusType) {
        case 'product':
            return 'محصول';
        case 'customer':
            return 'مشتری';
        case 'warehouse':
            return 'انبار';
        default:
            return 'تمرکز';
    }
}

function showFocusLoading() {
    $('#focus-summary-wrapper').show();
    $('#focus-summary-loading').show();
    $('#focus-summary-content').css('opacity', 0.2);
}

function hideFocusSummary() {
    $('#focus-summary-wrapper').hide();
    $('#focus-summary-loading').hide();
    $('#focus-summary-content').css('opacity', 1);
    $('#focus-top-customers-list, #focus-top-products-list, #focus-top-warehouses-list').empty();
    $('#focus-top-customers, #focus-top-products, #focus-top-warehouses').hide();
    applyFocusBadges(null);
}

function applyFocusBadges(focus, summaryData = null) {
    $('#filter_product, #filter_customer, #filter_warehouse, #filter_customer_category').removeClass('focus-highlight');
    $('#focus-active-badge').hide();

    if (!focus) {
        return;
    }

    const focusLabel = getFocusTypeLabel(focus.type);
    const summaryLabel = summaryData && summaryData.label ? summaryData.label : focus.value;
    const displayLabel = truncateLabel(summaryLabel, 45);

    $('#focus-active-text').text(`${focusLabel}: ${displayLabel}`);
    $('#focus-active-badge').fadeIn(150);

    if (focus.type === 'product') {
        $('#filter_product').addClass('focus-highlight');
    } else if (focus.type === 'customer') {
        $('#filter_customer').addClass('focus-highlight');
        $('#filter_customer_category').addClass('focus-highlight');
    } else if (focus.type === 'warehouse') {
        $('#filter_warehouse').addClass('focus-highlight');
    }
}

function renderFocusList(containerSelector, list, options = {}) {
    const $container = $(containerSelector);
    if (!$container.length) {
        return;
    }

    const $list = $container.find('ul');
    if (!list || !Array.isArray(list) || list.length === 0) {
        $list.empty();
        $container.hide();
        return;
    }

    $list.empty();
    list.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 45);
        const totalAmount = formatCurrency(window.toFiniteNumber(item.total_amount, 0));
        const quantityKey = options.quantityKey;
        const unitLabel = getUnitLabel(item);
        const quantityValue = quantityKey && item[quantityKey] ? window.toFiniteNumber(item[quantityKey], 0) : 0;
        const quantityLabel = quantityKey && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const countLabel = item.order_count ? formatNumber(window.toFiniteNumber(item.order_count, 0)) + ' سفارش' : '';

        const badgeHtml = `<span class="badge badge-light mr-2">${index + 1}</span>`;
        const metaHtml = [quantityLabel, countLabel].filter(Boolean).map(text => `<small class="text-muted d-block">${text}</small>`).join('');

        const itemHtml = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    ${badgeHtml}
                    <div class="d-flex flex-column">
                        <span>${label}</span>
                        ${metaHtml}
                    </div>
                </div>
                <span class="text-muted">${totalAmount}</span>
            </li>
        `;
        $list.append(itemHtml);
    });

    $container.fadeIn(150);
}

function renderFocusSummary(summary) {
    if (!summary) {
        hideFocusSummary();
        return;
    }

    $('#focus-summary-wrapper').show();
    $('#focus-summary-loading').hide();
    $('#focus-summary-content').css('opacity', 1);

    const focusType = summary.focus;
    const focusLabel = getFocusTypeLabel(focusType);
    const titleMap = {
        product: 'تحلیل اختصاصی محصول',
        customer: 'تحلیل اختصاصی مشتری',
        warehouse: 'تحلیل اختصاصی انبار'
    };

    $('#focus-summary-title').text(titleMap[focusType] || 'تحلیل تمرکز فعال');
    $('#focus-summary-subtitle').text(`نمایش اطلاعات برای ${focusLabel}: ${truncateLabel(summary.label || '-', 60)}`);

    $('#focus-total-amount').text(formatCurrency(summary.total_amount));
    $('#focus-order-count').text(formatNumber(summary.order_count || 0));
    const summaryUnitLabel = getUnitLabel(summary);
    $('#focus-total-quantity').text(summary.total_quantity ? formatQuantityValue(summary.total_quantity, summaryUnitLabel) : '-');

    $('#focus-unique-customers').text(formatNumber(summary.unique_customers || 0));
    $('#focus-unique-products').text(formatNumber(summary.unique_products || 0));
    $('#focus-unique-warehouses').text(formatNumber(summary.unique_warehouses || 0));

    const dateRange = summary.first_sale_date && summary.last_sale_date
        ? `${summary.first_sale_date} تا ${summary.last_sale_date}`
        : '-';
    $('#focus-date-range').text(dateRange);

    const topEntities = summary.top_entities || {};
    renderFocusList('#focus-top-customers', topEntities.customers || []);
    renderFocusList('#focus-top-products', topEntities.products || [], { quantityKey: 'total_quantity' });
    renderFocusList('#focus-top-warehouses', topEntities.warehouses || []);

    applyFocusBadges({ type: focusType, value: summary.label }, summary);
}

function loadFocusData(filters) {
    const focus = getActiveFocus();
    window.currentFocus = null;
    window.focusSummary = null;
    window.focusDistributions = null;

    if (!focus) {
        hideFocusSummary();
        return $.Deferred().resolve().promise();
    }

    const payload = Object.assign({}, filters, { focus: focus.type });
    payload[focus.type] = focus.value;

    showFocusLoading();
    focusDataLoading = true;

    const summaryRequest = $.ajax({
        url: '/report/sales/analytics/focus-summary',
        method: 'POST',
        headers: analyticsCsrfToken ? { 'X-CSRF-TOKEN': analyticsCsrfToken } : {},
        data: payload
    });

    const distributionRequest = $.ajax({
        url: '/report/sales/analytics/focus-distributions',
        method: 'POST',
        headers: analyticsCsrfToken ? { 'X-CSRF-TOKEN': analyticsCsrfToken } : {},
        data: payload
    });

    return $.when(summaryRequest, distributionRequest)
        .done(function(summaryResponse, distributionResponse) {
            const summaryData = summaryResponse[0];
            const distributionData = distributionResponse[0];

            if (summaryData && summaryData.success) {
                window.currentFocus = focus;
                window.focusSummary = summaryData.data;
                renderFocusSummary(summaryData.data);
                if (summaryData.data && summaryData.data.focus === 'product') {
                    window.productsChartsLoaded = false;
                    loadProductsAnalytics();
                    window.financialChartsLoaded = false;
                    loadFinancialAnalytics();
                } else if (summaryData.data && summaryData.data.focus === 'customer') {
                    window.customersChartsLoaded = false;
                    loadCustomersAnalytics();
                    window.financialChartsLoaded = false;
                    loadFinancialAnalytics();
                } else if (summaryData.data && summaryData.data.focus === 'warehouse') {
                    window.logisticsChartsLoaded = false;
                    loadLogisticsAnalytics();
                }
            } else {
                hideFocusSummary();
            }

            if (distributionData && distributionData.success) {
                window.focusDistributions = distributionData.data;
            }
        })
        .fail(function(xhr) {
            console.error('Focus data error:', xhr);
            hideFocusSummary();
        })
        .always(function() {
            focusDataLoading = false;
        });
}

// Tab switching is now handled in $(document).ready() above

// Update charts button
$('#update-analytics').on('click', function() {
    // Find which tab pane is active
    let activeTabId = '';
    $('.tab-pane').each(function() {
        if ($(this).hasClass('show') && $(this).hasClass('active')) {
            activeTabId = '#' + $(this).attr('id');
        }
    });
    
    const year = $('#analytics_year').val();
    const fromMonth = $('#analytics_from_month').val();
    const toMonth = $('#analytics_to_month').val();
    const fromDate = $('#analytics_from_date').val();
    const toDate = $('#analytics_to_date').val();
    
    console.log('Update button clicked');
    console.log('Active tab ID:', activeTabId);
    console.log('Filter - Year:', year, 'From Month:', fromMonth, 'To Month:', toMonth);
    console.log('Filter - From date:', fromDate, 'To date:', toDate);
    
    // Show filter status
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();
    const filters = getFilterDates();
    
    let filterText = '';
    let filterParts = [];
    
    if (currentFilterMode === 'exact') {
        // Using exact dates
        if (fromDate && toDate) {
            filterParts.push(`${fromDate} تا ${toDate}`);
        } else if (fromDate) {
            filterParts.push(`از ${fromDate}`);
        } else if (toDate) {
            filterParts.push(`تا ${toDate}`);
        }
    } else if (year) {
        // Using year/month
        let yearText = `سال ${year}`;
        if (fromMonth && toMonth) {
            if (fromMonth === toMonth) {
                yearText += ` - ${persianMonths[fromMonth - 1]}`;
            } else {
                yearText += ` - ${persianMonths[fromMonth - 1]} تا ${persianMonths[toMonth - 1]}`;
            }
        } else if (fromMonth) {
            yearText += ` - از ${persianMonths[fromMonth - 1]}`;
        } else if (toMonth) {
            yearText += ` - تا ${persianMonths[toMonth - 1]}`;
        }
        filterParts.push(yearText);
    }
    
    // Add additional filter info
    if (customer) filterParts.push(`مشتری: ${customer}`);
    if (warehouse) filterParts.push(`انبار: ${warehouse}`);
    if (product) filterParts.push(`محصول: ${product}`);
    const customerCategoryValue = $('#filter_customer_category').val();
    if (customerCategoryValue) {
        const categoryLabel = $('#filter_customer_category option:selected').text();
        filterParts.push(`دسته‌بندی: ${categoryLabel}`);
    }
    
    if (filterParts.length > 0) {
        filterText = filterParts.join(' | ');
        $('#filter-text').text(filterText);
        $('#filter-status').show();
    } else {
        $('#filter-status').hide();
    }
    
    $(this).prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...');
    
    // Reset loaded flags to force reload with new filters
    window.timeChartsLoaded = false;
    window.productsChartsLoaded = false;
    window.customersChartsLoaded = false;
    window.financialChartsLoaded = false;
    window.logisticsChartsLoaded = false;
    
    // Hide all tab contents until load completes
    $('.tab-pane .card, .tab-pane .chart-container, #focus-summary-wrapper').hide();
    $('#focus-summary-wrapper').hide();

    // Reload data for active tab
    const loaders = {
        '#time-analytics': loadTimeAnalytics,
        '#products-analytics': loadProductsAnalytics,
        '#customers-analytics': loadCustomersAnalytics,
        '#financial-analytics': loadFinancialAnalytics,
        '#logistics-analytics': loadLogisticsAnalytics
    };
    const loader = loaders[activeTabId] || loadTimeAnalytics;
    const activateContent = () => {
        hasTriggeredUpdate = true;
        $('.tab-pane.show.active .card, .tab-pane.show.active .chart-container').fadeIn(150);
    };
    try {
        const loaderResult = loader();
        if (loaderResult && typeof loaderResult.always === 'function') {
            loaderResult.always(activateContent);
        } else if (loaderResult && typeof loaderResult.finally === 'function') {
            loaderResult.finally(activateContent);
        } else {
            activateContent();
        }
    } catch (error) {
        console.error('Loader execution error:', error);
        activateContent();
    }
    
    setTimeout(() => {
        $(this).prop('disabled', false).html('<i class="ti-reload"></i> به‌روزرسانی نمودارها');
    }, 1000);

    // Detect and load focus-specific data
    loadFocusData({
        ...filters,
        product: product,
        customer: customer,
        warehouse: warehouse,
        customer_category: $('#filter_customer_category').val()
    });
});

// Get current Persian year (approximate calculation)
function getCurrentPersianYear() {
    // Simple conversion: Gregorian year - 621 or 622
    const now = new Date();
    const gregorianYear = now.getFullYear();
    const month = now.getMonth() + 1;
    
    // If before March 21, use year-622, else year-621
    if (month < 3 || (month === 3 && now.getDate() < 21)) {
        return gregorianYear - 622;
    } else {
        return gregorianYear - 621;
    }
}

function updateFilterMode(mode, options = {}) {
    if (!['year_month', 'exact'].includes(mode)) {
        console.warn('Unknown filter mode:', mode);
        return;
    }

    const { preserveYearValues = false, preserveDateValues = false } = options;
    currentFilterMode = mode;

    const $yearInputs = $('#analytics_year, #analytics_from_month, #analytics_to_month');
    const $dateInputs = $('#analytics_from_date, #analytics_to_date');
    const $yearGroups = $('.filter-year-month-group');
    const $dateGroups = $('.filter-exact-group');
    const $yearModeBtn = $('#filter-mode-year-month');
    const $exactModeBtn = $('#filter-mode-exact');

    if (mode === 'year_month') {
        $yearInputs.prop('disabled', false);
        $dateInputs.prop('disabled', true);
        if (!preserveDateValues) {
            $dateInputs.val('');
        }

        $yearGroups.removeClass('filter-group-disabled');
        $dateGroups.addClass('filter-group-disabled');

        $yearModeBtn.addClass('btn-primary active').removeClass('btn-outline-primary');
        $exactModeBtn.removeClass('btn-primary active').addClass('btn-outline-primary');
    } else {
        $yearInputs.prop('disabled', true);
        $dateInputs.prop('disabled', false);
        if (!preserveYearValues) {
            $('#analytics_year').val('');
            $('#analytics_from_month').val('');
            $('#analytics_to_month').val('');
        }

        $yearGroups.addClass('filter-group-disabled');
        $dateGroups.removeClass('filter-group-disabled');

        $exactModeBtn.addClass('btn-primary active').removeClass('btn-outline-primary');
        $yearModeBtn.removeClass('btn-primary active').addClass('btn-outline-primary');
    }
}

// Get all filters including date, product, customer, warehouse
function getFilterDates() {
    const filters = {
        filter_mode: currentFilterMode,
        from_date: '',
        to_date: '',
        year: '',
        from_month: '',
        to_month: ''
    };

    if (currentFilterMode === 'exact') {
        const fromDate = $('#analytics_from_date').val();
        const toDate = $('#analytics_to_date').val();

        filters.from_date = fromDate || '';
        filters.to_date = toDate || '';

        if (fromDate || toDate) {
            console.log('Using custom exact date range:', fromDate, 'to', toDate);
        }
    } else {
        const year = $('#analytics_year').val();
        const fromMonth = $('#analytics_from_month').val();
        const toMonth = $('#analytics_to_month').val();

        filters.year = year;
        filters.from_month = fromMonth;
        filters.to_month = toMonth;

        if (year) {
            const startMonth = (fromMonth || '01').toString().padStart(2, '0');
            const endMonth = (toMonth || '12').toString().padStart(2, '0');
            const endDay = (endMonth === '12') ? '29' : (parseInt(endMonth, 10) <= 6 ? '31' : '30');

            filters.from_date = `${year}/${startMonth}/01`;
            filters.to_date = `${year}/${endMonth}/${endDay}`;

            console.log('Using year/month filters:', filters.from_date, 'to', filters.to_date);
        }
    }

    // Additional filters
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();
    const customerCategory = $('#filter_customer_category').val();
    if (product) {
        filters.product = product;
        console.log('Filter - Product:', product);
    }
    if (customer) {
        filters.customer = customer;
        console.log('Filter - Customer:', customer);
    }
    if (warehouse) {
        filters.warehouse = warehouse;
        console.log('Filter - Warehouse:', warehouse);
    }
    if (customerCategory) {
        filters.customer_category = customerCategory;
        console.log('Filter - Customer Category:', customerCategory);
    }
    
    return filters;
}

$('#filter-mode-year-month').on('click', function() {
    updateFilterMode('year_month');
});

$('#filter-mode-exact').on('click', function() {
    updateFilterMode('exact', { preserveDateValues: true });
});

// Set current year button
$('#set-current-year').on('click', function() {
    updateFilterMode('year_month');
    const currentYear = getCurrentPersianYear();
    
    // Clear custom date inputs
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Set year/month selectors
    $('#analytics_year').val(currentYear);
    $('#analytics_from_month').val('1');
    $('#analytics_to_month').val('12');
    
    console.log('Set to current year:', currentYear);
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// Set last year button
$('#set-last-year').on('click', function() {
    updateFilterMode('year_month');
    const lastYear = getCurrentPersianYear() - 1;
    
    // Clear custom date inputs
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Set year/month selectors
    $('#analytics_year').val(lastYear);
    $('#analytics_from_month').val('1');
    $('#analytics_to_month').val('12');
    
    console.log('Set to last year:', lastYear);
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// Set current quarter button
$('#set-current-quarter').on('click', function() {
    updateFilterMode('year_month');
    const currentYear = getCurrentPersianYear();
    const now = new Date();
    const month = now.getMonth() + 1;
    
    // Simple approximation of Persian month
    let persianMonth = month - 3;
    if (persianMonth <= 0) persianMonth += 12;
    
    // Determine quarter
    let fromMonth, toMonth;
    if (persianMonth <= 3) {
        fromMonth = 1; toMonth = 3; // بهار
    } else if (persianMonth <= 6) {
        fromMonth = 4; toMonth = 6; // تابستان
    } else if (persianMonth <= 9) {
        fromMonth = 7; toMonth = 9; // پاییز
    } else {
        fromMonth = 10; toMonth = 12; // زمستان
    }
    
    // Clear custom date inputs
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Set year/month selectors
    $('#analytics_year').val(currentYear);
    $('#analytics_from_month').val(fromMonth);
    $('#analytics_to_month').val(toMonth);
    
    console.log('Set to current quarter:', currentYear, 'from month', fromMonth, 'to', toMonth);
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// Clear filters button
$('#clear-filters').on('click', function() {
    updateFilterMode('year_month');
    // Clear all filters
    $('#analytics_year').val('');
    $('#analytics_from_month').val('');
    $('#analytics_to_month').val('');
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    $('#filter_customer').val('').trigger('change');
    $('#filter_product').val('').trigger('change');
    $('#filter_warehouse').val('').trigger('change');
    $('#filter-status').hide();
    
    console.log('All filters cleared');
    
    // Auto update charts
    setTimeout(function() {
        $('#update-analytics').click();
    }, 100);
});

// When year/month changes, clear exact dates and highlight
$('#analytics_year, #analytics_from_month, #analytics_to_month').on('change', function() {
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    
    // Visual feedback
    $('#analytics_year, #analytics_from_month, #analytics_to_month').parent().addClass('border-primary');
    setTimeout(() => {
        $('#analytics_year, #analytics_from_month, #analytics_to_month').parent().removeClass('border-primary');
    }, 1000);
    
    console.log('Year/Month changed, custom dates cleared');
});

// When exact dates change, clear year/month and highlight
$('#analytics_from_date, #analytics_to_date').on('change', function() {
    if ($('#analytics_from_date').val() || $('#analytics_to_date').val()) {
        $('#analytics_year').val('');
        $('#analytics_from_month').val('');
        $('#analytics_to_month').val('');
        
        // Visual feedback
        $('.persian-date-input').parent().addClass('border-success');
        setTimeout(() => {
            $('.persian-date-input').parent().removeClass('border-success');
        }, 1000);
        
        console.log('Custom dates changed, year/month cleared');
    }
});

// Toggle help text
$('#toggle-help-text').on('click', function(e) {
    e.preventDefault();
    $('#filter-help').slideToggle();
});

// Refresh filters button - reload ComboBoxes based on date range
$('#refresh-filters').on('click', function() {
    const $btn = $(this);
    const originalHtml = $btn.html();
    
    $btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
    
    console.log('Refreshing filter lists based on date range...');
    
    loadUniqueFilterValues(true);
    
    setTimeout(function() {
        $btn.prop('disabled', false).html(originalHtml);
    }, 1500);
});

// When year changes, optionally reload ComboBoxes
$('#analytics_year, #analytics_from_month, #analytics_to_month').on('change', function() {
    // Optional: auto-refresh lists when year/month changes
    // Uncomment the next line to enable:
    // loadUniqueFilterValues(true);
});

// Test data button
$('#test-data-btn').on('click', function() {
    $(this).prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
    
    $.ajax({
        url: '/report/sales/analytics/test-data',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('=== داده‌های آزمایشی ===');
            console.log('تعداد کل رکوردها:', response.total_records);
            console.log('بازه تاریخی:', response.date_range);
            console.log('توزیع ماهانه:', response.monthly_distribution);
            console.log('نمونه داده‌ها:', response.sample_data);
            
            let message = `✅ تعداد کل رکوردها: ${response.total_records}\n`;
            message += `📅 بازه تاریخی: ${response.date_range.min} تا ${response.date_range.max}\n`;
            message += `📊 تعداد ماه‌های دارای داده: ${response.monthly_distribution.length}`;
            
            alert(message);
            $('#test-data-btn').prop('disabled', false).html('<i class="ti-bug"></i> Debug');
        },
        error: function(xhr, status, error) {
            console.error('خطا در تست داده‌ها:', error);
            alert('خطا در بارگذاری داده‌های تست. لطفاً Console را بررسی کنید.');
            $('#test-data-btn').prop('disabled', false).html('<i class="ti-bug"></i> Debug');
        }
    });
});

// Load customer categories for category filter
function loadCustomerCategories() {
    $.ajax({
        url: '/report/sales/analytics/customer-categories',
        method: 'GET',
        dataType: 'json'
    }).done(function(response) {
        const categories = response && response.success ? (response.data || []) : [];
        populateCategorySelect('#filter_customer_category', categories);
    }).fail(function(xhr) {
        console.error('Error loading customer categories', xhr);
        populateCategorySelect('#filter_customer_category', []);
    });
}

function populateCategorySelect(selector, categories) {
    const $select = $(selector);
    const previousValue = $select.val() || '';

    $select.empty();
    $select.append('<option value="">تمام دسته‌ها</option>');

    categories.forEach(function(category) {
        if (!category || !category.slug) {
            return;
        }
        const count = typeof category.client_count === 'number' ? category.client_count : '';
        const label = count !== '' ? `${category.name} (${count})` : category.name;
        const selected = category.slug === previousValue ? 'selected' : '';
        $select.append(`<option value="${category.slug}" ${selected}>${label}</option>`);
    });

    $('#customer-category-count').text(`(${categories.length})`);

    if (previousValue && !$select.find(`option[value="${previousValue}"]`).length) {
        $select.val('');
    }
}

// Load unique values for filters (with optional date filter)
function loadUniqueFilterValues(useDateFilter = false) {
    console.log('Loading unique filter values...');
    
    let filterData = {};
    if (useDateFilter) {
        const dates = getFilterDates();
        if (dates.from_date) filterData.from_date = dates.from_date;
        if (dates.to_date) filterData.to_date = dates.to_date;
    }
    
    const customerCategory = $('#filter_customer_category').val();

    const currentValues = {
        product: $('#filter_product').val(),
        customer: $('#filter_customer').val(),
        warehouse: $('#filter_warehouse').val()
    };
    
    if (customerCategory) {
        filterData.customer_category = customerCategory;
    }

    const requests = [
        { selector: '#filter_customer', column: 'customer_name', current: currentValues.customer, countId: 'customer-count' },
        { selector: '#filter_warehouse', column: 'warehouse', current: currentValues.warehouse, countId: 'warehouse-count' },
        { selector: '#filter_product', column: 'product_name', current: currentValues.product, countId: 'product-count' }
    ];
    
    requests.forEach(cfg => {
        $.ajax({
            url: '/report/sales/analytics/unique-values',
            method: 'POST',
            headers: analyticsCsrfToken ? { 'X-CSRF-TOKEN': analyticsCsrfToken } : {},
            data: { column: cfg.column, ...filterData },
            dataType: 'json'
        }).done(function(response) {
            const values = response.success ? (response.data || []) : [];
            populateSelect(cfg.selector, values, 'انتخاب کنید', cfg.current);
            $(`#${cfg.countId}`).text(`(${values.length})`);
        }).fail(function(xhr) {
            console.error('Error loading filter', cfg.column, xhr);
            populateSelect(cfg.selector, [], 'انتخاب کنید');
            $(`#${cfg.countId}`).text('(0)');
        });
    });
}

// Populate select with options
function populateSelect(selector, data, placeholder, selectedValue = '') {
    const $select = $(selector);
    const desiredValue = selectedValue || '';

    $select.empty();

    // Add placeholder option
    $select.append(`<option value="">${placeholder}</option>`);

    if (data && data.length > 0) {
        data.forEach(function(item) {
            if (item && item.trim() !== '') {
                const isSelected = item === desiredValue ? 'selected' : '';
                const displayName = item.length > 50 ? item.substring(0, 50) + '...' : item;
                $select.append(`<option value="${item}" ${isSelected}>${displayName}</option>`);
            }
        });

        const countLabel = selector.replace('#filter_', '') + '-count';
        $(`#${countLabel}`).text(`(${data.length})`);
    } else {
        $select.append('<option value="" disabled>داده‌ای یافت نشد</option>');
        const countLabel = selector.replace('#filter_', '') + '-count';
        $(`#${countLabel}`).text('(0)');
    }

    $select.val(desiredValue || '');

    console.log(`${selector} populated with ${(data && data.length) || 0} items`);
}

// Initialize on page load
$(document).ready(function() {
    console.log('Analytics page loaded');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Chart.js available:', typeof Chart !== 'undefined');
    console.log('Bootstrap available:', typeof $.fn.tab !== 'undefined');
    
    // Check if canvas elements exist
    console.log('Canvas #monthlyTrendChart exists:', $('#monthlyTrendChart').length > 0);
    console.log('Canvas #seasonalChart exists:', $('#seasonalChart').length > 0);
    
    // Set default to current year
    const currentYear = getCurrentPersianYear();
    $('#analytics_year').val('');
    $('#analytics_from_month').val('');
    $('#analytics_to_month').val('');
    
    updateFilterMode('year_month', { preserveYearValues: true, preserveDateValues: true });
    
    $('.tab-pane .card, .tab-pane .chart-container, #focus-summary-wrapper').hide();
    console.log('Default year placeholder active. Current year is:', currentYear);
    
    loadCustomerCategories();
    loadUniqueFilterValues(true);

    $('#filter_customer_category').on('change', function() {
        $('#filter_customer').val('');
        loadUniqueFilterValues(true);
        applyFocusBadges(getActiveFocus());
    });

    // Initialize Bootstrap tabs manually with click handler
    $('#analyticsTab a[data-toggle="tab"]').each(function() {
        $(this).on('click', function(e) {
            e.preventDefault();
            
            const targetId = $(this).attr('href');
            console.log('Tab clicked:', targetId);
            
            // Remove active from all tabs and panes
            $('#analyticsTab .nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');
            
            // Add active to clicked tab
            $(this).addClass('active');
            
            // Show corresponding tab pane
            $(targetId).addClass('show active');
            
            $('.tab-pane.show.active .card, .tab-pane.show.active .chart-container').toggle(hasTriggeredUpdate);

            // Load data for the specific tab if not loaded
            setTimeout(() => {
                if (!hasTriggeredUpdate) {
                    return;
                }
                if (targetId === '#time-analytics' && !window.timeChartsLoaded) {
                    console.log('Loading time analytics...');
                    loadTimeAnalytics();
                } else if (targetId === '#products-analytics' && !window.productsChartsLoaded) {
                    console.log('Loading products analytics...');
                    loadProductsAnalytics();
                } else if (targetId === '#customers-analytics' && !window.customersChartsLoaded) {
                    console.log('Loading customers analytics...');
                    loadCustomersAnalytics();
                } else if (targetId === '#financial-analytics' && !window.financialChartsLoaded) {
                    console.log('Loading financial analytics...');
                    loadFinancialAnalytics();
                } else if (targetId === '#logistics-analytics' && !window.logisticsChartsLoaded) {
                    console.log('Loading logistics analytics...');
                    loadLogisticsAnalytics();
                } else {
                    $('.tab-pane.show.active .card, .tab-pane.show.active .chart-container').fadeIn(150);
                }
                
                // Trigger resize to redraw charts
                window.dispatchEvent(new Event('resize'));
            }, 100);
        });
    });
    
    console.log('Tabs initialized with manual click handlers');
    
    // Initialize Persian date pickers
    initPersianDatePickers();
    
    // Load unique values for ComboBoxes
    loadUniqueFilterValues();

    // Highlight focus selections when filters change
    $('#filter_product, #filter_customer, #filter_warehouse').on('change', function() {
        applyFocusBadges(getActiveFocus());
    });
    applyFocusBadges(getActiveFocus());
    
    // Warehouse change event - Reload products based on selected warehouse
    $('#filter_warehouse').on('change', function() {
        const selectedWarehouse = $(this).val();
        const previousProduct = $('#filter_product').val();
        console.log('Warehouse changed:', selectedWarehouse);
        
        loadUniqueFilterValues(true);
        if (selectedWarehouse) {
            $('#filter_product').addClass('border-primary');
            setTimeout(() => $('#filter_product').removeClass('border-primary'), 2000);
        }
    });
    
    // Load first tab data after a short delay
    setTimeout(function() {
        console.log('Loading initial analytics data...');
        loadTimeAnalytics();
    }, 500);
});

// Persian Date Picker Initialization
function initPersianDatePickers() {
    $('.persian-date-input').each(function() {
        const inputId = $(this).attr('id');
        
        // Calendar button click
        $('button[data-target="' + inputId + '"]').on('click', function(e) {
            e.preventDefault();
            openPersianDatePicker(inputId);
        });
        
        // Input click
        $('#' + inputId).on('click', function() {
            openPersianDatePicker(inputId);
        });
        
        // Input change event
        $('#' + inputId).on('change', function() {
            console.log('Date changed:', inputId, $(this).val());
        });
        
        // Enter key press
        $('#' + inputId).on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#update-analytics').click();
            }
        });
    });
}

let currentPickerInput = null;
let selectedDate = { year: 1403, month: 1, day: 1 };

function openPersianDatePicker(inputId) {
    currentPickerInput = inputId;
    
    console.log('Opening date picker for:', inputId);
    
    // Get current date from input or use today (1403/01/01 as default)
    const currentValue = $('#' + inputId).val();
    if (currentValue && currentValue.trim() !== '') {
        const parts = currentValue.split('/');
        if (parts.length === 3) {
            selectedDate = {
                year: parseInt(parts[0]),
                month: parseInt(parts[1]),
                day: parseInt(parts[2])
            };
        }
    } else {
        // Set default date
        selectedDate = {
            year: 1403,
            month: 1,
            day: 1
        };
    }
    
    // Remove existing picker
    $('.persian-datepicker-popup').remove();
    
    // Create and show picker
    const popup = createPersianDatePickerPopup(selectedDate);
    $('body').append(popup);
    
    // Position popup near input
    const input = $('#' + inputId);
    const offset = input.offset();
    popup.css({
        top: offset.top + input.outerHeight() + 5,
        left: offset.left,
        zIndex: 9999
    });
    
    // Add click outside to close
    setTimeout(() => {
        $(document).on('click.persian-picker', function(e) {
            if (!$(e.target).closest('.persian-datepicker-popup').length && 
                !$(e.target).closest('#' + inputId).length &&
                !$(e.target).closest('button[data-target="' + inputId + '"]').length) {
                closePersianDatePicker();
            }
        });
    }, 100);
}

function createPersianDatePickerPopup(currentDate) {
    const { year, month, day } = currentDate;
    
    const popup = $(`
        <div class="persian-datepicker-popup" style="position: absolute; z-index: 9999; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 300px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h6 style="margin: 0; font-weight: bold;">انتخاب تاریخ</h6>
                <button type="button" onclick="closePersianDatePicker()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">سال</label>
                    <select id="picker-year" class="form-select form-select-sm" onchange="updatePersianCalendar()">
                        ${Array.from({length: 20}, (_, i) => 1395 + i).map(y => 
                            `<option value="${y}" ${y === year ? 'selected' : ''}>${y}</option>`
                        ).join('')}
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">ماه</label>
                    <select id="picker-month" class="form-select form-select-sm" onchange="updatePersianCalendar()">
                        ${persianMonths.map((m, i) => 
                            `<option value="${i + 1}" ${(i + 1) === month ? 'selected' : ''}>${m}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; margin-bottom: 15px;">
                ${['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].map(d => 
                    `<div style="font-weight: bold; font-size: 12px; padding: 5px;">${d}</div>`
                ).join('')}
                ${generateCalendarDays(year, month, day)}
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-sm btn-secondary" onclick="closePersianDatePicker()">لغو</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="confirmPersianDate()">تأیید</button>
            </div>
        </div>
    `);
    
    return popup;
}

function generateCalendarDays(year, month, selectedDay) {
    const daysInMonth = month <= 6 ? 31 : (month <= 11 ? 30 : (isLeapYear(year) ? 30 : 29));
    let html = '';
    
    for (let i = 1; i <= daysInMonth; i++) {
        const isSelected = i === selectedDay;
        html += `
            <div class="calendar-day ${isSelected ? 'selected' : ''}" 
                 data-day="${i}" 
                 onclick="selectDay(${i})"
                 style="
                     padding: 8px; 
                     cursor: pointer; 
                     border-radius: 4px;
                     font-size: 13px;
                     ${isSelected ? 'background: #5969ff; color: white; font-weight: bold;' : 'background: #f8f9fa;'}
                 ">
                ${i}
            </div>
        `;
    }
    
    return html;
}

function isLeapYear(year) {
    const breaks = [1, 5, 9, 13, 17, 22, 26, 30];
    const cycle = year % 33;
    return breaks.includes(cycle);
}

window.selectDay = function(day) {
    selectedDate.day = day;
    $('.calendar-day').removeClass('selected').css({'background': '#f8f9fa', 'color': 'inherit', 'font-weight': 'normal'});
    $(`.calendar-day[data-day="${day}"]`).addClass('selected').css({'background': '#5969ff', 'color': 'white', 'font-weight': 'bold'});
};

window.updatePersianCalendar = function() {
    selectedDate.year = parseInt($('#picker-year').val());
    selectedDate.month = parseInt($('#picker-month').val());
    
    console.log('Calendar updated to:', selectedDate.year, '/', selectedDate.month);
    
    // Regenerate calendar
    const calendarHtml = generateCalendarDays(selectedDate.year, selectedDate.month, selectedDate.day);
    $('.persian-datepicker-popup > div:nth-child(3)').html(`
        ${['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].map(d => 
            `<div style="font-weight: bold; font-size: 12px; padding: 5px;">${d}</div>`
        ).join('')}
        ${calendarHtml}
    `);
};

window.confirmPersianDate = function() {
    if (currentPickerInput) {
        const dateString = `${selectedDate.year}/${String(selectedDate.month).padStart(2, '0')}/${String(selectedDate.day).padStart(2, '0')}`;
        $('#' + currentPickerInput).val(dateString);
        console.log('Date set for', currentPickerInput, ':', dateString);
        
        // Trigger change event
        $('#' + currentPickerInput).trigger('change');
    }
    closePersianDatePicker();
};

window.closePersianDatePicker = function() {
    $('.persian-datepicker-popup').remove();
    $(document).off('click.persian-picker');
};
</script>

<style>
.nav-tabs .nav-link {
    color: #495057;
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}

.nav-tabs .nav-link.active {
    color: #5969ff;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    font-weight: 600;
}

.chart-container {
    position: relative;
    height: 400px;
    margin-bottom: 20px;
}

.chart-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.stat-card {
    border-left: 4px solid #5969ff;
    transition: all 0.3s ease;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.persian-datepicker-popup:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

.calendar-day:hover:not(.selected) {
    background: #e7e9fd !important;
}

.border-primary {
    border: 2px solid #5969ff !important;
    transition: border 0.3s ease;
}

.border-success {
    border: 2px solid #24d2b5 !important;
    transition: border 0.3s ease;
}

.form-control:disabled, .form-control[readonly] {
    background-color: #f8f9fa;
    opacity: 0.7;
}

.btn-group-sm>.btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.alert ul {
    padding-right: 20px;
    margin-bottom: 0;
}

.input-group-sm > .form-control {
    font-size: 0.875rem;
}

.focus-summary-card {
    border: 1px solid #ffe0a3;
    border-radius: 12px;
    box-shadow: 0 8px 18px rgba(255, 193, 7, 0.08);
}

.focus-summary-card .card-header {
    background: #fff7e6;
    border-bottom: 1px solid #ffe0a3;
}

.focus-summary-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 0 0 12px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.focus-summary-metrics .focus-metric-card {
    background: #f8f9fc;
    border-radius: 10px;
    padding: 14px 16px;
    border: 1px solid #e6ecff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
}

.focus-summary-metrics .focus-metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(89, 105, 255, 0.12);
}

.focus-metric-label {
    font-size: 13px;
    color: #6c757d;
}

.focus-metric-value {
    font-weight: 600;
    color: #2b2f5c;
}

.focus-meta-card {
    background: #ffffff;
    border: 1px dashed #d8dffb;
    border-radius: 10px;
    padding: 12px;
    height: 100%;
}

.focus-meta-label {
    font-size: 12px;
    color: #7a7f9a;
}

.focus-meta-value {
    font-weight: 600;
    color: #5969ff;
    font-size: 15px;
}

.focus-top-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    color: #2b2d5f;
    margin-bottom: 10px;
}

.list-group-sm .list-group-item {
    padding: 0.45rem 0.75rem;
    font-size: 0.88rem;
}

.list-group-sm .list-group-item .badge {
    font-weight: 500;
}

.focus-highlight {
    border: 2px solid #ff9800 !important;
    box-shadow: 0 0 12px rgba(255, 152, 0, 0.25);
}

#product-focus-view .focus-empty-state {
    background: #f7f9ff;
    border: 1px dashed #cbd6ff;
    color: #4f5d91;
}

#product-focus-view .focus-warehouse-list .list-group-item {
    border: none;
    border-bottom: 1px solid #f1f3f9;
}

#product-focus-view .focus-warehouse-list .list-group-item:last-child {
    border-bottom: none;
}

.filter-group-disabled {
    opacity: 0.6;
}
</style>


```

## تغییرات اصلی
- افزودن مجموعه‌ای از توابع کمکی برای نرمال‌سازی اعداد، قالب‌بندی درصد و مدیریت واحد سنجش؛ سپس به‌روزرسانی جمع‌بندی تمرکز و لیست‌های جزئیات تا مقدارها با واحد صحیح و بدون NaN نمایش داده شوند.  
```429:662:application/resources/views/pages/reports/sales/analytics-wrapper.blade.php
function formatPercentageValue(value, digits = 1) {
    // ...
}
function getUnitLabel(source, fallback = 'واحد') {
    // ...
}
$('#focus-total-quantity').text(summary.total_quantity ? formatQuantityValue(summary.total_quantity, summaryUnitLabel) : '-');
```

- بازبینی کامل تحلیل مشتریان، محصولات، زمان و لجستیک: پاک‌سازی داده‌ها پیش از ورود به نمودارها/Progress Bar‌ها و خواناتر کردن مقادیر (نمایش درصد با فرمت ثابت و مقدار+واحد در Tooltip و جدول‌ها).  
```882:1056:application/resources/views/pages/reports/sales/analytics-customers.blade.php
const percentageDisplay = formatPercentageValue(percentageValue, 2);
<span class="badge badge-light progress-value">${percentageDisplay}٪</span>
```
```381:545:application/resources/views/pages/reports/sales/analytics-products.blade.php
const unitLabel = getUnitLabel(item);
const quantity = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '-';
```
```258:427:application/resources/views/pages/reports/sales/analytics-logistics.blade.php
const safeRate = window.clampPercentage(deliveryRate, 2);
return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
$('#deliveredQty').text(formatQuantityValue(stats.total_issued || 0, unitLabel));
```
```202:360:application/resources/views/pages/reports/sales/analytics-time.blade.php
const counts = data.map(item => window.toFiniteNumber(item.count, 0));
const percentage = total ? formatPercentageValue((parsedValue / total) * 100, 1) : formatPercentageValue(0, 1);
```

- گسترش API‌ها برای برگرداندن برچسب واحد غالب در همه خروجی‌های تحلیلی (Focus summary، توزیع‌ها، محصولات برتر، وضعیت تحویل) تا فرانت بتواند مقدارها را با واحد درست نمایش دهد.  
```580:1124:application/app/Http/Controllers/Reports/SalesReports.php
->selectRaw('... COUNT(*) as order_count, MAX(main_unit) as unit_label')
$unitLabel = (clone $baseQuery)
    ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
$responseData = [
    'total_quantity' => (float) round($totalQuantity, 2),
    'unit_label' => $unitLabel,
    // ...
];
```

## منطق ۸۰/۲۰ و تجمعی مشتریان
الگوریتم ابتدا مشتریان را بر اساس مبلغ فروش مرتب می‌کند، سپس درصد مشارکت هر مشتری در کل فروش محاسبه و به‌صورت تجمعی جمع می‌شود. تا جایی که مجموع به ۸۰٪ برسد دسته A محسوب می‌شود، ۱۵٪ بعدی B و بقیه C؛ خلاصه و جدول/نمودار نیز از همین داده استفاده می‌کنند.  
```836:974:application/resources/views/pages/reports/sales/analytics-customers.blade.php
foreach ($customerSales as $index => $customer) {
    $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
    $cumulativePercentage += $percentage;
    $classification = $cumulativePercentage <= 80 ? 'A' : ($cumulativePercentage <= 95 ? 'B' : 'C');
    // ...
}
```

## وضعیت تست
- تست خودکاری اجرا نشد؛ در صورت امکان یک بار لود صفحه و بررسی نمودارها با داده‌های واقعی پیشنهاد می‌شود.

اگر نیاز به بررسی دقیق‌تری روی دیتاست یا اضافه کردن معادل انگلیسی واحدها داشتید اطلاع دهید.

```php
function formatPercentageValue(value, digits = 1) {
    // ...
}
function getUnitLabel(source, fallback = 'واحد') {
    // ...
}
$('#focus-total-quantity').text(summary.total_quantity ? formatQuantityValue(summary.total_quantity, summaryUnitLabel) : '-');
```

```php
const percentageDisplay = formatPercentageValue(percentageValue, 2);
<span class="badge badge-light progress-value">${percentageDisplay}٪</span>
```

```php
const unitLabel = getUnitLabel(item);
const quantity = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '-';
```

```php
const safeRate = window.clampPercentage(deliveryRate, 2);
return 'مقدار: ' + formatQuantityValue(context.parsed.x, unitLabel);
$('#deliveredQty').text(formatQuantityValue(stats.total_issued || 0, unitLabel));
```

```php
const counts = data.map(item => window.toFiniteNumber(item.count, 0));
const percentage = total ? formatPercentageValue((parsedValue / total) * 100, 1) : formatPercentageValue(0, 1);
```

```php
->selectRaw('... COUNT(*) as order_count, MAX(main_unit) as unit_label')
$unitLabel = (clone $baseQuery)
    ->selectRaw('main_unit, SUM(main_quantity) as quantity_sum')
$responseData = [
    'total_quantity' => (float) round($totalQuantity, 2),
    'unit_label' => $unitLabel,
    // ...
];
```

```php
foreach ($customerSales as $index => $customer) {
    $percentage = $totalSales > 0 ? ($customer->total_amount / $totalSales) * 100 : 0;
    $cumulativePercentage += $percentage;
    $classification = $cumulativePercentage <= 80 ? 'A' : ($cumulativePercentage <= 95 ? 'B' : 'C');
    // ...
}
```

