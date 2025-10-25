<div class="reports-list-page-filter-container">
    <form class="form-inline row gy-2 gx-3 align-items-center" id="reports-list-page-filter-form">

        <!-- From Date -->
        <div class="form-group row">
            <label class="form-label">از تاریخ</label>
            <div class="input-group">
                <input type="text" id="document_date_from" class="form-control form-control-sm persian-datepicker" autocomplete="off" placeholder="1403/01/01" name="document_date_from">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersianDatePicker('document_date_from')">
                    <i class="ti-calendar"></i>
                </button>
            </div>
        </div>

        <!-- To Date -->
        <div class="form-group row">
            <label class="form-label">تا تاریخ</label>
            <div class="input-group">
                <input type="text" id="document_date_to" class="form-control form-control-sm persian-datepicker" autocomplete="off" placeholder="1403/12/29" name="document_date_to">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showPersianDatePicker('document_date_to')">
                    <i class="ti-calendar"></i>
                </button>
            </div>
        </div>

        <!-- Customer Filter -->
        <div class="form-group row">
            <label class="form-label">مشتری</label>
            <input type="text" id="column_customer_name" class="form-control form-control-sm" placeholder="نام مشتری" name="column_customer_name">
        </div>

        <!-- Warehouse Filter -->
        <div class="form-group row">
            <label class="form-label">انبار</label>
            <input type="text" id="column_warehouse" class="form-control form-control-sm" placeholder="انبار" name="column_warehouse">
        </div>

        <!--form buttons-->
        <div class="col-auto">
            <input type="hidden" name="report-form" value="filter">
            <button type="submit" id="submitButton" class="btn btn-info btn-sm waves-effect text-left ajax-request"
                data-url="{{ url('report/sales/aggregates?action=load') }}"
                data-loading-target="report-results-container" data-ajax-type="POST"
                data-form-id="reports-list-page-filter-form"
                data-on-start-submit-button="disable">@lang('lang.update_report')</button>
        </div>
    </form>
</div>

<script>
// Simple Persian date picker function
function showPersianDatePicker(inputId) {
    var currentValue = $('#' + inputId).val();
    var year = 1403, month = 1, day = 1;
    
    if (currentValue && /^\d{4}\/\d{1,2}\/\d{1,2}$/.test(currentValue)) {
        var parts = currentValue.split('/');
        year = parseInt(parts[0]);
        month = parseInt(parts[1]);
        day = parseInt(parts[2]);
    }
    
    // Create a simple date picker dialog
    var persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر',
        'مرداد', 'شهریور', 'مهر', 'آبان',
        'آذر', 'دی', 'بهمن', 'اسفند'
    ];
    
    var yearOptions = '';
    for (var i = 1400; i <= 1410; i++) {
        yearOptions += '<option value="' + i + '"' + (i === year ? ' selected' : '') + '>' + i + '</option>';
    }
    
    var monthOptions = '';
    for (var i = 1; i <= 12; i++) {
        monthOptions += '<option value="' + i + '"' + (i === month ? ' selected' : '') + '>' + persianMonths[i-1] + '</option>';
    }
    
    var dayOptions = '';
    var daysInMonth = month <= 6 ? 31 : 30;
    for (var i = 1; i <= daysInMonth; i++) {
        dayOptions += '<option value="' + i + '"' + (i === day ? ' selected' : '') + '>' + i + '</option>';
    }
    
    var dialog = `
        <div id="datePickerDialog" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
             background: white; border: 2px solid #ccc; border-radius: 8px; padding: 20px; z-index: 9999; 
             box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
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
             background: rgba(0,0,0,0.5); z-index: 9998;"></div>
    `;
    
    // Remove existing dialog
    $('#datePickerDialog, #datePickerOverlay').remove();
    
    // Add new dialog
    $('body').append(dialog);
    
    // Update days when month changes
    $('#picker-month').on('change', function() {
        var selectedMonth = parseInt($(this).val());
        var daysInMonth = selectedMonth <= 6 ? 31 : 30;
        var daySelect = $('#picker-day');
        var currentDay = parseInt(daySelect.val());
        
        daySelect.empty();
        for (var i = 1; i <= daysInMonth; i++) {
            daySelect.append('<option value="' + i + '"' + (i === Math.min(currentDay, daysInMonth) ? ' selected' : '') + '>' + i + '</option>');
        }
    });
}

function confirmDate(inputId) {
    var year = $('#picker-year').val();
    var month = $('#picker-month').val();
    var day = $('#picker-day').val();
    
    var formattedDate = year + '/' + month + '/' + day;
    $('#' + inputId).val(formattedDate);
    
    // Remove dialog
    $('#datePickerDialog, #datePickerOverlay').remove();
}

function cancelDate() {
    // Remove dialog
    $('#datePickerDialog, #datePickerOverlay').remove();
}
</script>
