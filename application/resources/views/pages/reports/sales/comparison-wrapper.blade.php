<!-- Sales Comparison Report Wrapper -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">گزارش مقایسه فروش بر اساس تاریخ سند</h4>
                
                <!-- Date Range Filters -->
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-3">
                        <label class="form-label">از تاریخ (بازه 1)</label>
                        <div class="input-group">
                            <input type="text" id="range1_from" class="form-control persian-date-input" 
                                   placeholder="1403/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range1_from">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تا تاریخ (بازه 1)</label>
                        <div class="input-group">
                            <input type="text" id="range1_to" class="form-control persian-date-input" 
                                   placeholder="1403/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range1_to">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">از تاریخ (بازه 2)</label>
                        <div class="input-group">
                            <input type="text" id="range2_from" class="form-control persian-date-input" 
                                   placeholder="1404/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range2_from">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تا تاریخ (بازه 2)</label>
                        <div class="input-group">
                            <input type="text" id="range2_to" class="form-control persian-date-input" 
                                   placeholder="1404/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range2_to">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Additional Filters -->
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-3">
                        <label class="form-label">وضعیت فروش</label>
                        <select id="sales_status_filter" class="form-select">
                            <option value="">همه وضعیت‌ها</option>
                            <option value="completed">تکمیل شده</option>
                            <option value="pending">در انتظار</option>
                            <option value="cancelled">لغو شده</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">نام مشتری</label>
                        <input type="text" id="customer_filter" class="form-control" placeholder="جستجو در نام مشتری">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">نام محصول</label>
                        <input type="text" id="product_filter" class="form-control" placeholder="جستجو در نام محصول">
                    </div>
                    <div class="col-md-3">
                        <button id="run-comparison" class="btn btn-primary w-100">
                            <i class="ti-bar-chart"></i> اجرای مقایسه
                        </button>
                    </div>
                </div>

                <hr>

                <!-- Summary Results -->
                <div id="comparison-results" class="row g-3 mb-4" style="display: none;">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">بازه 1 - خلاصه آمار</h5>
                                <p class="mb-1">تعداد رکورد: <span id="r1-count" class="fw-bold">0</span></p>
                                <p class="mb-1">مجموع مبلغ فروش: <span id="r1-total" class="fw-bold">0</span> ریال</p>
                                <p class="mb-0">میانگین مبلغ فروش: <span id="r1-avg" class="fw-bold">0</span> ریال</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">بازه 2 - خلاصه آمار</h5>
                                <p class="mb-1">تعداد رکورد: <span id="r2-count" class="fw-bold">0</span></p>
                                <p class="mb-1">مجموع مبلغ فروش: <span id="r2-total" class="fw-bold">0</span> ریال</p>
                                <p class="mb-0">میانگین مبلغ فروش: <span id="r2-avg" class="fw-bold">0</span> ریال</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparison Chart -->
                <div id="chart-section" class="row mb-4" style="display: none;">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">نمودار مقایسه‌ای</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="comparisonChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DataTables Section -->
                <div id="tables-section" class="mt-4" style="display: none;">
                    <!-- Range 1 Table -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">گزارش بازه 1</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportTable('range1', 'excel')">
                                    <i class="ti-file"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportTable('range1', 'pdf')">
                                    <i class="ti-file"></i> PDF
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sales-table-range1" class="table table-striped table-bordered table-hover" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>نام محصول</th>
                                            <th>نام مشتری</th>
                                            <th>شماره سند</th>
                                            <th>مقدار اصلی</th>
                                            <th>مبلغ فروش</th>
                                            <th>خالص</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ سند</th>
                                            <th>ایجادکننده</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Range 2 Table -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">گزارش بازه 2</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportTable('range2', 'excel')">
                                    <i class="ti-file"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportTable('range2', 'pdf')">
                                    <i class="ti-file"></i> PDF
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sales-table-range2" class="table table-striped table-bordered table-hover" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>نام محصول</th>
                                            <th>نام مشتری</th>
                                            <th>شماره سند</th>
                                            <th>مقدار اصلی</th>
                                            <th>مبلغ فروش</th>
                                            <th>خالص</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ سند</th>
                                            <th>ایجادکننده</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Persian Date Picker Modal -->
<div id="persianDatePickerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); z-index: 99999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; padding: 20px; min-width: 400px; max-width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.5); border: 2px solid #007bff;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
            <h4 style="margin: 0; color: #333; font-weight: bold;">انتخاب تاریخ شمسی</h4>
            <button type="button" onclick="closePersianDatePicker()" style="background: #dc3545; color: white; border: none; font-size: 20px; cursor: pointer; padding: 5px 10px; border-radius: 4px;">&times;</button>
        </div>
        
        <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 120px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px;">سال:</label>
                <select id="picker-year" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                    <option value="1400">1400</option>
                    <option value="1401">1401</option>
                    <option value="1402">1402</option>
                    <option value="1403">1403</option>
                    <option value="1404" selected>1404</option>
                    <option value="1405">1405</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 120px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px;">ماه:</label>
                <select id="picker-month" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
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
            <div style="flex: 1; min-width: 120px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px;">روز:</label>
                <select id="picker-day" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                    <!-- Days will be populated dynamically -->
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <button type="button" onclick="confirmPersianDate()" style="background: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold;">تأیید</button>
            <button type="button" onclick="closePersianDatePicker()" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold;">لغو</button>
        </div>
    </div>
</div>

<script>
// Global variables
var table1 = null;
var table2 = null;
var currentDateInput = null;
var comparisonChart = null;

// Utility functions
function fmt(amount) {
    try { 
        return new Intl.NumberFormat('fa-IR').format(parseFloat(amount || 0)); 
    } catch(e) { 
        return amount; 
    }
}

function clearOldDatePickers() {
    // Remove any existing date picker elements
    $('.kamadatepicker-popup, .persian-datepicker-popup-old, .modal-backdrop').remove();
    
    // Remove any existing date picker event handlers
    $(document).off('click.persian-picker');
    $(document).off('keydown.persian-picker');
    
    // Clear any existing date picker variables
    currentDatePicker = null;
    currentInputId = null;
    
    console.log('Old date pickers cleared');
}

// Custom Persian Date Picker Functions
var currentDatePicker = null;
var currentInputId = null;

function showPersianDatePicker(inputId) {
    console.log('showPersianDatePicker called with:', inputId);
    
    // Clear any existing date pickers first
    $('.kamadatepicker-popup, .persian-datepicker-popup-old, .modal-backdrop').remove();
    
    // Close any existing picker
    closePersianDatePicker();
    
    currentInputId = inputId;
    var input = $('#' + inputId);
    var inputGroup = input.closest('.input-group');
    
    console.log('Input found:', input.length);
    console.log('InputGroup found:', inputGroup.length);
    
    // Get current date or default to today
    var currentValue = input.val();
    var currentDate = currentValue ? parsePersianDate(currentValue) : getTodayPersian();
    
    console.log('Current date:', currentDate);
    
    // Create popup
    var popup = createPersianDatePickerPopup(currentDate);
    
    // Position popup
    var offset = inputGroup.offset();
    popup.css({
        'top': offset.top + inputGroup.outerHeight() + 5,
        'left': offset.left
    });
    
    console.log('Popup positioned at:', offset);
    
    // Add to body
    $('body').append(popup);
    currentDatePicker = popup;
    
    console.log('Popup added to body');
    
    // Add click outside to close
    $(document).on('click.persian-picker', function(e) {
        if (!$(e.target).closest('.persian-datepicker-popup').length && 
            !$(e.target).closest('#' + inputId).length &&
            !$(e.target).closest('button[data-target="' + inputId + '"]').length) {
            closePersianDatePicker();
        }
    });
    
    // Add escape key to close
    $(document).on('keydown.persian-picker', function(e) {
        if (e.keyCode === 27) { // Escape key
            closePersianDatePicker();
        }
    });
}

function createPersianDatePickerPopup(currentDate) {
    var year = currentDate.year;
    var month = currentDate.month;
    var day = currentDate.day;
    
    var persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر',
        'مرداد', 'شهریور', 'مهر', 'آبان',
        'آذر', 'دی', 'بهمن', 'اسفند'
    ];
    
    var dayNames = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
    
    // Generate calendar
    var calendarHtml = generatePersianCalendar(year, month, day);
    
    var popup = $(`
        <div class="persian-datepicker-popup">
            <div class="header">
                <h5 style="margin: 0;">انتخاب تاریخ شمسی</h5>
                <button type="button" onclick="closePersianDatePicker()" style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            
            <div class="month-year">
                <select id="picker-year" onchange="updatePersianCalendar()">
                    ${generateYearOptions(year)}
                </select>
                <select id="picker-month" onchange="updatePersianCalendar()">
                    ${generateMonthOptions(month)}
                </select>
            </div>
            
            <div class="calendar">
                ${dayNames.map(d => `<div class="day-header">${d}</div>`).join('')}
                ${calendarHtml}
            </div>
            
            <div class="buttons">
                <button type="button" class="btn-danger" onclick="clearPersianDate()">پاک کردن</button>
                <button type="button" class="btn-secondary" onclick="closePersianDatePicker()">لغو</button>
                <button type="button" class="btn-primary" onclick="confirmPersianDate()">تأیید</button>
            </div>
        </div>
    `);
    
    return popup;
}

function generateYearOptions(currentYear) {
    var options = '';
    for (var i = 1400; i <= 1410; i++) {
        var selected = i === currentYear ? 'selected' : '';
        options += `<option value="${i}" ${selected}>${i}</option>`;
    }
    return options;
}

function generateMonthOptions(currentMonth) {
    var persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر',
        'مرداد', 'شهریور', 'مهر', 'آبان',
        'آذر', 'دی', 'بهمن', 'اسفند'
    ];
    
    var options = '';
    for (var i = 1; i <= 12; i++) {
        var selected = i === currentMonth ? 'selected' : '';
        options += `<option value="${i}" ${selected}>${persianMonths[i-1]}</option>`;
    }
    return options;
}

function generatePersianCalendar(year, month, selectedDay) {
    var daysInMonth = month <= 6 ? 31 : 30;
    if (month === 12 && year % 4 === 3) daysInMonth = 30; // Leap year
    
    var html = '';
    var today = getTodayPersian();
    
    // Add empty cells for alignment (simplified)
    for (var i = 1; i <= daysInMonth; i++) {
        var isToday = (year === today.year && month === today.month && i === today.day);
        var isSelected = (i === selectedDay);
        var classes = 'day';
        if (isToday) classes += ' today';
        if (isSelected) classes += ' selected';
        
        html += `<div class="${classes}" onclick="selectPersianDay(${i})">${i}</div>`;
    }
    
    return html;
}

function selectPersianDay(day) {
    if (currentDatePicker) {
        currentDatePicker.find('.day').removeClass('selected');
        currentDatePicker.find('.day').each(function(index) {
            if ($(this).text() == day) {
                $(this).addClass('selected');
            }
        });
    }
}

function updatePersianCalendar() {
    if (currentDatePicker) {
        var year = parseInt(currentDatePicker.find('#picker-year').val());
        var month = parseInt(currentDatePicker.find('#picker-month').val());
        var calendar = currentDatePicker.find('.calendar');
        
        var calendarHtml = generatePersianCalendar(year, month, 1);
        calendar.html(`
            <div class="day-header">ش</div>
            <div class="day-header">ی</div>
            <div class="day-header">د</div>
            <div class="day-header">س</div>
            <div class="day-header">چ</div>
            <div class="day-header">پ</div>
            <div class="day-header">ج</div>
            ${calendarHtml}
        `);
    }
}

function confirmPersianDate() {
    if (currentDatePicker && currentInputId) {
        var year = currentDatePicker.find('#picker-year').val();
        var month = currentDatePicker.find('#picker-month').val();
        var selectedDay = currentDatePicker.find('.day.selected');
        
        if (selectedDay.length > 0) {
            var day = selectedDay.text();
            var formattedDate = year + '/' + month + '/' + day;
            $('#' + currentInputId).val(formattedDate);
            console.log('Date set to:', formattedDate);
        } else {
            alert('لطفاً یک روز را انتخاب کنید.');
            return;
        }
    }
    closePersianDatePicker();
}

function clearPersianDate() {
    if (currentInputId) {
        $('#' + currentInputId).val('');
    }
    closePersianDatePicker();
}

function closePersianDatePicker() {
    if (currentDatePicker) {
        currentDatePicker.remove();
        currentDatePicker = null;
    }
    $(document).off('click.persian-picker');
    $(document).off('keydown.persian-picker');
    currentInputId = null;
}

function parsePersianDate(dateStr) {
    if (!dateStr || !/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(dateStr)) {
        return getTodayPersian();
    }
    var parts = dateStr.split('/');
    return {
        year: parseInt(parts[0]),
        month: parseInt(parts[1]),
        day: parseInt(parts[2])
    };
}

function getTodayPersian() {
    // Simple approximation - in real app you'd use proper conversion
    return { year: 1404, month: 1, day: 1 };
}

function createPersianDatePickerModal() {
    var modalHtml = `
        <div id="persianDatePickerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); z-index: 99999;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; padding: 20px; min-width: 400px; max-width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.5); border: 2px solid #007bff;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
                    <h4 style="margin: 0; color: #333; font-weight: bold;">انتخاب تاریخ شمسی</h4>
                    <button type="button" onclick="closePersianDatePicker()" style="background: #dc3545; color: white; border: none; font-size: 20px; cursor: pointer; padding: 5px 10px; border-radius: 4px;">&times;</button>
                </div>
                
                <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 120px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px;">سال:</label>
                        <select id="picker-year" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                            <option value="1400">1400</option>
                            <option value="1401">1401</option>
                            <option value="1402">1402</option>
                            <option value="1403">1403</option>
                            <option value="1404" selected>1404</option>
                            <option value="1405">1405</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px;">ماه:</label>
                        <select id="picker-month" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
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
                    <div style="flex: 1; min-width: 120px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555; font-size: 14px;">روز:</label>
                        <select id="picker-day" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: white;">
                            <!-- Days will be populated dynamically -->
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <button type="button" onclick="confirmPersianDate()" style="background: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold;">تأیید</button>
                    <button type="button" onclick="closePersianDatePicker()" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold;">لغو</button>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
    console.log('Modal created and appended to body');
    
    // Initialize month change handler
    $('#picker-month').on('change', updateDays);
    
    // Close modal when clicking outside
    $('#persianDatePickerModal').on('click', function(e) {
        if (e.target === this) {
            closePersianDatePicker();
        }
    });
}

function updateDays() {
    // This function is no longer needed with custom date picker
    // Keeping it for compatibility
}

function confirmPersianDate() {
    // This function is now implemented above
}

function closePersianDatePicker() {
    // This function is now implemented above
}

// Event handlers
$(document).ready(function() {
    console.log('Document ready - Initializing Persian Date Picker');
    
    // Clear any existing date pickers
    clearOldDatePickers();
    
    // Initialize Persian date picker buttons
    $(document).on('click', 'button[data-target]', function() {
        var targetId = $(this).data('target');
        console.log('Button clicked for:', targetId);
        showPersianDatePicker(targetId);
    });
    
    // Remove modal backdrop when it appears
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).hasClass('modal-backdrop')) {
            $(e.target).remove();
        }
    });
    
    // Initialize month change handler
    $('#picker-month').on('change', updateDays);
    
    // Initialize date input validation
    $('.persian-date-input').on('blur', function() {
        var value = $(this).val();
        if (value && !/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(value)) {
            alert('لطفاً تاریخ را به فرمت صحیح وارد کنید (مثال: 1403/1/1)');
            $(this).focus();
        }
    });
    
    // Initialize date input formatting
    $('.persian-date-input').on('input', function() {
        var value = $(this).val();
        value = value.replace(/[^\d\/]/g, '');
        $(this).val(value);
    });
});

// Main comparison function
$('#run-comparison').on('click', function() {
    var range1_from = $('#range1_from').val().trim();
    var range1_to = $('#range1_to').val().trim();
    var range2_from = $('#range2_from').val().trim();
    var range2_to = $('#range2_to').val().trim();
    var sales_status = $('#sales_status_filter').val();
    var customer = $('#customer_filter').val().trim();
    var product = $('#product_filter').val().trim();
    
    if ((!range1_from && !range1_to) && (!range2_from && !range2_to)) {
        alert('لطفاً حداقل یک بازه تاریخ را انتخاب کنید.');
        return;
    }
    
    $(this).prop('disabled', true).html('<i class="ti-reload"></i> در حال بارگذاری...');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Get summary data
    $.post('/report/sales/comparison/data', {
        range1_from: range1_from,
        range1_to: range1_to,
        range2_from: range2_from,
        range2_to: range2_to,
        sales_status: sales_status,
        customer: customer,
        product: product
    }).done(function(resp) {
        if (resp.success) {
            $('#comparison-results').show();
            $('#tables-section').show();
            $('#chart-section').show();
            
            // Update summary
            $('#r1-count').text(resp.data.range1.count);
            $('#r1-total').text(fmt(resp.data.range1.total_sales_amount));
            $('#r1-avg').text(fmt(resp.data.range1.average_sales_amount));
            $('#r2-count').text(resp.data.range2.count);
            $('#r2-total').text(fmt(resp.data.range2.total_sales_amount));
            $('#r2-avg').text(fmt(resp.data.range2.average_sales_amount));
            
            // Initialize DataTables
            initializeTables(range1_from, range1_to, range2_from, range2_to, sales_status, customer, product);
            
            // Initialize Chart
            initializeChart(resp.data);
        }
    }).fail(function(xhr) {
        console.error(xhr.responseText);
        alert('خطا در دریافت اطلاعات. لطفاً دوباره تلاش کنید.');
    }).always(function() {
        $('#run-comparison').prop('disabled', false).html('<i class="ti-bar-chart"></i> اجرای مقایسه');
    });
});

// Initialize DataTables
function initializeTables(r1_from, r1_to, r2_from, r2_to, status, customer, product) {
    // Destroy existing tables
    if (table1) {
        table1.destroy();
        table1 = null;
    }
    if (table2) {
        table2.destroy();
        table2 = null;
    }
    
    // Initialize Range 1 Table
    table1 = $('#sales-table-range1').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/report/sales/comparison/datatables',
            type: 'GET',
            data: function(d) {
                d.range1_from = r1_from;
                d.range1_to = r1_to;
                d.range2_from = r2_from;
                d.range2_to = r2_to;
                d.range = 1;
                d.sales_status = status;
                d.customer = customer;
                d.product = product;
                return d;
            }
        },
        columns: [
            { data: 'sales_id' },
            { data: 'product_name' },
            { data: 'customer_name' },
            { data: 'document_number' },
            { data: 'main_quantity' },
            { data: 'base_sales_amount', render: function(data) { return fmt(data); } },
            { data: 'base_net_amount', render: function(data) { return fmt(data); } },
            { data: 'sales_status' },
            { data: 'document_date_persian' },
            { data: 'creator' },
            { data: 'actions' }
        ],
        language: {
            "processing": "در حال پردازش...",
            "lengthMenu": "نمایش _MENU_ رکورد",
            "zeroRecords": "رکوردی یافت نشد",
            "info": "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
            "infoEmpty": "نمایش 0 تا 0 از 0 رکورد",
            "infoFiltered": "(فیلتر شده از _MAX_ رکورد)",
            "search": "جستجو:",
            "paginate": {
                "first": "ابتدا",
                "previous": "قبلی",
                "next": "بعدی",
                "last": "انتها"
            }
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        autoWidth: false,
        scrollX: true
    });
    
    // Initialize Range 2 Table
    table2 = $('#sales-table-range2').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/report/sales/comparison/datatables',
            type: 'GET',
            data: function(d) {
                d.range1_from = r1_from;
                d.range1_to = r1_to;
                d.range2_from = r2_from;
                d.range2_to = r2_to;
                d.range = 2;
                d.sales_status = status;
                d.customer = customer;
                d.product = product;
                return d;
            }
        },
        columns: [
            { data: 'sales_id' },
            { data: 'product_name' },
            { data: 'customer_name' },
            { data: 'document_number' },
            { data: 'main_quantity' },
            { data: 'base_sales_amount', render: function(data) { return fmt(data); } },
            { data: 'base_net_amount', render: function(data) { return fmt(data); } },
            { data: 'sales_status' },
            { data: 'document_date_persian' },
            { data: 'creator' },
            { data: 'actions' }
        ],
        language: {
            "processing": "در حال پردازش...",
            "lengthMenu": "نمایش _MENU_ رکورد",
            "zeroRecords": "رکوردی یافت نشد",
            "info": "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
            "infoEmpty": "نمایش 0 تا 0 از 0 رکورد",
            "infoFiltered": "(فیلتر شده از _MAX_ رکورد)",
            "search": "جستجو:",
            "paginate": {
                "first": "ابتدا",
                "previous": "قبلی",
                "next": "بعدی",
                "last": "انتها"
            }
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        autoWidth: false,
        scrollX: true
    });
}

// Initialize Chart
function initializeChart(data) {
    var ctx = document.getElementById('comparisonChart').getContext('2d');
    
    if (comparisonChart) {
        comparisonChart.destroy();
    }
    
    comparisonChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['تعداد رکورد', 'مجموع مبلغ فروش', 'میانگین مبلغ فروش'],
            datasets: [
                {
                    label: 'بازه 1',
                    data: [
                        data.range1.count,
                        data.range1.total_sales_amount,
                        data.range1.average_sales_amount
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'بازه 2',
                    data: [
                        data.range2.count,
                        data.range2.total_sales_amount,
                        data.range2.average_sales_amount
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'مقایسه آمار فروش'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Export functions
function exportTable(range, format) {
    var table = range === 'range1' ? table1 : table2;
    if (table) {
        if (format === 'excel') {
            table.button('.buttons-excel').trigger();
        } else if (format === 'pdf') {
            table.button('.buttons-pdf').trigger();
        }
    }
}
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom Persian Date Picker -->
<style>
.persian-datepicker-popup {
    position: absolute;
    background: white;
    border: 2px solid #007bff;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    padding: 15px;
    z-index: 9999;
    min-width: 280px;
    font-family: 'Tahoma', sans-serif;
}

.persian-datepicker-popup .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.persian-datepicker-popup .month-year {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.persian-datepicker-popup select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
}

.persian-datepicker-popup .calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    margin-bottom: 15px;
}

.persian-datepicker-popup .day-header {
    text-align: center;
    font-weight: bold;
    padding: 5px;
    background: #f8f9fa;
    font-size: 12px;
}

.persian-datepicker-popup .day {
    text-align: center;
    padding: 8px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.persian-datepicker-popup .day:hover {
    background: #e3f2fd;
}

.persian-datepicker-popup .day.selected {
    background: #007bff;
    color: white;
}

.persian-datepicker-popup .day.today {
    background: #28a745;
    color: white;
}

.persian-datepicker-popup .day.other-month {
    color: #ccc;
}

.persian-datepicker-popup .buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.persian-datepicker-popup button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.persian-datepicker-popup .btn-primary {
    background: #007bff;
    color: white;
}

.persian-datepicker-popup .btn-secondary {
    background: #6c757d;
    color: white;
}

.persian-datepicker-popup .btn-danger {
    background: #dc3545;
    color: white;
}

/* Hide any modal backdrop */
.modal-backdrop {
    display: none !important;
}

/* Force clear any existing date picker styles */
.kamadatepicker-popup,
.persian-datepicker-popup-old,
.modal-backdrop {
    display: none !important;
}
</style>