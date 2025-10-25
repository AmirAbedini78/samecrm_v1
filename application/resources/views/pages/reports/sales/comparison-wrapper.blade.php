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
                            <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range1_from')">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تا تاریخ (بازه 1)</label>
                        <div class="input-group">
                            <input type="text" id="range1_to" class="form-control persian-date-input" 
                                   placeholder="1403/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range1_to')">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">از تاریخ (بازه 2)</label>
                        <div class="input-group">
                            <input type="text" id="range2_from" class="form-control persian-date-input" 
                                   placeholder="1404/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range2_from')">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تا تاریخ (بازه 2)</label>
                        <div class="input-group">
                            <input type="text" id="range2_to" class="form-control persian-date-input" 
                                   placeholder="1404/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" onclick="showPersianDatePicker('range2_to')">
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

<!-- Persian Date Picker Modal -->
<div id="persianDatePickerModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">انتخاب تاریخ شمسی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-4">
                        <label class="form-label">سال:</label>
                        <select id="picker-year" class="form-select form-select-sm">
                            <option value="1400">1400</option>
                            <option value="1401">1401</option>
                            <option value="1402">1402</option>
                            <option value="1403">1403</option>
                            <option value="1404" selected>1404</option>
                            <option value="1405">1405</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label">ماه:</label>
                        <select id="picker-month" class="form-select form-select-sm">
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
                    <div class="col-4">
                        <label class="form-label">روز:</label>
                        <select id="picker-day" class="form-select form-select-sm">
                            <!-- Days will be populated dynamically -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="confirmPersianDate()">تأیید</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
            </div>
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

// Persian Date Picker Functions
function showPersianDatePicker(inputId) {
    currentDateInput = inputId;
    var currentValue = $('#' + inputId).val();
    
    if (currentValue && /^\d{4}\/\d{1,2}\/\d{1,2}$/.test(currentValue)) {
        var parts = currentValue.split('/');
        $('#picker-year').val(parts[0]);
        $('#picker-month').val(parseInt(parts[1]));
        updateDays();
        $('#picker-day').val(parseInt(parts[2]));
    } else {
        $('#picker-year').val('1404');
        $('#picker-month').val('1');
        updateDays();
        $('#picker-day').val('1');
    }
    
    $('#persianDatePickerModal').modal('show');
}

function updateDays() {
    var month = parseInt($('#picker-month').val());
    var year = parseInt($('#picker-year').val());
    var daysInMonth = month <= 6 ? 31 : 30;
    
    // Check for leap year in Persian calendar
    if (month == 12 && year % 4 == 3) {
        daysInMonth = 30; // Leap year
    }
    
    $('#picker-day').empty();
    for (var i = 1; i <= daysInMonth; i++) {
        $('#picker-day').append('<option value="' + i + '">' + i + '</option>');
    }
}

function confirmPersianDate() {
    var year = $('#picker-year').val();
    var month = $('#picker-month').val();
    var day = $('#picker-day').val();
    
    var formattedDate = year + '/' + month + '/' + day;
    $('#' + currentDateInput).val(formattedDate);
    
    $('#persianDatePickerModal').modal('hide');
}

// Event handlers
$(document).ready(function() {
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