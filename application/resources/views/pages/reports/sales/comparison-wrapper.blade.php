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
                    <div class="card mb-4" id="sales-table-range1-wrapper">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">گزارش بازه 1</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-info js-toggle-table-config" data-target="table-config-report-range1">
                                    <i class="ti-settings"></i> تنظیمات ستون‌ها
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning clear-column-searches-range1">
                                    <i class="ti-refresh"></i> پاک کردن فیلترها
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportTable('range1', 'excel')">
                                    <i class="ti-file"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportTable('range1', 'pdf')">
                                    <i class="ti-file"></i> PDF
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive list-table-wrapper">
                                <table id="sales-table-range1" class="table m-t-0 m-b-0 table-hover no-wrap" data-page-size="10">
                                    <thead>
                                        <!-- Header Row with Sort and Filter -->
                                        <tr>
                                            <th class="sales_col_id">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="sales_id" href="javascript:void(0)">
                                                        @lang('lang.id')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="sales_id" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_document_number">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="document_number" href="javascript:void(0)">
                                                        @lang('lang.document_number')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="document_number" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_customer_name">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="customer_name" href="javascript:void(0)">
                                                        @lang('lang.customer_name')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="customer_name" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_product_name">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="product_name" href="javascript:void(0)">
                                                        @lang('lang.product_name')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="product_name" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_main_quantity">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="main_quantity" href="javascript:void(0)">
                                                        @lang('lang.main_quantity')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="main_quantity" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_base_price">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="base_price" href="javascript:void(0)">
                                                        @lang('lang.base_price')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="base_price" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_base_sales_amount">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="base_sales_amount" href="javascript:void(0)">
                                                        مبلغ فروش<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="base_sales_amount" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_base_net_amount">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="base_net_amount" href="javascript:void(0)">
                                                        @lang('lang.base_net_amount')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="base_net_amount" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_document_type">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="document_type" href="javascript:void(0)">
                                                        @lang('lang.document_type')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="document_type" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_document_date">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="document_date" href="javascript:void(0)">
                                                        @lang('lang.document_date')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="document_date" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_sales_status">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="sales_status" href="javascript:void(0)">
                                                        @lang('lang.sales_status')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="sales_status" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_creator">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="1" data-orderby="creator" href="javascript:void(0)">
                                                        @lang('lang.created_by')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="creator" data-range="1" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_actions">عملیات</th>
                                        </tr>
                                        <!-- Search Row -->
                                        <tr class="search-row">
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="sales_id" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="document_number" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="customer_name" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="product_name" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="main_quantity" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="base_price" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="base_sales_amount" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="base_net_amount" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="document_type" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="document_date" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="sales_status" data-range="1"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="creator" data-range="1"></th>
                                            <th></th>
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
                    <div class="card" id="sales-table-range2-wrapper">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">گزارش بازه 2</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-info js-toggle-table-config" data-target="table-config-report-range2">
                                    <i class="ti-settings"></i> تنظیمات ستون‌ها
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning clear-column-searches-range2">
                                    <i class="ti-refresh"></i> پاک کردن فیلترها
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportTable('range2', 'excel')">
                                    <i class="ti-file"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="exportTable('range2', 'pdf')">
                                    <i class="ti-file"></i> PDF
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive list-table-wrapper">
                                <table id="sales-table-range2" class="table m-t-0 m-b-0 table-hover no-wrap" data-page-size="10">
                                    <thead>
                                        <!-- Header Row with Sort and Filter -->
                                        <tr>
                                            <th class="sales_col_id">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="sales_id" href="javascript:void(0)">
                                                        @lang('lang.id')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="sales_id" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_document_number">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="document_number" href="javascript:void(0)">
                                                        @lang('lang.document_number')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="document_number" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_customer_name">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="customer_name" href="javascript:void(0)">
                                                        @lang('lang.customer_name')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="customer_name" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_product_name">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="product_name" href="javascript:void(0)">
                                                        @lang('lang.product_name')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="product_name" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_main_quantity">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="main_quantity" href="javascript:void(0)">
                                                        @lang('lang.main_quantity')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="main_quantity" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_base_price">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="base_price" href="javascript:void(0)">
                                                        @lang('lang.base_price')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="base_price" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_base_sales_amount">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="base_sales_amount" href="javascript:void(0)">
                                                        مبلغ فروش<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="base_sales_amount" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_base_net_amount">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="base_net_amount" href="javascript:void(0)">
                                                        @lang('lang.base_net_amount')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="base_net_amount" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_document_type">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="document_type" href="javascript:void(0)">
                                                        @lang('lang.document_type')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="document_type" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_document_date">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="document_date" href="javascript:void(0)">
                                                        @lang('lang.document_date')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="document_date" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_sales_status">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="sales_status" href="javascript:void(0)">
                                                        @lang('lang.sales_status')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="sales_status" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_creator">
                                                <div class="column-header-container">
                                                    <a class="js-ajax-ux-request js-sort-link" data-range="2" data-orderby="creator" href="javascript:void(0)">
                                                        @lang('lang.created_by')<span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                                    </a>
                                                    <span class="column-filter-dropdown" data-column="creator" data-range="2" title="فیلتر بر اساس مقادیر">
                                                        <i class="ti-angle-down"></i>
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="sales_col_actions">عملیات</th>
                                        </tr>
                                        <!-- Search Row -->
                                        <tr class="search-row">
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="sales_id" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="document_number" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="customer_name" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="product_name" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="main_quantity" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="base_price" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="base_sales_amount" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="base_net_amount" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="document_type" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="document_date" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="sales_status" data-range="2"></th>
                                            <th><input type="text" class="form-control form-control-sm column-search-input" placeholder="جستجو..." data-column="creator" data-range="2"></th>
                                            <th></th>
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

<!-- Table Configuration Sidebars -->
<!-- Range 1 Table Config -->
<div class="right-sidebar table-config-sidebar" id="table-config-report-range1" style="display: none;">
    <div class="slimscrollright">
        <div class="rpanel-title">
            <i class="ti-settings"></i> تنظیمات ستون‌ها - بازه 1
            <span>
                <i class="ti-close js-close-table-config" data-target="table-config-report-range1"></i>
            </span>
        </div>

        <div class="r-panel-body">
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="0" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">شناسه</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="1" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">شماره سند</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="2" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">نام مشتری</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="3" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">نام محصول</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="4" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">مقدار اصلی</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="5" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">قیمت پایه</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="6" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">مبلغ فروش</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="7" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">مبلغ خالص</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="8" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">نوع سند</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="9" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">تاریخ سند</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="10" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">وضعیت فروش</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="11" data-range="1" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">ایجاد کننده</span>
                </label>
            </div>
        </div>

        <div class="buttons-block">
            <button type="button" class="btn btn-rounded-x btn-secondary js-close-table-config" data-target="table-config-report-range1">بستن</button>
            <button type="button" class="btn btn-rounded-x btn-primary js-reset-table-config" data-range="1">بازنشانی</button>
        </div>
    </div>
</div>

<!-- Range 2 Table Config -->
<div class="right-sidebar table-config-sidebar" id="table-config-report-range2" style="display: none;">
    <div class="slimscrollright">
        <div class="rpanel-title">
            <i class="ti-settings"></i> تنظیمات ستون‌ها - بازه 2
            <span>
                <i class="ti-close js-close-table-config" data-target="table-config-report-range2"></i>
            </span>
        </div>

        <div class="r-panel-body">
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="0" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">شناسه</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="1" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">شماره سند</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="2" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">نام مشتری</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="3" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">نام محصول</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="4" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">مقدار اصلی</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="5" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">قیمت پایه</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="6" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">مبلغ فروش</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="7" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">مبلغ خالص</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="8" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">نوع سند</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="9" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">تاریخ سند</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="10" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">وضعیت فروش</span>
                </label>
            </div>
            
            <div class="p-b-5">
                <label class="custom-control custom-checkbox table-config-checkbox-container">
                    <input type="checkbox" class="custom-control-input table-config-checkbox" data-column="11" data-range="2" checked>
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">ایجاد کننده</span>
                </label>
            </div>
        </div>

        <div class="buttons-block">
            <button type="button" class="btn btn-rounded-x btn-secondary js-close-table-config" data-target="table-config-report-range2">بستن</button>
            <button type="button" class="btn btn-rounded-x btn-primary js-reset-table-config" data-range="2">بازنشانی</button>
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

// Initialize Advanced DataTables with sort, filter, and search
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
            { data: 'document_number' },
            { data: 'customer_name' },
            { data: 'product_name' },
            { data: 'main_quantity' },
            { data: 'base_price', render: function(data) { return fmt(data); } },
            { data: 'base_sales_amount', render: function(data) { return fmt(data); } },
            { data: 'base_net_amount', render: function(data) { return fmt(data); } },
            { data: 'document_type' },
            { data: 'document_date' },
            { data: 'sales_status' },
            { data: 'creator' },
            { data: 'actions', orderable: false, searchable: false }
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
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        autoWidth: false,
        scrollX: true,
        order: [[9, 'desc']] // Sort by document_date descending
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
            { data: 'document_number' },
            { data: 'customer_name' },
            { data: 'product_name' },
            { data: 'main_quantity' },
            { data: 'base_price', render: function(data) { return fmt(data); } },
            { data: 'base_sales_amount', render: function(data) { return fmt(data); } },
            { data: 'base_net_amount', render: function(data) { return fmt(data); } },
            { data: 'document_type' },
            { data: 'document_date' },
            { data: 'sales_status' },
            { data: 'creator' },
            { data: 'actions', orderable: false, searchable: false }
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
        dom: 'Blfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        autoWidth: false,
        scrollX: true,
        order: [[9, 'desc']] // Sort by document_date descending
    });
    
    // Setup advanced features after tables are initialized
    setupAdvancedTableFeatures(r1_from, r1_to, r2_from, r2_to, status, customer, product);
    
    // Setup table column configuration
    setupTableColumnConfig();
}

// Setup advanced table features: sort, filter, search
function setupAdvancedTableFeatures(r1_from, r1_to, r2_from, r2_to, status, customer, product) {
    
    // Handle sort links
    $(document).off('click', '.js-sort-link');
    $(document).on('click', '.js-sort-link', function(e) {
        e.preventDefault();
        var $link = $(this);
        var range = $link.data('range');
        var orderby = $link.data('orderby');
        var currentSortOrder = $link.data('sortorder') || 'asc';
        var newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
        
        // Update link data
        $link.data('sortorder', newSortOrder);
        
        // Update sorting icons
        var $parentTh = $link.closest('th');
        $parentTh.siblings().find('.sorting-icons i').removeClass('ti-angle-up ti-angle-down').addClass('ti-arrows-vertical');
        $link.find('.sorting-icons i').removeClass('ti-arrows-vertical').addClass(newSortOrder === 'asc' ? 'ti-angle-up' : 'ti-angle-down');
        
        // Send sort request to server
        $.ajax({
            url: '/report/sales/comparison',
            type: 'GET',
            data: {
                action: 'sort',
                orderby: orderby,
                sortorder: newSortOrder,
                range: range,
                range1_from: r1_from,
                range1_to: r1_to,
                range2_from: r2_from,
                range2_to: r2_to
            },
            success: function(response) {
                // Reload the appropriate table
                if (range == 1 && table1) {
                    table1.ajax.reload();
                } else if (range == 2 && table2) {
                    table2.ajax.reload();
                }
            }
        });
    });
    
    // Handle column filter dropdowns
    $(document).off('click', '.column-filter-dropdown');
    $(document).on('click', '.column-filter-dropdown', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $dropdown = $(this);
        var column = $dropdown.data('column');
        var range = $dropdown.data('range');
        
        // Close other dropdowns
        $('.column-filter-menu').removeClass('show');
        $('.column-filter-dropdown').removeClass('active');
        
        // Toggle current dropdown
        if ($dropdown.hasClass('active')) {
            $dropdown.removeClass('active');
            return;
        }
        
        $dropdown.addClass('active');
        
        // Check if menu already exists
        var $existingMenu = $dropdown.siblings('.column-filter-menu');
        if ($existingMenu.length > 0) {
            $existingMenu.addClass('show');
            return;
        }
        
        // Create and show loading menu
        var $menu = $('<div class="column-filter-menu"><div class="column-filter-item">در حال بارگذاری...</div></div>');
        $dropdown.parent().append($menu);
        $menu.addClass('show');
        
        // Load unique values for this column
        loadColumnUniqueValues(column, range, $menu, r1_from, r1_to, r2_from, r2_to);
    });
    
    // Close dropdowns when clicking outside
    $(document).off('click.filter-dropdown');
    $(document).on('click.filter-dropdown', function(e) {
        if (!$(e.target).closest('.column-filter-dropdown, .column-filter-menu').length) {
            $('.column-filter-menu').removeClass('show');
            $('.column-filter-dropdown').removeClass('active');
        }
    });
    
    // Handle column search inputs
    $(document).off('input', '.column-search-input');
    $(document).on('input', '.column-search-input', function() {
        var $input = $(this);
        var column = $input.data('column');
        var range = $input.data('range');
        var searchValue = $input.val();
        
        // Clear previous timeout
        clearTimeout($input.data('search-timeout'));
        
        // Set new timeout for live search
        $input.data('search-timeout', setTimeout(function() {
            performColumnSearch(column, range, searchValue, r1_from, r1_to, r2_from, r2_to);
        }, 800));
    });
    
    // Handle enter key on search inputs
    $(document).off('keypress', '.column-search-input');
    $(document).on('keypress', '.column-search-input', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            var $input = $(this);
            var column = $input.data('column');
            var range = $input.data('range');
            var searchValue = $input.val();
            
            clearTimeout($input.data('search-timeout'));
            performColumnSearch(column, range, searchValue, r1_from, r1_to, r2_from, r2_to);
        }
    });
    
    // Handle clear searches buttons
    $('.clear-column-searches-range1').off('click').on('click', function() {
        $('#sales-table-range1 .column-search-input').val('');
        if (table1) {
            table1.ajax.reload();
        }
    });
    
    $('.clear-column-searches-range2').off('click').on('click', function() {
        $('#sales-table-range2 .column-search-input').val('');
        if (table2) {
            table2.ajax.reload();
        }
    });
}

// Load unique values for column filter
function loadColumnUniqueValues(column, range, $menu, r1_from, r1_to, r2_from, r2_to) {
    $.ajax({
        url: '/report/sales/comparison',
        type: 'GET',
        data: {
            action: 'unique_values',
            column: column,
            range: range,
            range1_from: r1_from,
            range1_to: r1_to,
            range2_from: r2_from,
            range2_to: r2_to
        },
        success: function(response) {
            if (response.success && response.data) {
                var html = '';
                if (response.data.length === 0) {
                    html = '<div class="column-filter-item">مقداری یافت نشد</div>';
                } else {
                    // Add "All" option
                    html += '<div class="column-filter-item" data-value=""><i class="ti-reload"></i> همه</div>';
                    
                    // Add unique values
                    response.data.forEach(function(value) {
                        html += '<div class="column-filter-item" data-value="' + escapeHtml(value) + '">' + escapeHtml(value) + '</div>';
                    });
                }
                $menu.html(html);
                
                // Handle filter item clicks
                $menu.find('.column-filter-item').off('click').on('click', function() {
                    var filterValue = $(this).data('value');
                    var $input = $menu.closest('th').find('.column-search-input');
                    if ($input.length) {
                        $input.val(filterValue);
                        $input.trigger('input');
                    }
                    $menu.removeClass('show');
                    $menu.siblings('.column-filter-dropdown').removeClass('active');
                });
            }
        },
        error: function() {
            $menu.html('<div class="column-filter-item">خطا در بارگذاری</div>');
        }
    });
}

// Perform column search
function performColumnSearch(column, range, searchValue, r1_from, r1_to, r2_from, r2_to) {
    var searchParams = {
        action: 'search',
        range: range,
        range1_from: r1_from,
        range1_to: r1_to,
        range2_from: r2_from,
        range2_to: r2_to
    };
    searchParams['column_search_' + column] = searchValue;
    
    $.ajax({
        url: '/report/sales/comparison',
        type: 'GET',
        data: searchParams,
        success: function(response) {
            // Reload the appropriate table
            if (range == 1 && table1) {
                table1.ajax.reload();
            } else if (range == 2 && table2) {
                table2.ajax.reload();
            }
        }
    });
}

// Utility function to escape HTML
function escapeHtml(text) {
    if (typeof text !== 'string') return text;
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Setup Table Column Configuration
function setupTableColumnConfig() {
    // Toggle table config sidebar
    $(document).off('click', '.js-toggle-table-config');
    $(document).on('click', '.js-toggle-table-config', function() {
        var target = $(this).data('target');
        $('#' + target).fadeToggle(300);
        $('body').toggleClass('show-table-config-sidebar');
    });
    
    // Close table config sidebar
    $(document).off('click', '.js-close-table-config');
    $(document).on('click', '.js-close-table-config', function() {
        var target = $(this).data('target');
        $('#' + target).fadeOut(300);
        $('body').removeClass('show-table-config-sidebar');
    });
    
    // Handle column visibility toggle
    $(document).off('change', '.table-config-checkbox');
    $(document).on('change', '.table-config-checkbox', function() {
        var $checkbox = $(this);
        var columnIndex = $checkbox.data('column');
        var range = $checkbox.data('range');
        var isVisible = $checkbox.is(':checked');
        
        // Get the appropriate table
        var table = range == 1 ? table1 : table2;
        
        if (table) {
            // Toggle column visibility
            var column = table.column(columnIndex);
            column.visible(isVisible);
            
            // Save to localStorage
            saveColumnConfig(range, columnIndex, isVisible);
        }
    });
    
    // Reset table config
    $(document).off('click', '.js-reset-table-config');
    $(document).on('click', '.js-reset-table-config', function() {
        var range = $(this).data('range');
        
        // Reset all checkboxes to checked
        $('[data-range="' + range + '"].table-config-checkbox').prop('checked', true).trigger('change');
        
        // Clear localStorage
        localStorage.removeItem('sales_report_columns_range' + range);
        
        // Show success message
        alert('تنظیمات ستون‌ها بازنشانی شد');
    });
    
    // Load saved column config from localStorage
    loadColumnConfigs();
}

// Save column configuration to localStorage
function saveColumnConfig(range, columnIndex, isVisible) {
    var storageKey = 'sales_report_columns_range' + range;
    var config = JSON.parse(localStorage.getItem(storageKey) || '{}');
    config[columnIndex] = isVisible;
    localStorage.setItem(storageKey, JSON.stringify(config));
}

// Load column configurations from localStorage
function loadColumnConfigs() {
    // Load for range 1
    var config1 = JSON.parse(localStorage.getItem('sales_report_columns_range1') || '{}');
    Object.keys(config1).forEach(function(columnIndex) {
        var isVisible = config1[columnIndex];
        var $checkbox = $('[data-range="1"][data-column="' + columnIndex + '"]');
        $checkbox.prop('checked', isVisible);
        
        if (table1) {
            table1.column(parseInt(columnIndex)).visible(isVisible);
        }
    });
    
    // Load for range 2
    var config2 = JSON.parse(localStorage.getItem('sales_report_columns_range2') || '{}');
    Object.keys(config2).forEach(function(columnIndex) {
        var isVisible = config2[columnIndex];
        var $checkbox = $('[data-range="2"][data-column="' + columnIndex + '"]');
        $checkbox.prop('checked', isVisible);
        
        if (table2) {
            table2.column(parseInt(columnIndex)).visible(isVisible);
        }
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
/* Advanced Table Styles */
.column-header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.column-header-container a {
    flex: 1;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
    gap: 5px;
}

.column-header-container a:hover {
    color: #007bff;
}

.sorting-icons {
    display: inline-flex;
    align-items: center;
}

.sorting-icons i {
    font-size: 12px;
    margin-left: 3px;
}

.column-filter-dropdown {
    cursor: pointer;
    padding: 4px;
    border-radius: 3px;
    transition: all 0.2s;
}

.column-filter-dropdown:hover {
    background: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

.column-filter-dropdown.active {
    background: #007bff;
    color: white;
}

.column-filter-menu {
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    min-width: 180px;
    display: none;
    top: 100%;
    right: 0;
    margin-top: 5px;
}

.column-filter-menu.show {
    display: block;
}

.column-filter-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}

.column-filter-item:last-child {
    border-bottom: none;
}

.column-filter-item:hover {
    background: #f8f9fa;
}

.column-filter-item i {
    margin-right: 5px;
}

.column-search-input {
    width: 100%;
    min-width: 80px;
}

.column-search-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.search-row th {
    padding: 5px;
    background: #f8f9fa;
}

.list-table-wrapper {
    min-height: 400px;
}

thead th {
    position: relative;
    white-space: nowrap;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .column-header-container {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .column-filter-dropdown {
        position: absolute;
        top: 5px;
        right: 5px;
    }
}

/* Table Configuration Sidebar */
.table-config-sidebar {
    position: fixed;
    top: 0;
    left: -300px;
    width: 300px;
    height: 100%;
    background: white;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    z-index: 9999;
    transition: left 0.3s ease;
    overflow-y: auto;
}

.table-config-sidebar.show,
body.show-table-config-sidebar .table-config-sidebar {
    left: 0;
}

.table-config-sidebar .rpanel-title {
    background: #007bff;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
}

.table-config-sidebar .rpanel-title i.ti-close {
    cursor: pointer;
    font-size: 18px;
}

.table-config-sidebar .rpanel-title i.ti-close:hover {
    color: #ffdddd;
}

.table-config-sidebar .r-panel-body {
    padding: 20px;
}

.table-config-sidebar .buttons-block {
    padding: 15px;
    background: #f8f9fa;
    border-top: 1px solid #ddd;
    position: sticky;
    bottom: 0;
    display: flex;
    gap: 10px;
    justify-content: space-between;
}

.table-config-sidebar .buttons-block button {
    flex: 1;
}

.table-config-checkbox-container {
    display: flex;
    align-items: center;
    padding: 8px 0;
    cursor: pointer;
    user-select: none;
}

.table-config-checkbox-container:hover {
    background: #f8f9fa;
    padding-left: 10px;
    padding-right: 10px;
    margin-left: -10px;
    margin-right: -10px;
    border-radius: 4px;
}

.custom-control-indicator {
    width: 18px;
    height: 18px;
    border: 2px solid #ddd;
    border-radius: 3px;
    margin-left: 10px;
    display: inline-block;
    position: relative;
    transition: all 0.2s;
}

.custom-control-input:checked ~ .custom-control-indicator {
    background: #007bff;
    border-color: #007bff;
}

.custom-control-input:checked ~ .custom-control-indicator::after {
    content: '\2713';
    position: absolute;
    top: -2px;
    left: 3px;
    color: white;
    font-size: 14px;
}

.custom-control-description {
    font-size: 14px;
}

/* Hide checkbox input */
.custom-control-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

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