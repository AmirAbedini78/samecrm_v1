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

