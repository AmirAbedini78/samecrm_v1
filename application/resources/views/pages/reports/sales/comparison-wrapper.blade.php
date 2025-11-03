<!-- Sales Comparison Report Wrapper -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">گزارش مقایسه فروش بر اساس تاریخ سند</h4>
                
                <!-- Date Range Filters -->
                <div class="row align-items-end mb-4">
                    <div class="col-md-3">
                        <label class="form-label">از تاریخ (بازه 1)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="range1_from" class="form-control persian-date-input" 
                                   placeholder="1403/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range1_from">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تا تاریخ (بازه 1)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="range1_to" class="form-control persian-date-input" 
                                   placeholder="1403/12/29" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range1_to">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">از تاریخ (بازه 2)</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="range2_from" class="form-control persian-date-input" 
                                   placeholder="1404/01/01" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" data-target="range2_from">
                                <i class="ti-calendar"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">تا تاریخ (بازه 2)</label>
                        <div class="input-group input-group-sm">
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
                    <div class="col-md-2">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر محصول</span>
                            <small class="text-muted" id="comp-product-count"></small>
                        </label>
                        <select id="product_filter" class="form-control form-control-sm">
                            <option value="">همه محصولات</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر مشتری</span>
                            <small class="text-muted" id="comp-customer-count"></small>
                        </label>
                        <select id="customer_filter" class="form-control form-control-sm">
                            <option value="">همه مشتریان</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>فیلتر انبار</span>
                            <small class="text-muted" id="comp-warehouse-count"></small>
                        </label>
                        <select id="warehouse_filter" class="form-control form-control-sm">
                            <option value="">همه انبارها</option>
                            <option value="loading" disabled>در حال بارگذاری...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">وضعیت فروش</label>
                        <select id="sales_status_filter" class="form-control form-control-sm">
                            <option value="">همه</option>
                            <option value="completed">تکمیل شده</option>
                            <option value="pending">در انتظار</option>
                            <option value="cancelled">لغو شده</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="refresh-comparison-filters" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="ti-reload"></i> بروزرسانی لیست‌ها
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button id="run-comparison" class="btn btn-primary w-100">
                            <i class="ti-bar-chart"></i> اجرا
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

<!-- Persian Date Picker Scripts (Analytics Style) -->
<script>
const persianMonths = [
    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
];

// Global variables
var table1 = null;
var table2 = null;
var comparisonChart = null;

// Utility functions
function fmt(amount) {
    try { 
        return new Intl.NumberFormat('fa-IR').format(parseFloat(amount || 0)); 
    } catch(e) { 
        return amount; 
    }
}

// Persian Date Picker Initialization (Analytics Style)
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
    });
}

let currentPickerInput = null;
let selectedDate = { year: 1403, month: 1, day: 1 };

function openPersianDatePicker(inputId) {
    currentPickerInput = inputId;
    
    console.log('Opening date picker for:', inputId);
    
    // Get current date from input or use default
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

// Event handlers
// Load unique values for ComboBox filters
function loadComparisonUniqueFilters() {
    console.log('Loading unique filter values for comparison...');
    
    // Load unique products
    $.ajax({
        url: '/report/sales/comparison',
        method: 'GET',
        data: { action: 'unique_values', column: 'product_name' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                console.log('Unique products loaded:', response.data.length);
                populateComparisonSelect('#product_filter', response.data, 'همه محصولات', 'comp-product-count');
            }
        },
        error: function(xhr) {
            console.error('Error loading products:', xhr);
            populateComparisonSelect('#product_filter', [], 'همه محصولات', 'comp-product-count');
        }
    });
    
    // Load unique customers
    $.ajax({
        url: '/report/sales/comparison',
        method: 'GET',
        data: { action: 'unique_values', column: 'customer_name' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                console.log('Unique customers loaded:', response.data.length);
                populateComparisonSelect('#customer_filter', response.data, 'همه مشتریان', 'comp-customer-count');
            }
        },
        error: function(xhr) {
            console.error('Error loading customers:', xhr);
            populateComparisonSelect('#customer_filter', [], 'همه مشتریان', 'comp-customer-count');
        }
    });
    
    // Load unique warehouses
    $.ajax({
        url: '/report/sales/comparison',
        method: 'GET',
        data: { action: 'unique_values', column: 'warehouse' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                console.log('Unique warehouses loaded:', response.data.length);
                populateComparisonSelect('#warehouse_filter', response.data, 'همه انبارها', 'comp-warehouse-count');
            }
        },
        error: function(xhr) {
            console.error('Error loading warehouses:', xhr);
            populateComparisonSelect('#warehouse_filter', [], 'همه انبارها', 'comp-warehouse-count');
        }
    });
}

// Populate ComboBox select with options
function populateComparisonSelect(selector, data, placeholder, countId) {
    const $select = $(selector);
    const currentValue = $select.val();
    
    $select.empty();
    
    // Add placeholder option
    $select.append(`<option value="">${placeholder}</option>`);
    
    // Add data options
    if (data && data.length > 0) {
        data.forEach(function(item) {
            if (item && item.trim() !== '') {
                const isSelected = item === currentValue ? 'selected' : '';
                const displayName = item.length > 50 ? item.substring(0, 50) + '...' : item;
                $select.append(`<option value="${item}" ${isSelected}>${displayName}</option>`);
            }
        });
        
        // Update count label
        $(`#${countId}`).text(`(${data.length})`);
    } else {
        $select.append(`<option value="" disabled>داده‌ای یافت نشد</option>`);
        $(`#${countId}`).text('(0)');
    }
    
    console.log(`${selector} populated with ${data.length} items`);
}

// Refresh filters button
$('#refresh-comparison-filters').on('click', function() {
    const $btn = $(this);
    const originalHtml = $btn.html();
    
    $btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i>');
    
    console.log('Refreshing comparison filter lists...');
    loadComparisonUniqueFilters();
    
    setTimeout(function() {
        $btn.prop('disabled', false).html(originalHtml);
    }, 1500);
});

$(document).ready(function() {
    console.log('Document ready - Initializing Comparison Page');
    
    // Initialize Persian date pickers (Analytics Style)
    initPersianDatePickers();
    
    // Load unique values for ComboBoxes
    loadComparisonUniqueFilters();
    
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
    var customer = $('#customer_filter').val();
    var product = $('#product_filter').val();
    var warehouse = $('#warehouse_filter').val();
    
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
        product: product,
        warehouse: warehouse
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
            initializeTables(range1_from, range1_to, range2_from, range2_to, sales_status, customer, product, warehouse);
            
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
function initializeTables(r1_from, r1_to, r2_from, r2_to, status, customer, product, warehouse) {
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
                d.warehouse = warehouse;
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
                d.warehouse = warehouse;
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

/* Analytics Style Date Picker Hover Effects */
.persian-datepicker-popup:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.2) !important;
}

.calendar-day:hover:not(.selected) {
    background: #e7e9fd !important;
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