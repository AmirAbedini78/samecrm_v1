<!-- Products Analytics Tab Content -->

<div id="product-focus-view" style="display: none;">
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="ti-light-bulb mr-2"></i>
        <div>
            <strong>نمای اختصاصی محصول فعال است.</strong>
            <div class="small text-muted">برای بازگشت به گزارش عمومی، فیلتر محصول را پاک کنید یا فیلتر دیگری را انتخاب نمایید.</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center mb-3">
                        <span><i class="ti-user"></i> مشتریان برتر این محصول</span>
                        <small class="text-muted" id="product-focus-customers-count"></small>
                    </h5>
                    <div class="chart-container" style="height: 360px;">
                        <canvas id="productFocusCustomersChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="productFocusCustomersEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> داده‌ای برای نمایش مشتریان برتر یافت نشد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-home"></i> توزیع فروش بین انبارها
                    </h5>
                    <ul class="list-group list-group-sm focus-warehouse-list" id="productFocusWarehousesList"></ul>
                    <div class="alert alert-info focus-empty-state mt-3" id="productFocusWarehousesEmpty" style="display: none;">
                        <i class="ti-info-alt"></i> این محصول در انباری ثبت نشده است.
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
                        <i class="ti-pie-chart"></i> وضعیت سفارش‌های این محصول
                    </h5>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="productFocusStatusChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="productFocusStatusEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> هیچ سفارشی برای نمایش وضعیت وجود ندارد.
                        </div>
                    </div>
                    <div class="mt-3" id="productFocusStatusLegend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-bar-chart"></i> شاخص‌های کلیدی محصول
                    </h5>
                    <ul class="list-group list-group-sm" id="productFocusStats"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="ti-list"></i> مشتریان برتر (جزئیات کامل)
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="productFocusCustomersTable">
                    <thead>
                        <tr>
                            <th width="60">رتبه</th>
                            <th>مشتری</th>
                            <th width="120">تعداد سفارش</th>
                            <th width="160">مبلغ کل</th>
                            <th width="120">مقدار کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> در حال آماده‌سازی داده‌های مشتریان...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="product-general-view" class="row">
    <!-- Top 10 Products Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-medall"></i> Top 10 محصولات پرفروش
                </h5>
                <p class="text-muted">محصولاتی که بیشترین فروش را داشته‌اند</p>
                <div class="chart-container">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Statistics -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات پرفروش (جدول)</h5>
                <p class="text-muted">جزئیات فروش محصولات</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="topProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>رتبه</th>
                                <th>محصول</th>
                                <th>تعداد</th>
                                <th>مقدار</th>
                                <th>مبلغ کل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Categories Analysis -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-layout-grid2"></i> سهم محصولات از کل فروش
                </h5>
                <p class="text-muted">توزیع فروش بین محصولات</p>
                <div class="chart-container">
                    <canvas id="productsDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Performance Cards -->
    <div class="col-12">
        <div class="row" id="productStatsCards">
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">تعداد محصولات</p>
                                <h4 class="mb-0" id="totalProducts">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-package" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">محصول برتر</p>
                                <h6 class="mb-0" id="topProduct" style="font-size: 14px;">-</h6>
                            </div>
                            <div class="text-warning">
                                <i class="ti-crown" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع مقدار</p>
                                <h4 class="mb-0" id="totalQuantity">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let topProductsChart = null;
let productsDistributionChart = null;
let productFocusCustomersChart = null;
let productFocusStatusChart = null;
window.productsChartsLoaded = false;

// Load Products Analytics Data
function loadProductsAnalytics() {
    console.log('Loading products analytics...');

    // Handle focus mode (single product selected)
    if (window.currentFocus && window.currentFocus.type === 'product') {
        if (focusDataLoading) {
            console.log('Focus data is still loading, waiting before rendering product focus view...');
            setTimeout(loadProductsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderProductFocusView(window.focusSummary, window.focusDistributions);
            window.productsChartsLoaded = true;
            return;
        }
    } else {
        toggleProductFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/top-products',
        method: 'POST',
        data: { ...dates, limit: 10 },
        dataType: 'json',
        success: function(response) {
            console.log('Top products response:', response);
            if (response.success && response.data) {
                toggleProductFocusView(false);
                renderTopProductsChart(response.data);
                renderProductsDistributionChart(response.data);
                updateTopProductsTable(response.data);
                calculateProductStatistics(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading top products:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.productsChartsLoaded = true;
}

function toggleProductFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#product-general-view').hide();
        $('#product-focus-view').fadeIn(150);
    } else {
        $('#product-focus-view').hide();
        $('#product-general-view').show();
    }
}

function renderProductFocusView(summaryData, distributionsData) {
    if (!summaryData || !distributionsData) {
        toggleProductFocusView(false);
        return;
    }

    toggleProductFocusView(true);

    const distributions = distributionsData.distributions || {};
    const customerDistribution = distributions.customers || [];
    const warehouseDistribution = distributions.warehouses || [];
    const statusDistribution = distributions.statuses || [];

    renderProductFocusCustomersChart(customerDistribution);
    renderProductFocusWarehouseList(warehouseDistribution);
    renderProductFocusStatusChart(statusDistribution);
    renderProductFocusStats(summaryData);

    const topCustomers = summaryData.top_entities ? summaryData.top_entities.customers || [] : [];
    $('#product-focus-customers-count').text(topCustomers.length ? `${formatNumber(topCustomers.length)} مشتری` : '');
    renderProductFocusCustomersTable(topCustomers);
}

function renderProductFocusCustomersChart(data) {
    const ctx = document.getElementById('productFocusCustomersChart');
    if (!ctx) return;

    if (productFocusCustomersChart) {
        productFocusCustomersChart.destroy();
        productFocusCustomersChart = null;
    }

    if (!data || data.length === 0) {
        $('#productFocusCustomersEmpty').show();
        return;
    }

    $('#productFocusCustomersEmpty').hide();

    const labels = data.map(item => item.label || '-');
    const amounts = data.map(item => item.total_amount || 0);

    productFocusCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ فروش (ریال)',
                data: amounts,
                backgroundColor: 'rgba(255, 152, 0, 0.7)',
                borderColor: 'rgba(255, 152, 0, 1)',
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

function renderProductFocusWarehouseList(data) {
    const $list = $('#productFocusWarehousesList');
    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $('#productFocusWarehousesEmpty').show();
        return;
    }

    $('#productFocusWarehousesEmpty').hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = item.label || '-';
        const amount = formatCurrency(item.total_amount || 0);
        const orders = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';
        const quantity = item.total_quantity ? formatNumber(Math.round(item.total_quantity)) + ' واحد' : '';
        const meta = [orders, quantity].filter(Boolean).join(' • ');

        const listItem = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${label}</strong>
                    ${meta ? `<div class="text-muted small">${meta}</div>` : ''}
                </div>
                <span class="text-muted">${amount}</span>
            </li>
        `;
        $list.append(listItem);
    });
}

function renderProductFocusStatusChart(data) {
    const ctx = document.getElementById('productFocusStatusChart');
    if (!ctx) return;

    if (productFocusStatusChart) {
        productFocusStatusChart.destroy();
        productFocusStatusChart = null;
    }

    if (!data || data.length === 0) {
        $('#productFocusStatusEmpty').show();
        $('#productFocusStatusLegend').empty();
        return;
    }

    $('#productFocusStatusEmpty').hide();

    const labels = data.map(item => item.label || 'نامشخص');
    const counts = data.map(item => item.order_count || 0);
    const bgColors = [
        'rgba(89, 105, 255, 0.8)',
        'rgba(255, 152, 0, 0.8)',
        'rgba(36, 210, 181, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    productFocusStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: bgColors.slice(0, labels.length),
                borderColor: bgColors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
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
                            const label = context.label || '';
                            const value = formatNumber(context.parsed);
                            return `${label}: ${value} سفارش`;
                        }
                    }
                }
            }
        }
    });

    let legendHtml = '<div class="row">';
    data.forEach((item, index) => {
        const color = bgColors[index % bgColors.length];
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
    $('#productFocusStatusLegend').html(legendHtml);
}

function renderProductFocusStats(summary) {
    const $list = $('#productFocusStats');
    if (!$list.length || !summary) return;

    const stats = [
        { label: 'کل فروش', value: formatCurrency(summary.total_amount) },
        { label: 'تعداد سفارش', value: formatNumber(summary.order_count || 0) },
        { label: 'کل مقدار فروش', value: formatNumber(Math.round(summary.total_quantity || 0)) + ' واحد' },
        { label: 'مشتریان یکتا', value: formatNumber(summary.unique_customers || 0) },
        { label: 'انبارهای فعال', value: formatNumber(summary.unique_warehouses || 0) }
    ];

    $list.empty();
    stats.forEach(stat => {
        const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${stat.label}</span>
                <span class="font-weight-bold">${stat.value}</span>
            </li>
        `;
        $list.append(li);
    });
}

function renderProductFocusCustomersTable(customers) {
    const $tbody = $('#productFocusCustomersTable tbody');
    if (!$tbody.length) return;

    if (!customers || customers.length === 0) {
        $tbody.html(`
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="ti-alert"></i> اطلاعاتی برای نمایش مشتریان برتر وجود ندارد.
                </td>
            </tr>
        `);
        return;
    }

    $tbody.empty();
    customers.forEach((customer, index) => {
        const quantity = customer.total_quantity ? formatNumber(Math.round(customer.total_quantity)) : '-';
        const row = `
            <tr>
                <td><span class="badge badge-secondary">${index + 1}</span></td>
                <td>${customer.label || '-'}</td>
                <td>${formatNumber(customer.order_count || 0)}</td>
                <td>${formatCurrency(customer.total_amount || 0)}</td>
                <td>${quantity}</td>
            </tr>
        `;
        $tbody.append(row);
    });
}

// Render Top Products Chart (Horizontal Bar)
function renderTopProductsChart(data) {
    const ctx = document.getElementById('topProductsChart');
    
    if (topProductsChart) {
        topProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 30 ? item.product_name.substring(0, 30) + '...' : item.product_name);
    const amounts = data.map(item => item.total_amount);
    
    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ فروش (ریال)',
                data: amounts,
                backgroundColor: 'rgba(89, 105, 255, 0.7)',
                borderColor: 'rgba(89, 105, 255, 1)',
                borderWidth: 2
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return 'مبلغ: ' + formatNumber(context.parsed.x) + ' ریال';
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
                        font: {
                            family: 'Vazir',
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

// Render Products Distribution Chart (Pie)
function renderProductsDistributionChart(data) {
    const ctx = document.getElementById('productsDistributionChart');
    
    if (productsDistributionChart) {
        productsDistributionChart.destroy();
    }
    
    const labels = data.slice(0, 5).map(item => item.product_name.length > 20 ? item.product_name.substring(0, 20) + '...' : item.product_name);
    const amounts = data.slice(0, 5).map(item => item.total_amount);
    
    // Add "سایر" category if more than 5 products
    if (data.length > 5) {
        const otherTotal = data.slice(5).reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
        labels.push('سایر محصولات');
        amounts.push(otherTotal);
    }
    
    const colors = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(201, 203, 207, 0.8)'
    ];
    
    productsDistributionChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: amounts,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length).map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Vazir',
                            size: 11
                        },
                        padding: 10
                    }
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = formatNumber(context.parsed);
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${label}: ${value} ریال (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Update Top Products Table
function updateTopProductsTable(data) {
    const tbody = $('#topProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="5" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item, index) => {
        const row = `
            <tr>
                <td><span class="badge bg-${index < 3 ? 'warning' : 'secondary'}">${index + 1}</span></td>
                <td><strong>${item.product_name}</strong></td>
                <td>${formatNumber(item.sales_count)}</td>
                <td>${formatNumber(item.total_quantity)}</td>
                <td>${formatNumber(Math.round(item.total_amount))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Product Statistics
function calculateProductStatistics(data) {
    if (data.length === 0) return;
    
    // Total unique products
    $('#totalProducts').text(formatNumber(data.length));
    
    // Top product
    if (data.length > 0) {
        $('#topProduct').text(data[0].product_name.length > 20 ? data[0].product_name.substring(0, 20) + '...' : data[0].product_name);
    }
    
    // Total quantity
    const totalQty = data.reduce((sum, item) => sum + parseFloat(item.total_quantity), 0);
    $('#totalQuantity').text(formatNumber(Math.round(totalQty)));
}
</script>

