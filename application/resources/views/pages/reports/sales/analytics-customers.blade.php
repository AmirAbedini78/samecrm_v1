<!-- Customers Analytics Tab Content -->

<div id="customer-focus-view" style="display: none;">
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="ti-light-bulb mr-2"></i>
        <div>
            <strong>نمای اختصاصی مشتری فعال است.</strong>
            <div class="small text-muted">برای بازگشت به گزارش عمومی، فیلتر مشتری را پاک کنید یا فیلتر متفاوتی انتخاب نمایید.</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center mb-3">
                        <span><i class="ti-package"></i> محصولات خریداری شده توسط مشتری</span>
                        <small class="text-muted" id="customer-focus-products-count"></small>
                    </h5>
                    <div class="chart-container" style="height: 360px;">
                        <canvas id="customerFocusProductsChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="customerFocusProductsEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> برای این مشتری محصولی یافت نشد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-home"></i> انبارهای مورد استفاده
                    </h5>
                    <ul class="list-group list-group-sm focus-warehouse-list" id="customerFocusWarehousesList"></ul>
                    <div class="alert alert-info focus-empty-state mt-3" id="customerFocusWarehousesEmpty" style="display: none;">
                        <i class="ti-info-alt"></i> سفارشات این مشتری به انبار خاصی نسبت داده نشده است.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-pie-chart"></i> وضعیت سفارش‌های این مشتری
                    </h5>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="customerFocusStatusChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="customerFocusStatusEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> سفارش فعالی برای وضعیت نمایش وجود ندارد.
                        </div>
                    </div>
                    <div class="mt-3" id="customerFocusStatusLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-stats-up"></i> شاخص‌های کلیدی مشتری
                    </h5>
                    <ul class="list-group list-group-sm" id="customerFocusStats"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="ti-list"></i> محصولات خریداری شده (جزئیات کامل)
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="customerFocusProductsTable">
                    <thead>
                        <tr>
                            <th width="60">رتبه</th>
                            <th>محصول</th>
                            <th width="120">تعداد سفارش</th>
                            <th width="160">مبلغ کل</th>
                            <th width="160">مقدار کل (واحد)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> در حال آماده‌سازی داده‌های محصول...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="customer-general-view" class="row">
    <!-- Top Customers Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-user"></i> Top 10 مشتریان برتر
                </h5>
                <p class="text-muted">مشتریانی که بیشترین خرید را داشته‌اند</p>
                <div class="chart-container">
                    <canvas id="topCustomersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول مشتریان برتر
                </h5>
                <p class="text-muted">جزئیات خرید مشتریان</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover" id="topCustomersTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>رتبه</th>
                                <th>نام مشتری</th>
                                <th>تعداد سفارش</th>
                                <th>مبلغ کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="ti-stats-up"></i> آمار کلیدی مشتریان
                </h5>
                <div class="mb-4">
                    <p class="text-muted mb-2">تعداد مشتریان</p>
                    <h3 class="mb-0" id="totalCustomers">-</h3>
                </div>
                <div class="mb-4">
                    <p class="text-muted mb-2">مشتری برتر</p>
                    <h6 class="mb-0" id="topCustomer" style="font-size: 13px;">-</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Segmentation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-pie-chart"></i> تحلیل پارتو (قانون 80/20)
                </h5>
                <p class="text-muted">درصد مشتریان که 80% فروش را تشکیل می‌دهند</p>
                <div class="alert alert-info" role="alert" id="paretoAnalysis">
                    <i class="spinner-border spinner-border-sm"></i> در حال محاسبه...
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Percentage Analysis Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="ti-pie-chart"></i> تحلیل درصدی فروش مشتریان (Pareto & ABC Analysis)
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="ti-info-alt"></i> 
                    این تحلیل نشان می‌دهد هر مشتری چند درصد از کل فروش شما را تشکیل می‌دهد و کدام مشتریان کلیدی (A)، مهم (B) یا عادی (C) هستند.
                </p>

                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">کل فروش</h6>
                                <h4 id="percentage-total-sales">-</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">تعداد مشتریان</h6>
                                <h4 id="percentage-total-customers">-</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left: 4px solid #28a745;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Top 10 مشتری</h6>
                                <h4 id="percentage-top10" class="text-success">-</h4>
                                <small class="text-muted">از کل فروش</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left: 4px solid #ff9f40;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">20% مشتریان برتر</h6>
                                <h4 id="percentage-top20" class="text-warning">-</h4>
                                <small class="text-muted">از کل فروش (پارتو)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ABC Classification Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            <h6><i class="ti-star"></i> دسته A - کلیدی</h6>
                            <p class="mb-1"><strong id="class-a-count">-</strong> مشتری (80% اول فروش)</p>
                            <small>مشتریان استراتژیک که باید به آنها توجه ویژه شود</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <h6><i class="ti-bookmark"></i> دسته B - مهم</h6>
                            <p class="mb-1"><strong id="class-b-count">-</strong> مشتری (15% بعدی)</p>
                            <small>مشتریان مهم با پتانسیل رشد</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-info">
                            <h6><i class="ti-user"></i> دسته C - عادی</h6>
                            <p class="mb-1"><strong id="class-c-count">-</strong> مشتری (5% باقیمانده)</p>
                            <small>مشتریان عادی با حجم خرید کم</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-pie-chart"></i> نمودار دایره‌ای سهم مشتریان (Top 15)
                                </h6>
                                <div class="chart-container" style="height: 400px;">
                                    <canvas id="customerPercentagePieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Percentage Table -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-list"></i> جدول تحلیل درصدی (همه مشتریان)
                                </h6>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-hover" id="customerPercentageTable">
                                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                            <tr>
                                                <th width="50">رتبه</th>
                                                <th>مشتری</th>
                                                <th width="100">مبلغ</th>
                                                <th width="80">درصد</th>
                                                <th width="80">تجمعی</th>
                                                <th width="60">دسته</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pareto Chart (Cumulative Line) -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ti-bar-chart"></i> نمودار پارتو (نمودار تجمعی)
                                </h6>
                                <p class="text-muted">نمایش درصد تجمعی فروش نسبت به تعداد مشتریان</p>
                                <div class="chart-container" style="height: 300px;">
                                    <canvas id="paretoChart"></canvas>
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
let topCustomersChart = null;
let customerPercentagePieChart = null;
let paretoChart = null;
let customerFocusProductsChart = null;
let customerFocusStatusChart = null;
window.customersChartsLoaded = false;

// Load Customers Analytics Data
function loadCustomersAnalytics() {
    console.log('Loading customers analytics...');

    if (window.currentFocus && window.currentFocus.type === 'customer') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for customer view, delaying render...');
            setTimeout(loadCustomersAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderCustomerFocusView(window.focusSummary, window.focusDistributions);
            window.customersChartsLoaded = true;
            return;
        }
    } else {
        toggleCustomerFocusView(false);
    }

    const dates = getFilterDates();
    
    // Load Top Customers (existing)
    $.ajax({
        url: '/report/sales/analytics/top-customers',
        method: 'POST',
        data: { ...dates, limit: 10 },
        dataType: 'json',
        success: function(response) {
            console.log('Top customers response:', response);
            if (response.success && response.data) {
                toggleCustomerFocusView(false);
                renderTopCustomersChart(response.data);
                updateTopCustomersTable(response.data);
                calculateCustomerStatistics(response.data);
                calculateParetoAnalysis(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading top customers:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    // Load Customer Percentage Analysis (NEW)
    $.ajax({
        url: '/report/sales/analytics/customer-percentage',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Customer percentage response:', response);
            if (response.success && response.data) {
                updatePercentageSummary(response.summary);
                renderCustomerPercentagePieChart(response.data);
                updateCustomerPercentageTable(response.data);
                renderParetoChart(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading customer percentage:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.customersChartsLoaded = true;
}

function toggleCustomerFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#customer-general-view').hide();
        $('#customer-focus-view').fadeIn(150);
    } else {
        $('#customer-focus-view').hide();
        $('#customer-general-view').show();
    }
}

function renderCustomerFocusView(summaryData, distributionsData) {
    if (!summaryData || !distributionsData) {
        toggleCustomerFocusView(false);
        return;
    }

    toggleCustomerFocusView(true);

    const distributions = distributionsData.distributions || {};
    const productDistribution = distributions.products || [];
    const warehouseDistribution = distributions.warehouses || [];
    const statusDistribution = distributions.statuses || [];

    renderCustomerFocusProductsChart(productDistribution);
    renderCustomerFocusWarehouseList(warehouseDistribution);
    renderCustomerFocusStatusChart(statusDistribution);
    renderCustomerFocusStats(summaryData);

    const topProducts = summaryData.top_entities ? summaryData.top_entities.products || [] : [];
    $('#customer-focus-products-count').text(topProducts.length ? `${formatNumber(topProducts.length)} محصول` : '');
    renderCustomerFocusProductsTable(topProducts);
}

function renderCustomerFocusProductsChart(data) {
    const ctx = document.getElementById('customerFocusProductsChart');
    if (!ctx) return;

    if (customerFocusProductsChart) {
        customerFocusProductsChart.destroy();
        customerFocusProductsChart = null;
    }

    if (!data || data.length === 0) {
        $('#customerFocusProductsEmpty').show();
        return;
    }

    $('#customerFocusProductsEmpty').hide();

    const labels = data.map(item => item.label || '-');
    const amounts = data.map(item => item.total_amount || 0);

    customerFocusProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ خرید (ریال)',
                data: amounts,
                backgroundColor: 'rgba(89, 105, 255, 0.75)',
                borderColor: 'rgba(89, 105, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return formatCurrency(context.parsed.x);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y: {
                    ticks: {
                        font: { family: 'Vazir', size: 11 }
                    }
                }
            }
        }
    });
}

function renderCustomerFocusWarehouseList(data) {
    const $list = $('#customerFocusWarehousesList');
    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $('#customerFocusWarehousesEmpty').show();
        return;
    }

    $('#customerFocusWarehousesEmpty').hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = item.label || '-';
        const amount = formatCurrency(item.total_amount || 0);
        const orders = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';
        const unitLabel = getUnitLabel(item);
        const quantityValue = window.toFiniteNumber(item.total_quantity, 0);
        const quantity = quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '';
        const meta = [orders, quantity].filter(Boolean).join(' • ');

        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(li);
    });
}

function renderCustomerFocusStatusChart(data) {
    const ctx = document.getElementById('customerFocusStatusChart');
    if (!ctx) return;

    if (customerFocusStatusChart) {
        customerFocusStatusChart.destroy();
        customerFocusStatusChart = null;
    }

    if (!data || data.length === 0) {
        $('#customerFocusStatusEmpty').show();
        $('#customerFocusStatusLegend').empty();
        return;
    }

    $('#customerFocusStatusEmpty').hide();

    const labels = data.map(item => item.label || 'نامشخص');
    const counts = data.map(item => item.order_count || 0);
    const colors = [
        'rgba(255, 152, 0, 0.8)',
        'rgba(36, 210, 181, 0.8)',
        'rgba(89, 105, 255, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    customerFocusStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return `${context.label || ''}: ${formatNumber(context.parsed)} سفارش`;
                        }
                    }
                }
            }
        }
    });

    let legendHtml = '<div class="row">';
    data.forEach((item, index) => {
        const color = colors[index % colors.length];
        legendHtml += `
            <div class="col-md-6 mb-2 d-flex align-items-center">
                <span class="badge badge-pill mr-2" style="background:${color};">&nbsp;</span>
                <div>
                    <strong>${item.label || 'نامشخص'}</strong>
                    <div class="text-muted small">${formatNumber(item.order_count || 0)} سفارش</div>
                </div>
            </div>
        `;
    });
    legendHtml += '</div>';
    $('#customerFocusStatusLegend').html(legendHtml);
}

function renderCustomerFocusStats(summary) {
    const $list = $('#customerFocusStats');
    if (!$list.length || !summary) return;

    const totalAmount = window.toFiniteNumber(summary.total_amount, 0);
    const orderCount = window.toFiniteNumber(summary.order_count, 0);
    const hasQuantity = summary.total_quantity !== null && summary.total_quantity !== undefined;
    const totalQuantity = hasQuantity ? window.toFiniteNumber(summary.total_quantity, 0) : null;
    const uniqueProducts = window.toFiniteNumber(summary.unique_products, 0);
    const uniqueWarehouses = window.toFiniteNumber(summary.unique_warehouses, 0);
    const unitLabel = getUnitLabel(summary);

    const stats = [
        { label: 'کل خرید', value: formatCurrency(totalAmount) },
        { label: 'تعداد سفارش', value: formatNumber(orderCount) },
        { label: 'کل مقدار خرید (واحد ثبت‌شده)', value: hasQuantity && totalQuantity !== null ? formatQuantityValue(totalQuantity, unitLabel) : '-' },
        { label: 'محصولات یکتا', value: formatNumber(uniqueProducts) },
        { label: 'انبارهای فعال', value: formatNumber(uniqueWarehouses) }
    ];

    $list.empty();
    stats.forEach(stat => {
        $list.append(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${stat.label}</span>
                <span class="font-weight-bold">${stat.value}</span>
            </li>
        `);
    });
}

function renderCustomerFocusProductsTable(products) {
    const $tbody = $('#customerFocusProductsTable tbody');
    if (!$tbody.length) return;

    if (!products || products.length === 0) {
        $tbody.html(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="ti-alert"></i> محصولی برای این مشتری ثبت نشده است.
                </td>
            </tr>
        `);
        return;
    }

    $tbody.empty();
    products.forEach((product, index) => {
        const hasQuantity = product.total_quantity !== null && product.total_quantity !== undefined;
        const quantityValue = hasQuantity ? window.toFiniteNumber(product.total_quantity, 0) : 0;
        const unitLabel = getUnitLabel(product);
        const quantityDisplay = hasQuantity && quantityValue ? formatQuantityValue(quantityValue, unitLabel) : '-';
        const orderCount = window.toFiniteNumber(product.order_count, 0);
        const totalAmount = window.toFiniteNumber(product.total_amount, 0);
        $tbody.append(`
            <tr>
                <td><span class="badge badge-secondary">${index + 1}</span></td>
                <td>${product.label || '-'}</td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatCurrency(totalAmount)}</td>
                <td>${quantityDisplay}</td>
            </tr>
        `);
    });
}

// Render Top Customers Chart
function renderTopCustomersChart(data) {
    const ctx = document.getElementById('topCustomersChart');
    
    if (topCustomersChart) {
        topCustomersChart.destroy();
    }
    
    const labels = data.map(item => {
        const name = item.customer_name || 'نامشخص';
        return name.length > 25 ? name.substring(0, 25) + '...' : name;
    });
    const amounts = data.map(item => window.toFiniteNumber(item.total_amount, 0));
    const counts = data.map(item => window.toFiniteNumber(item.order_count, 0));
    
    topCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'مبلغ خرید (ریال)',
                    data: amounts,
                    backgroundColor: 'rgba(89, 105, 255, 0.7)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 2,
                    yAxisID: 'y'
                },
                {
                    label: 'تعداد سفارش',
                    data: counts,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += formatNumber(window.toFiniteNumber(context.parsed.y, 0));
                            if (context.datasetIndex === 0) {
                                label += ' ریال';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'مبلغ (ریال)',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'تعداد',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Update Top Customers Table
function updateTopCustomersTable(data) {
    const tbody = $('#topCustomersTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item, index) => {
        const badgeClass = index === 0 ? 'bg-warning' : (index === 1 ? 'bg-secondary' : (index === 2 ? 'bg-bronze' : 'bg-light text-dark'));
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const row = `
            <tr>
                <td><span class="badge ${badgeClass}">${index + 1}</span></td>
                <td><strong>${item.customer_name}</strong></td>
                <td>${formatNumber(orderCount)}</td>
                <td>${formatNumber(Math.round(totalAmount))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Customer Statistics
function calculateCustomerStatistics(data) {
    if (data.length === 0) return;
    
    // Total customers
    $('#totalCustomers').text(formatNumber(data.length));
    
    // Top customer
    if (data.length > 0) {
        const topCust = data[0].customer_name;
        $('#topCustomer').text(topCust.length > 30 ? topCust.substring(0, 30) + '...' : topCust);
    }
    
}

// Calculate Pareto Analysis (80/20 rule)
function calculateParetoAnalysis(data) {
    if (!Array.isArray(data) || data.length === 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده کافی برای تحلیل موجود نیست');
        return;
    }
    
    const totalSales = data.reduce((sum, item) => {
        return sum + window.toFiniteNumber(item.total_amount, 0);
    }, 0);

    if (totalSales <= 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده معتبر برای تحلیل پارتو یافت نشد');
        return;
    }

    const target80 = totalSales * 0.8;
    
    let cumulativeSum = 0;
    let count80 = 0;
    
    for (let i = 0; i < data.length; i++) {
        cumulativeSum += window.toFiniteNumber(data[i].total_amount, 0);
        count80++;
        if (cumulativeSum >= target80) {
            break;
        }
    }
    
    const percentage = window.toPercentageValue((count80 / data.length) * 100, 1);
    
    $('#paretoAnalysis').html(`
        <i class="ti-info-alt"></i> 
        <strong>${formatNumber(count80)}</strong> مشتری از ${formatNumber(data.length)} (${formatNumber(percentage)}%) مشتری برتر، 
        <strong>80%</strong> از کل فروش را تشکیل می‌دهند.
        <br>
        <small class="text-muted">این تحلیل به شما کمک می‌کند روی مشتریان کلیدی تمرکز کنید.</small>
    `);
}

// ============ NEW FUNCTIONS FOR PERCENTAGE ANALYSIS ============

// Update Percentage Summary Statistics
function updatePercentageSummary(summary) {
    if (!summary) return;
    
    const totalSales = window.toFiniteNumber(summary.total_sales, 0);
    const totalCustomers = window.toFiniteNumber(summary.total_customers, 0);
    const top10Percentage = window.clampPercentage(summary.top_10_percentage, 1);
    const top20Percentage = window.clampPercentage(summary.top_20_percentage, 1);
    const classACount = window.toFiniteNumber(summary.class_a_customers, 0);
    const classBCount = window.toFiniteNumber(summary.class_b_customers, 0);
    const classCCount = window.toFiniteNumber(summary.class_c_customers, 0);
    
    $('#percentage-total-sales').text(formatNumber(Math.round(totalSales)) + ' ریال');
    $('#percentage-total-customers').text(formatNumber(totalCustomers) + ' مشتری');
    $('#percentage-top10').text(formatPercentageValue(top10Percentage, 1) + '٪');
    $('#percentage-top20').text(formatPercentageValue(top20Percentage, 1) + '٪');
    
    $('#class-a-count').text(formatNumber(classACount));
    $('#class-b-count').text(formatNumber(classBCount));
    $('#class-c-count').text(formatNumber(classCCount));
}

// Render Customer Percentage Pie Chart
function renderCustomerPercentagePieChart(data) {
    const ctx = document.getElementById('customerPercentagePieChart');
    
    if (!ctx) {
        console.error('Canvas element not found: customerPercentagePieChart');
        return;
    }
    
    if (customerPercentagePieChart) {
        customerPercentagePieChart.destroy();
    }
    
    if (data.length === 0) {
        return;
    }
    
    // Take top 15 customers for pie chart
    const top15 = data.slice(0, 15);
    const labels = top15.map(item => item.customer_name || 'نامشخص');
    const percentages = top15.map(item => window.clampPercentage(item.percentage, 2));
    
    // Generate colors
    const colors = generatePieColors(top15.length);
    
    customerPercentagePieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'درصد فروش',
                data: percentages,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir',
                            size: 11
                        },
                        padding: 10,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const shortLabel = label.length > 20 ? label.substring(0, 20) + '...' : label;
                                    return {
                                        text: `${shortLabel} (${value}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = window.toFiniteNumber(context.parsed, 0);
                            const dataItem = top15[context.dataIndex];
                            return [
                                `${label}`,
                                `درصد: ${formatPercentageValue(value, 2)}٪`,
                                `مبلغ: ${formatNumber(Math.round(window.toFiniteNumber(dataItem.total_amount, 0)))} ریال`,
                                `تعداد: ${formatNumber(window.toFiniteNumber(dataItem.order_count, 0))} سفارش`
                            ];
                        }
                    }
                }
            }
        }
    });
}

// Update Customer Percentage Table
function updateCustomerPercentageTable(data) {
    const tbody = $('#customerPercentageTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item) => {
        // Classification badge
        let classBadge = '';
        if (item.classification === 'A') {
            classBadge = '<span class="badge badge-success">A</span>';
        } else if (item.classification === 'B') {
            classBadge = '<span class="badge badge-warning">B</span>';
        } else {
            classBadge = '<span class="badge badge-info">C</span>';
        }
        
        // Rank badge
        let rankBadge = `<span class="badge badge-light">${item.rank}</span>`;
        if (item.rank === 1) rankBadge = `<span class="badge badge-warning">🥇 ${item.rank}</span>`;
        else if (item.rank === 2) rankBadge = `<span class="badge badge-secondary">🥈 ${item.rank}</span>`;
        else if (item.rank === 3) rankBadge = `<span class="badge bg-bronze text-white">🥉 ${item.rank}</span>`;
        
        const orderCount = window.toFiniteNumber(item.order_count, 0);
        const totalAmount = window.toFiniteNumber(item.total_amount, 0);
        const percentageValue = window.clampPercentage(item.percentage, 2);
        const percentageDisplay = formatPercentageValue(percentageValue, 2);
        const cumulativeValue = window.clampPercentage(item.cumulative_percentage, 2);
        const cumulativeDisplay = formatPercentageValue(cumulativeValue, 2);
        const progressColor = item.classification === 'A' ? 'success' : (item.classification === 'B' ? 'warning' : 'info');
        const progressWidth = percentageValue;
        const row = `
            <tr>
                <td>${rankBadge}</td>
                <td>
                    <strong>${item.customer_name || 'نامشخص'}</strong>
                    <br><small class="text-muted">${formatNumber(orderCount)} سفارش</small>
                </td>
                <td>${formatNumber(Math.round(totalAmount))}</td>
                <td>
                    <div class="customer-percentage-progress">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 14px;">
                                <div class="progress-bar bg-${progressColor}" role="progressbar" 
                                     style="width: ${progressWidth}%;" 
                                     aria-valuenow="${percentageValue}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-light progress-value">${percentageDisplay}٪</span>
                    </div>
                </td>
                <td>
                    <strong class="${cumulativeValue <= 80 ? 'text-success' : ''}">${cumulativeDisplay}٪</strong>
                </td>
                <td>${classBadge}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Render Pareto Chart (Cumulative Line Chart)
function renderParetoChart(data) {
    const ctx = document.getElementById('paretoChart');
    
    if (!ctx) {
        console.error('Canvas element not found: paretoChart');
        return;
    }
    
    if (paretoChart) {
        paretoChart.destroy();
    }
    
    if (data.length === 0) {
        return;
    }
    
    const labels = data.map(item => {
        const name = item.customer_name || 'نامشخص';
        const shortName = name.length > 15 ? name.substring(0, 15) + '...' : name;
        return `${item.rank}. ${shortName}`;
    });
    const percentages = data.map(item => window.clampPercentage(item.percentage, 2));
    const cumulativePercentages = data.map(item => window.clampPercentage(item.cumulative_percentage, 2));
    
    paretoChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'درصد فروش',
                    data: percentages,
                    backgroundColor: 'rgba(89, 105, 255, 0.6)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'درصد تجمعی',
                    data: cumulativePercentages,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    yAxisID: 'y',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const dataItem = data[context.dataIndex];
                            if (context.dataset.type === 'bar') {
                                const safeValue = window.toFiniteNumber(context.parsed.y, 0);
                                return `درصد: ${formatPercentageValue(safeValue, 2)}٪`;
                            } else {
                                const safeValue = window.toFiniteNumber(context.parsed.y, 0);
                                return `تجمعی: ${formatPercentageValue(safeValue, 2)}٪`;
                            }
                        }
                    }
                },
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: 80,
                            yMax: 80,
                            borderColor: 'rgba(255, 0, 0, 0.5)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                content: '80% (قاعده پارتو)',
                                enabled: true,
                                position: 'end'
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'درصد',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 9
                        },
                        maxRotation: 90,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Generate colors for pie chart
function generatePieColors(count) {
    const baseColors = [
        'rgba(89, 105, 255, 0.8)',   // Blue
        'rgba(255, 159, 64, 0.8)',   // Orange
        'rgba(75, 192, 192, 0.8)',   // Teal
        'rgba(255, 99, 132, 0.8)',   // Red
        'rgba(153, 102, 255, 0.8)',  // Purple
        'rgba(255, 205, 86, 0.8)',   // Yellow
        'rgba(54, 162, 235, 0.8)',   // Light Blue
        'rgba(231, 76, 60, 0.8)',    // Red-Orange
        'rgba(46, 204, 113, 0.8)',   // Green
        'rgba(155, 89, 182, 0.8)',   // Purple
        'rgba(52, 152, 219, 0.8)',   // Blue
        'rgba(241, 196, 15, 0.8)',   // Gold
        'rgba(230, 126, 34, 0.8)',   // Orange
        'rgba(26, 188, 156, 0.8)',   // Turquoise
        'rgba(142, 68, 173, 0.8)'    // Dark Purple
    ];
    
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(baseColors[i % baseColors.length]);
    }
    return colors;
}
</script>

<style>
.customer-percentage-progress {
    display: flex;
    align-items: center;
}

.customer-percentage-progress .progress {
    flex-grow: 1;
    margin-bottom: 0;
    background-color: #f1f3f5;
}

.customer-percentage-progress .progress-value {
    margin-right: 0.75rem;
    margin-left: 0.75rem;
    font-weight: 600;
    color: #343a40;
    min-width: 64px;
    text-align: center;
}

.bg-bronze {
    background-color: #CD7F32;
    color: white;
}
</style>

