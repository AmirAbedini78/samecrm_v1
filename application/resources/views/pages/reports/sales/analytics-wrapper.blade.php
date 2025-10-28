<!-- Sales Analytics Dashboard -->
<div class="row" id="sales-analytics-dashboard">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="ti-bar-chart"></i> تحلیل‌های فروش و نمودارهای تحلیلی
                </h4>
                
                <!-- Date Range Filters -->
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-4">
                        <label class="form-label">از تاریخ</label>
                        <div class="input-group">
                            <input type="text" id="analytics_from_date" class="form-control persian-date-input" 
                                   placeholder="1403/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_from_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">تا تاریخ</label>
                        <div class="input-group">
                            <input type="text" id="analytics_to_date" class="form-control persian-date-input" 
                                   placeholder="1403/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="analytics_to_date">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button id="update-analytics" class="btn btn-primary w-100">
                            <i class="ti-reload"></i> به‌روزرسانی نمودارها
                        </button>
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

// Initialize charts when tab is shown
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    const target = $(e.target).attr('href');
    
    // Trigger resize event to redraw charts
    window.dispatchEvent(new Event('resize'));
    
    // Load data for the specific tab if not loaded
    if (target === '#time-analytics' && !window.timeChartsLoaded) {
        loadTimeAnalytics();
    } else if (target === '#products-analytics' && !window.productsChartsLoaded) {
        loadProductsAnalytics();
    } else if (target === '#customers-analytics' && !window.customersChartsLoaded) {
        loadCustomersAnalytics();
    } else if (target === '#financial-analytics' && !window.financialChartsLoaded) {
        loadFinancialAnalytics();
    } else if (target === '#logistics-analytics' && !window.logisticsChartsLoaded) {
        loadLogisticsAnalytics();
    }
});

// Update charts button
$('#update-analytics').on('click', function() {
    const activeTab = $('.nav-tabs .nav-link.active').attr('data-bs-target');
    
    $(this).prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...');
    
    // Reload data for active tab
    if (activeTab === '#time-analytics') {
        loadTimeAnalytics();
    } else if (activeTab === '#products-analytics') {
        loadProductsAnalytics();
    } else if (activeTab === '#customers-analytics') {
        loadCustomersAnalytics();
    } else if (activeTab === '#financial-analytics') {
        loadFinancialAnalytics();
    } else if (activeTab === '#logistics-analytics') {
        loadLogisticsAnalytics();
    }
    
    setTimeout(() => {
        $(this).prop('disabled', false).html('<i class="ti-reload"></i> به‌روزرسانی نمودارها');
    }, 1000);
});

// Get filter dates
function getFilterDates() {
    return {
        from_date: $('#analytics_from_date').val(),
        to_date: $('#analytics_to_date').val()
    };
}

// Initialize on page load
$(document).ready(function() {
    console.log('Analytics page loaded');
    
    // Initialize Persian date pickers first
    initPersianDatePickers();
    
    // Load first tab data after a short delay
    setTimeout(function() {
        loadTimeAnalytics();
    }, 500);
});

// Persian Date Picker Initialization
function initPersianDatePickers() {
    $('.persian-date-input').each(function() {
        const inputId = $(this).attr('id');
        
        // Calendar button click
        $('button[data-target="' + inputId + '"]').on('click', function() {
            openPersianDatePicker(inputId);
        });
        
        // Input click
        $('#' + inputId).on('click', function() {
            openPersianDatePicker(inputId);
        });
    });
}

let currentPickerInput = null;
let selectedDate = { year: 1403, month: 1, day: 1 };

function openPersianDatePicker(inputId) {
    currentPickerInput = inputId;
    
    // Get current date from input or use today
    const currentValue = $('#' + inputId).val();
    if (currentValue) {
        const parts = currentValue.split('/');
        if (parts.length === 3) {
            selectedDate = {
                year: parseInt(parts[0]),
                month: parseInt(parts[1]),
                day: parseInt(parts[2])
            };
        }
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
        left: offset.left
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

function selectDay(day) {
    selectedDate.day = day;
    $('.calendar-day').removeClass('selected').css({'background': '#f8f9fa', 'color': 'inherit', 'font-weight': 'normal'});
    $(`.calendar-day[data-day="${day}"]`).addClass('selected').css({'background': '#5969ff', 'color': 'white', 'font-weight': 'bold'});
}

function updatePersianCalendar() {
    selectedDate.year = parseInt($('#picker-year').val());
    selectedDate.month = parseInt($('#picker-month').val());
    
    // Regenerate calendar
    const calendarHtml = generateCalendarDays(selectedDate.year, selectedDate.month, selectedDate.day);
    $('.persian-datepicker-popup > div:nth-child(3)').html(`
        ${['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].map(d => 
            `<div style="font-weight: bold; font-size: 12px; padding: 5px;">${d}</div>`
        ).join('')}
        ${calendarHtml}
    `);
}

function confirmPersianDate() {
    if (currentPickerInput) {
        const dateString = `${selectedDate.year}/${String(selectedDate.month).padStart(2, '0')}/${String(selectedDate.day).padStart(2, '0')}`;
        $('#' + currentPickerInput).val(dateString);
    }
    closePersianDatePicker();
}

function closePersianDatePicker() {
    $('.persian-datepicker-popup').remove();
    $(document).off('click.persian-picker');
}
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
</style>

