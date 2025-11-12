<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mb-4">گزارش تجمیعی مبلغ فروش</h4>
					<div class="row g-3 align-items-end">
						<div class="col-md-3">
							<label class="form-label">از تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="document_date_from" class="form-control persian-datepicker" autocomplete="off" placeholder="لطفاً تاریخ شروع گزارش را وارد کنید">
                                <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('document_date_from')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
						</div>
						<div class="col-md-3">
							<label class="form-label">تا تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="document_date_to" class="form-control persian-datepicker" autocomplete="off" placeholder="لطفاً تاریخ پایان گزارش را وارد کنید">
                                <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('document_date_to')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
						</div>
						<div class="col-md-3">
							<label class="form-label">مشتری (یونیک)</label>
							<input type="text" id="column_customer_name" class="form-control" placeholder="لطفاً نام مشتری را بنویسید">
						</div>
						<div class="col-md-3">
							<label class="form-label">انبار (یونیک)</label>
							<input type="text" id="column_warehouse" class="form-control" placeholder="لطفاً نام انبار را بنویسید">
						</div>
					</div>
					<div class="text-end mt-3">
						<button id="run-aggregates" class="btn btn-primary"><i class="ti-stats-up"></i> محاسبه</button>
					</div>

					<hr>

					<div id="aggregates-results" class="row g-3" style="display: none;">
						<div class="col-md-12">
							<div class="card bg-light">
								<div class="card-body">
									<h5 class="mb-3">نتایج تجمیعی</h5>
									<p class="mb-1">تعداد رکورد: <span id="agg-count">0</span></p>
									<p class="mb-0">مجموع مبلغ فروش: <span id="agg-total">0</span></p>
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
function fmt(amount){
	try { return new Intl.NumberFormat('fa-IR').format(parseFloat(amount||0)); } catch(e){ return amount; }
}

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

// Initialize Persian date pickers
$(document).ready(function() {
    // Add input validation
    $('.persian-datepicker').on('blur', function() {
        var value = $(this).val();
        if (value && !/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(value)) {
            alert('لطفاً تاریخ را به فرمت صحیح وارد کنید (مثال: 1403/1/1 یا 1403/01/01)');
            $(this).focus();
        }
    });
    
    // Add input formatting for each field individually
    $('#document_date_from, #document_date_to').on('input', function() {
        var value = $(this).val();
        // Remove any non-numeric characters except /
        value = value.replace(/[^\d\/]/g, '');
        $(this).val(value);
    });
});

$('#run-aggregates').on('click', function(){
	var data = {
		document_date_from: $('#document_date_from').val(),
		document_date_to: $('#document_date_to').val(),
		column_customer_name: $('#column_customer_name').val(),
		column_warehouse: $('#column_warehouse').val(),
	};
	$.post('/report/sales/aggregates/data', data).done(function(resp){
		if(resp.success){
			$('#aggregates-results').show();
			$('#agg-count').text(resp.data.count);
			$('#agg-total').text(fmt(resp.data.total_sales_amount));
		}
	}).fail(function(xhr){
		console.error(xhr.responseText);
	});
});
</script>
