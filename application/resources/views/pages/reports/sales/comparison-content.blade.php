<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mb-4">گزارش مقایسه فروش بر اساس تاریخ سند</h4>
                    <div class="row g-3 align-items-end">
						<div class="col-md-3">
							<label class="form-label">از تاریخ (بازه 1)</label>
                            <div class="input-group">
                                <input type="text" id="range1_from" class="form-control persian-datepicker" autocomplete="off" placeholder="لطفاً تاریخ شروع بازه ۱ را وارد کنید" data-field="range1_from">
                                <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range1_from')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
						</div>
						<div class="col-md-3">
							<label class="form-label">تا تاریخ (بازه 1)</label>
                            <div class="input-group">
                                <input type="text" id="range1_to" class="form-control persian-datepicker" autocomplete="off" placeholder="لطفاً تاریخ پایان بازه ۱ را وارد کنید" data-field="range1_to">
                                <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range1_to')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
						</div>
						<div class="col-md-3">
							<label class="form-label">از تاریخ (بازه 2)</label>
                            <div class="input-group">
                                <input type="text" id="range2_from" class="form-control persian-datepicker" autocomplete="off" placeholder="لطفاً تاریخ شروع بازه ۲ را وارد کنید" data-field="range2_from">
                                <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range2_from')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
						</div>
						<div class="col-md-3">
							<label class="form-label">تا تاریخ (بازه 2)</label>
                            <div class="input-group">
                                <input type="text" id="range2_to" class="form-control persian-datepicker" autocomplete="off" placeholder="لطفاً تاریخ پایان بازه ۲ را وارد کنید" data-field="range2_to">
                                <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range2_to')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
						</div>
					</div>
					<div class="text-end mt-3">
						<button id="run-comparison" class="btn btn-primary"><i class="ti-bar-chart"></i> اجرا</button>
					</div>

					<hr>

                <div id="comparison-results" class="row g-3" style="display: none;">
					<div class="col-md-6">
						<div class="card bg-light">
							<div class="card-body">
								<h5 class="mb-3">بازه 1</h5>
								<p class="mb-1">تعداد رکورد: <span id="r1-count">0</span></p>
								<p class="mb-0">مجموع مبلغ فروش: <span id="r1-total">0</span></p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card bg-light">
							<div class="card-body">
								<h5 class="mb-3">بازه 2</h5>
								<p class="mb-1">تعداد رکورد: <span id="r2-count">0</span></p>
								<p class="mb-0">مجموع مبلغ فروش: <span id="r2-total">0</span></p>
							</div>
						</div>
					</div>
				</div>

				<!-- Tables Section -->
				<div id="tables-section" class="row g-3 mt-4" style="display: none;">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="mb-0">جدول بازه 1</h5>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-sm table-bordered" id="tbl-range1">
										<thead>
											<tr>
												<th>ID</th>
												<th>تاریخ سند</th>
												<th>مشتری</th>
												<th>محصول</th>
												<th>مقدار</th>
												<th>مبلغ فروش</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h5 class="mb-0">جدول بازه 2</h5>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-sm table-bordered" id="tbl-range2">
										<thead>
											<tr>
												<th>ID</th>
												<th>تاریخ سند</th>
												<th>مشتری</th>
												<th>محصول</th>
												<th>مقدار</th>
												<th>مبلغ فروش</th>
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
			</div>
		</div>
	</div>
</div>

<!-- Custom Persian Date Picker - No external libraries needed -->

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
    // Disable automatic Persian date picker initialization
    // We'll use our custom modal instead
    console.log('Using custom Persian date picker');
    
    // Add input validation
    $('.persian-datepicker').on('blur', function() {
        var value = $(this).val();
        if (value && !/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(value)) {
            alert('لطفاً تاریخ را به فرمت صحیح وارد کنید (مثال: 1403/1/1 یا 1403/01/01)');
            $(this).focus();
        }
    });
    
    // Add input formatting for each field individually
    $('#range1_from, #range1_to, #range2_from, #range2_to').on('input', function() {
        var value = $(this).val();
        // Remove any non-numeric characters except /
        value = value.replace(/[^\d\/]/g, '');
        $(this).val(value);
    });
});

$('#run-comparison').on('click', function(){
	// Validate that at least one date range is provided
	var range1_from = $('#range1_from').val();
	var range1_to = $('#range1_to').val();
	var range2_from = $('#range2_from').val();
	var range2_to = $('#range2_to').val();
	
	if ((!range1_from && !range1_to) && (!range2_from && !range2_to)) {
		alert('لطفاً حداقل یک بازه تاریخ را انتخاب کنید.');
		return;
	}
	
	// Validate date format
	var dateRegex = /^\d{4}\/\d{1,2}\/\d{1,2}$/;
	if (range1_from && !dateRegex.test(range1_from)) {
		alert('فرمت تاریخ بازه 1 (از) صحیح نیست. لطفاً از فرمت 1403/1/1 یا 1403/01/01 استفاده کنید.');
		console.log('Invalid range1_from:', range1_from);
		return;
	}
	if (range1_to && !dateRegex.test(range1_to)) {
		alert('فرمت تاریخ بازه 1 (تا) صحیح نیست. لطفاً از فرمت 1403/1/1 یا 1403/01/01 استفاده کنید.');
		console.log('Invalid range1_to:', range1_to);
		return;
	}
	if (range2_from && !dateRegex.test(range2_from)) {
		alert('فرمت تاریخ بازه 2 (از) صحیح نیست. لطفاً از فرمت 1403/1/1 یا 1403/01/01 استفاده کنید.');
		console.log('Invalid range2_from:', range2_from);
		return;
	}
	if (range2_to && !dateRegex.test(range2_to)) {
		alert('فرمت تاریخ بازه 2 (تا) صحیح نیست. لطفاً از فرمت 1403/1/1 یا 1403/01/01 استفاده کنید.');
		console.log('Invalid range2_to:', range2_to);
		return;
	}
	
	// Show loading state
	$(this).prop('disabled', true).html('<i class="ti-reload"></i> در حال بارگذاری...');
	
	var data = {
		range1_from: range1_from,
		range1_to: range1_to,
		range2_from: range2_from,
		range2_to: range2_to,
		_action: 'comparison'
	};
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	$.post('/report/sales/comparison/data', data).done(function(resp){
		if(resp.success){
			// Show results sections
			$('#comparison-results').show();
			$('#tables-section').show();
			
			// Update summary data
			$('#r1-count').text(resp.data.range1.count);
			$('#r1-total').text(fmt(resp.data.range1.total_sales_amount));
			$('#r2-count').text(resp.data.range2.count);
			$('#r2-total').text(fmt(resp.data.range2.total_sales_amount));

        // fill tables
        var tbody1 = $('#tbl-range1 tbody');
        var tbody2 = $('#tbl-range2 tbody');
        tbody1.empty(); tbody2.empty();
        
        if (resp.data.range1.rows && resp.data.range1.rows.length > 0) {
            (resp.data.range1.rows || []).forEach(function(r){
                tbody1.append('<tr>'+
                    '<td>'+r.sales_id+'</td>'+
                    '<td>'+r.document_date_persian+'</td>'+
                    '<td>'+(r.customer_name||'')+'</td>'+
                    '<td>'+(r.product_name||'')+'</td>'+
                    '<td>'+(r.main_quantity||0)+'</td>'+
                    '<td>'+fmt(r.base_sales_amount||0)+'</td>'+
                '</tr>');
            });
        } else {
            tbody1.append('<tr><td colspan="6" class="text-center">هیچ رکوردی یافت نشد</td></tr>');
        }
        
        if (resp.data.range2.rows && resp.data.range2.rows.length > 0) {
            (resp.data.range2.rows || []).forEach(function(r){
                tbody2.append('<tr>'+
                    '<td>'+r.sales_id+'</td>'+
                    '<td>'+r.document_date_persian+'</td>'+
                    '<td>'+(r.customer_name||'')+'</td>'+
                    '<td>'+(r.product_name||'')+'</td>'+
                    '<td>'+(r.main_quantity||0)+'</td>'+
                    '<td>'+fmt(r.base_sales_amount||0)+'</td>'+
                '</tr>');
            });
        } else {
            tbody2.append('<tr><td colspan="6" class="text-center">هیچ رکوردی یافت نشد</td></tr>');
        }
		}
	}).fail(function(xhr){
		console.error(xhr.responseText);
		alert('خطا در دریافت اطلاعات. لطفاً دوباره تلاش کنید.');
	}).always(function(){
		// Reset button state
		$('#run-comparison').prop('disabled', false).html('<i class="ti-bar-chart"></i> اجرا');
	});
});
</script>
