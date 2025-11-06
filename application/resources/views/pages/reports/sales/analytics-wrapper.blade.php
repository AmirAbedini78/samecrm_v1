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
                        <button id="test-data-btn" class="btn btn-sm btn-outline-secondary" title="تست داده‌ها">
                            <i class="ti-bug"></i> Debug
                        </button>
                    </div>
                </div>
                
                <!-- Date Range Filters -->
                <div class="row align-items-end mb-4">
                    <!-- Year Selector -->
                    <div class="col-md-2">
                        <label class="form-label">انتخاب سال</label>
                        <select id="analytics_year" class="form-control">
                            <option value="">همه سال‌ها</option>
                            <option value="1400">1400</option>
                            <option value="1401">1401</option>
                            <option value="1402">1402</option>
                            <option value="1403" selected>1403</option>
                            <option value="1404">1404</option>
                            <option value="1405">1405</option>
                        </select>
                    </div>
                    
                    <!-- Month Range -->
                    <div class="col-md-2">
                        <label class="form-label">از ماه</label>
                        <select id="analytics_from_month" class="form-control">
                            <option value="">همه</option>
                            <option value="1" selected>فروردین</option>
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
                    
                    <div class="col-md-2">
                        <label class="form-label">تا ماه</label>
                        <select id="analytics_to_month" class="form-control">
                            <option value="">همه</option>
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
                            <option value="12" selected>اسفند</option>
                        </select>
                    </div>
                    
                    <!-- OR Custom Date Range -->
                    <div class="col-md-1 text-center">
                        <label class="form-label d-block">&nbsp;</label>
                        <strong>یا</strong>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">از تاریخ (دقیق)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="analytics_from_date" class="form-control persian-date-input" 
                                   placeholder="1403/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_from_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">تا تاریخ (دقیق)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="analytics_to_date" class="form-control persian-date-input" 
                                   placeholder="1403/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_to_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-1">
                        <button id="update-analytics" class="btn btn-primary w-100">
                            <i class="ti-reload"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Additional Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر محصول</span>
                            <small class="text-muted" id="product-count"></small>
                        </label>
                        <select id="filter_product" class="form-control form-control-sm">
                            <option value="">همه محصولات</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر مشتری</span>
                            <small class="text-muted" id="customer-count"></small>
                        </label>
                        <select id="filter_customer" class="form-control form-control-sm">
                            <option value="">همه مشتریان</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر انبار</span>
                            <small class="text-muted" id="warehouse-count"></small>
                        </label>
                        <select id="filter_warehouse" class="form-control form-control-sm">
                            <option value="">همه انبارها</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">وضعیت فروش</label>
                        <select id="filter_status" class="form-control form-control-sm">
                            <option value="">همه</option>
                            <option value="completed">تکمیل شده</option>
                            <option value="pending">در انتظار</option>
                            <option value="cancelled">لغو شده</option>
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

// Format numbers with Persian separators
function formatNumber(num) {
    return new Intl.NumberFormat('fa-IR').format(num);
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
    const status = $('#filter_status').val();
    
    let filterText = '';
    let filterParts = [];
    
    if (fromDate || toDate) {
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
    if (product) filterParts.push(`محصول: ${product}`);
    if (customer) filterParts.push(`مشتری: ${customer}`);
    if (warehouse) filterParts.push(`انبار: ${warehouse}`);
    if (status) {
        const statusText = status === 'completed' ? 'تکمیل شده' : (status === 'pending' ? 'در انتظار' : 'لغو شده');
        filterParts.push(`وضعیت: ${statusText}`);
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
    
    // Reload data for active tab
    if (activeTabId === '#time-analytics') {
        loadTimeAnalytics();
    } else if (activeTabId === '#products-analytics') {
        loadProductsAnalytics();
    } else if (activeTabId === '#customers-analytics') {
        loadCustomersAnalytics();
    } else if (activeTabId === '#financial-analytics') {
        loadFinancialAnalytics();
    } else if (activeTabId === '#logistics-analytics') {
        loadLogisticsAnalytics();
    } else {
        // Default to time analytics if nothing is active
        console.log('No active tab found, loading time analytics');
        loadTimeAnalytics();
    }
    
    setTimeout(() => {
        $(this).prop('disabled', false).html('<i class="ti-reload"></i> به‌روزرسانی نمودارها');
    }, 1000);
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

// Get all filters including date, product, customer, warehouse
function getFilterDates() {
    const year = $('#analytics_year').val();
    const fromMonth = $('#analytics_from_month').val();
    const toMonth = $('#analytics_to_month').val();
    const fromDate = $('#analytics_from_date').val();
    const toDate = $('#analytics_to_date').val();
    
    // Additional filters
    const product = $('#filter_product').val();
    const customer = $('#filter_customer').val();
    const warehouse = $('#filter_warehouse').val();
    const status = $('#filter_status').val();
    
    let calculatedFromDate = '';
    let calculatedToDate = '';
    
    // If custom dates are provided, use them
    if (fromDate || toDate) {
        calculatedFromDate = fromDate;
        calculatedToDate = toDate;
        console.log('Using custom date range:', fromDate, 'to', toDate);
    } else if (year) {
        // Otherwise use year/month selectors
        const startMonth = fromMonth || '01';
        const endMonth = toMonth || '12';
        const endDay = (endMonth === '12') ? '29' : ((parseInt(endMonth) <= 6) ? '31' : '30');
        
        calculatedFromDate = `${year}/${startMonth.padStart(2, '0')}/01`;
        calculatedToDate = `${year}/${endMonth.padStart(2, '0')}/${endDay}`;
        console.log('Using year/month filters:', calculatedFromDate, 'to', calculatedToDate);
    }
    
    const filters = {
        from_date: calculatedFromDate,
        to_date: calculatedToDate,
        filter_mode: fromDate || toDate ? 'exact' : 'year_month',
        year: year,
        from_month: fromMonth,
        to_month: toMonth
    };
    
    // Add additional filters if they have values
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
    if (status) {
        filters.status = status;
        console.log('Filter - Status:', status);
    }
    
    return filters;
}

// Set current year button
$('#set-current-year').on('click', function() {
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
    // Clear all filters
    $('#analytics_year').val('');
    $('#analytics_from_month').val('');
    $('#analytics_to_month').val('');
    $('#analytics_from_date').val('');
    $('#analytics_to_date').val('');
    $('#filter_product').val('');
    $('#filter_customer').val('');
    $('#filter_warehouse').val('');
    $('#filter_status').val('');
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
    
    // Reload with date filter
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

// Load unique values for filters (with optional date filter)
function loadUniqueFilterValues(useDateFilter = false) {
    console.log('Loading unique filter values...');
    
    let filterData = {};
    
    // If useDateFilter is true, add date range to filter
    if (useDateFilter) {
        const dates = getFilterDates();
        if (dates.from_date) filterData.from_date = dates.from_date;
        if (dates.to_date) filterData.to_date = dates.to_date;
    }
    
    // Save current selections
    const currentProduct = $('#filter_product').val();
    const currentCustomer = $('#filter_customer').val();
    const currentWarehouse = $('#filter_warehouse').val();
    
    // Load unique products
    $.ajax({
        url: '/report/sales/analytics/unique-values',
        method: 'POST',
        data: { column: 'product_name', ...filterData },
        dataType: 'json',
        success: function(response) {
            console.log('Unique products loaded:', response.data.length);
            populateSelect('#filter_product', response.data, 'همه محصولات', currentProduct);
        },
        error: function(xhr) {
            console.error('Error loading products:', xhr);
            populateSelect('#filter_product', [], 'همه محصولات');
        }
    });
    
    // Load unique customers
    $.ajax({
        url: '/report/sales/analytics/unique-values',
        method: 'POST',
        data: { column: 'customer_name', ...filterData },
        dataType: 'json',
        success: function(response) {
            console.log('Unique customers loaded:', response.data.length);
            populateSelect('#filter_customer', response.data, 'همه مشتریان', currentCustomer);
        },
        error: function(xhr) {
            console.error('Error loading customers:', xhr);
            populateSelect('#filter_customer', [], 'همه مشتریان');
        }
    });
    
    // Load unique warehouses
    $.ajax({
        url: '/report/sales/analytics/unique-values',
        method: 'POST',
        data: { column: 'warehouse', ...filterData },
        dataType: 'json',
        success: function(response) {
            console.log('Unique warehouses loaded:', response.data.length);
            populateSelect('#filter_warehouse', response.data, 'همه انبارها', currentWarehouse);
        },
        error: function(xhr) {
            console.error('Error loading warehouses:', xhr);
            populateSelect('#filter_warehouse', [], 'همه انبارها');
        }
    });
}

// Populate select with options
function populateSelect(selector, data, placeholder, selectedValue = '') {
    const $select = $(selector);
    $select.empty();
    
    // Add placeholder option
    $select.append(`<option value="">${placeholder}</option>`);
    
    // Add data options
    if (data && data.length > 0) {
        data.forEach(function(item) {
            if (item && item.trim() !== '') {
                const isSelected = item === selectedValue ? 'selected' : '';
                // Truncate long names for display
                const displayName = item.length > 50 ? item.substring(0, 50) + '...' : item;
                $select.append(`<option value="${item}" ${isSelected}>${displayName}</option>`);
            }
        });
        
        // Update count label
        const countLabel = selector.replace('#filter_', '') + '-count';
        $(`#${countLabel}`).text(`(${data.length})`);
    } else {
        $select.append(`<option value="" disabled>داده‌ای یافت نشد</option>`);
        // Clear count label
        const countLabel = selector.replace('#filter_', '') + '-count';
        $(`#${countLabel}`).text('(0)');
    }
    
    console.log(`${selector} populated with ${data.length} items`);
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
    $('#analytics_year').val(currentYear);
    $('#analytics_from_month').val('1');
    $('#analytics_to_month').val('12');
    
    console.log('Default year set to:', currentYear);
    
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
            
            // Load data for the specific tab if not loaded
            setTimeout(() => {
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
    
    // Warehouse change event - Reload products based on selected warehouse
    $('#filter_warehouse').on('change', function() {
        const selectedWarehouse = $(this).val();
        console.log('Warehouse changed:', selectedWarehouse);
        
        // Get current date filter
        let filterData = {};
        const dates = getFilterDates();
        if (dates.from_date) filterData.from_date = dates.from_date;
        if (dates.to_date) filterData.to_date = dates.to_date;
        
        // Add warehouse filter
        if (selectedWarehouse) {
            filterData.warehouse = selectedWarehouse;
        }
        
        // Reload products based on selected warehouse
        $.ajax({
            url: '/report/sales/analytics/unique-values',
            method: 'POST',
            data: { column: 'product_name', ...filterData },
            dataType: 'json',
            success: function(response) {
                console.log('Products reloaded for warehouse:', response.data.length);
                populateSelect('#filter_product', response.data, 'همه محصولات', '');
                
                // Show feedback
                if (selectedWarehouse) {
                    $('#filter_product').addClass('border-primary');
                    setTimeout(() => $('#filter_product').removeClass('border-primary'), 2000);
                }
            },
            error: function(xhr) {
                console.error('Error loading products for warehouse:', xhr);
                populateSelect('#filter_product', [], 'همه محصولات');
            }
        });
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
</style>

