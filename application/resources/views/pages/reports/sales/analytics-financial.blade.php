<!-- Financial Analytics Tab Content -->

<div id="financial-focus-view" style="display: none;">
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="ti-light-bulb mr-2"></i>
        <div>
            <strong id="financial-focus-heading">نمای اختصاصی مالی فعال است.</strong>
            <div class="small text-muted">برای بازگشت به گزارش عمومی، فیلتر فعلی را پاک کنید یا تمرکز را تغییر دهید.</div>
        </div>
    </div>

    <div class="row mb-3" id="financial-focus-stats-row">
        <!-- Dynamic stats cards will be injected via JavaScript -->
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3" id="financial-focus-primary-title">
                        <i class="ti-bar-chart"></i> تحلیل تمرکز
                    </h5>
                    <div class="chart-container" style="height: 340px;">
                        <canvas id="financialFocusPrimaryChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="financialFocusPrimaryEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> داده‌ای برای نمایش نمودار تمرکز یافت نشد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-home"></i> توزیع فروش بین انبارها
                    </h5>
                    <ul class="list-group list-group-sm focus-warehouse-list" id="financialFocusWarehousesList"></ul>
                    <div class="alert alert-info focus-empty-state mt-3" id="financialFocusWarehousesEmpty" style="display: none;">
                        <i class="ti-info-alt"></i> داده‌ای از انبارها در دسترس نیست.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ti-pie-chart"></i> وضعیت سفارش‌ها
                    </h5>
                    <div class="chart-container" style="height: 260px;">
                        <canvas id="financialFocusStatusChart"></canvas>
                        <div class="alert alert-info focus-empty-state mt-3" id="financialFocusStatusEmpty" style="display: none;">
                            <i class="ti-info-alt"></i> وضعیت سفارشی برای نمایش وجود ندارد.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3" id="financial-focus-detail-title">
                <i class="ti-list"></i> جزئیات تمرکز مالی
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover" id="financialFocusDetailTable">
                    <thead id="financialFocusDetailHead"></thead>
                    <tbody id="financialFocusDetailBody">
                        <tr>
                            <td class="text-center text-muted">
                                <i class="spinner-border spinner-border-sm"></i> در حال آماده‌سازی داده‌های تمرکز...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="financial-general-view" class="row">
    <!-- Profit Analysis Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-money"></i> تحلیل سودآوری محصولات
                </h5>
                <p class="text-muted">سود ناخالص به تفکیک محصولات (Top 15)</p>
                <div class="chart-container">
                    <canvas id="profitAnalysisChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Statistics Cards -->
    <div class="col-md-4 mb-4">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">سود ناخالص کل</p>
                        <h4 class="mb-0 text-success" id="totalProfit">-</h4>
                    </div>
                    <div class="text-success">
                        <i class="ti-stats-up" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">مبلغ فروش کل</p>
                        <h4 class="mb-0 text-primary" id="totalRevenue">-</h4>
                    </div>
                    <div class="text-primary">
                        <i class="ti-wallet" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">حاشیه سود</p>
                        <h4 class="mb-0 text-warning" id="profitMargin">-</h4>
                    </div>
                    <div class="text-warning">
                        <i class="ti-pie-chart" style="font-size: 32px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Profit Table -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول سودآوری محصولات
                </h5>
                <p class="text-muted">جزئیات سود به تفکیک محصول</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="profitTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>تعداد</th>
                                <th>فروش</th>
                                <th>خالص</th>
                                <th>سود</th>
                                <th>حاشیه</th>
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

    <!-- Most Profitable Product -->
    <div class="col-md-4 mb-4">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h5 class="card-title text-white">
                    <i class="ti-crown"></i> سودآورترین محصول
                </h5>
                <div class="mt-4">
                    <h6 id="mostProfitableProduct" style="min-height: 40px;">-</h6>
                    <hr class="bg-white">
                    <div class="d-flex justify-content-between">
                        <span>سود:</span>
                        <strong id="mostProfitableAmount">-</strong>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span>حاشیه سود:</span>
                        <strong id="mostProfitableMargin">-</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let profitAnalysisChart = null;
let financialFocusPrimaryChart = null;
let financialFocusStatusChart = null;
window.financialChartsLoaded = false;

// Load Financial Analytics Data
function loadFinancialAnalytics() {
    console.log('Loading financial analytics...');

    const focus = window.currentFocus;
    if (focus && (focus.type === 'product' || focus.type === 'customer')) {
        if (focusDataLoading) {
            console.log('Focus data is still loading for financial view, delaying render...');
            setTimeout(loadFinancialAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderFinancialFocusView(window.focusSummary, window.focusDistributions);
            window.financialChartsLoaded = true;
            return;
        }
    } else {
        toggleFinancialFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/profit-analysis',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Profit analysis response:', response);
            if (response.success && response.data) {
                renderProfitAnalysisChart(response.data);
                updateProfitTable(response.data);
                calculateFinancialStatistics(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading profit analysis:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.financialChartsLoaded = true;
}

function toggleFinancialFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#financial-general-view').hide();
        $('#financial-focus-view').fadeIn(150);
    } else {
        $('#financial-focus-view').hide();
        $('#financial-general-view').show();
    }
}

function renderFinancialFocusView(summary, distributionsData) {
    if (!summary || !distributionsData) {
        toggleFinancialFocusView(false);
        return;
    }

    toggleFinancialFocusView(true);

    const focusType = summary.focus;
    const focusLabel = truncateLabel(summary.label || '-', 60);
    const distributions = distributionsData.distributions || {};

    $('#financial-focus-heading').text(`نمای اختصاصی مالی برای ${getFocusTypeLabel(focusType)}: ${focusLabel}`);

    renderFinancialFocusStats(summary);

    if (focusType === 'product') {
        $('#financial-focus-primary-title').html('<i class="ti-user"></i> مشتریان کلیدی این محصول');
        $('#financial-focus-detail-title').html('<i class="ti-list"></i> مشتریان برتر این محصول');
        renderFinancialFocusPrimaryChart(distributions.customers || []);
        renderFinancialFocusDetailTable(summary.top_entities ? summary.top_entities.customers || [] : [], 'customer');
    } else {
        $('#financial-focus-primary-title').html('<i class="ti-package"></i> محصولات خریداری شده توسط این مشتری');
        $('#financial-focus-detail-title').html('<i class="ti-list"></i> محصولات برتر این مشتری');
        renderFinancialFocusPrimaryChart(distributions.products || []);
        renderFinancialFocusDetailTable(summary.top_entities ? summary.top_entities.products || [] : [], 'product');
    }

    renderFinancialFocusWarehousesList(distributions.warehouses || []);
    renderFinancialFocusStatusChart(distributions.statuses || []);
}

function renderFinancialFocusStats(summary) {
    const $row = $('#financial-focus-stats-row');
    if (!$row.length) return;

    const totalSales = summary.total_amount || 0;
    const totalNet = summary.total_net_amount || 0;
    const profit = totalNet - totalSales;
    const margin = totalSales > 0 ? ((profit / totalSales) * 100).toFixed(1) : 0;
    const stats = [
        { label: 'مبلغ فروش', value: formatCurrency(totalSales), icon: 'ti-wallet', theme: 'primary' },
        { label: 'مبلغ خالص', value: formatCurrency(totalNet), icon: 'ti-stats-up', theme: 'success' },
        { label: 'سود تخمینی', value: formatCurrency(profit), icon: 'ti-money', theme: profit >= 0 ? 'success' : 'danger' },
        { label: 'تعداد سفارش', value: formatNumber(summary.order_count || 0), icon: 'ti-layers', theme: 'warning' },
        { label: 'مقدار کل', value: formatNumber(Math.round(summary.total_quantity || 0)) + ' واحد', icon: 'ti-truck', theme: 'secondary' },
        { label: 'حاشیه سود', value: margin + '%', icon: 'ti-bar-chart', theme: 'warning' }
    ];

    $row.empty();
    stats.forEach(stat => {
        const card = `
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card focus-meta-card h-100 border-${stat.theme}">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="focus-meta-label mb-1">${stat.label}</p>
                            <h5 class="mb-0">${stat.value}</h5>
                        </div>
                        <div class="text-${stat.theme}">
                            <i class="${stat.icon}" style="font-size: 28px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $row.append(card);
    });
}

function renderFinancialFocusPrimaryChart(data) {
    const ctx = document.getElementById('financialFocusPrimaryChart');
    if (!ctx) return;

    if (financialFocusPrimaryChart) {
        financialFocusPrimaryChart.destroy();
        financialFocusPrimaryChart = null;
    }

    if (!data || data.length === 0) {
        $('#financialFocusPrimaryEmpty').show();
        return;
    }

    $('#financialFocusPrimaryEmpty').hide();

    const labels = data.map(item => item.label || '-');
    const amounts = data.map(item => item.total_amount || 0);

    financialFocusPrimaryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مبلغ فروش (ریال)',
                data: amounts,
                backgroundColor: 'rgba(89, 105, 255, 0.65)',
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
                        callback: function(value) { return formatNumber(value); }
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

function renderFinancialFocusWarehousesList(data) {
    const $list = $('#financialFocusWarehousesList');
    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $('#financialFocusWarehousesEmpty').show();
        return;
    }

    $('#financialFocusWarehousesEmpty').hide();
    $list.empty();

    data.forEach((item, index) => {
        const listItem = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${index + 1}. ${item.label || '-'}</strong>
                    <div class="text-muted small">${formatNumber(item.order_count || 0)} سفارش</div>
                </div>
                <span class="text-muted">${formatCurrency(item.total_amount || 0)}</span>
            </li>
        `;
        $list.append(listItem);
    });
}

function renderFinancialFocusStatusChart(data) {
    const ctx = document.getElementById('financialFocusStatusChart');
    if (!ctx) return;

    if (financialFocusStatusChart) {
        financialFocusStatusChart.destroy();
        financialFocusStatusChart = null;
    }

    if (!data || data.length === 0) {
        $('#financialFocusStatusEmpty').show();
        return;
    }

    $('#financialFocusStatusEmpty').hide();

    const labels = data.map(item => item.label || 'نامشخص');
    const counts = data.map(item => item.order_count || 0);
    const colors = [
        'rgba(36, 210, 181, 0.8)',
        'rgba(255, 159, 64, 0.8)',
        'rgba(89, 105, 255, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    financialFocusStatusChart = new Chart(ctx, {
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
}

function renderFinancialFocusDetailTable(records, type) {
    const $thead = $('#financialFocusDetailHead');
    const $tbody = $('#financialFocusDetailBody');
    if (!$thead.length || !$tbody.length) return;

    if (!records || records.length === 0) {
        $thead.html('<tr><th>اطلاعاتی یافت نشد</th></tr>');
        $tbody.html(`
            <tr>
                <td class="text-center text-muted">
                    <i class="ti-alert"></i> داده‌ای برای نمایش وجود ندارد.
                </td>
            </tr>
        `);
        return;
    }

    if (type === 'customer') {
        $thead.html(`
            <tr>
                <th width="60">رتبه</th>
                <th>مشتری</th>
                <th width="120">تعداد سفارش</th>
                <th width="160">مبلغ کل</th>
                <th width="140">مقدار کل</th>
            </tr>
        `);
    } else {
        $thead.html(`
            <tr>
                <th width="60">رتبه</th>
                <th>محصول</th>
                <th width="120">تعداد سفارش</th>
                <th width="160">مبلغ کل</th>
                <th width="140">مقدار کل</th>
            </tr>
        `);
    }

    $tbody.empty();
    records.forEach((record, index) => {
        const quantity = record.total_quantity ? formatNumber(Math.round(record.total_quantity)) : '-';
        const row = `
            <tr>
                <td><span class="badge badge-secondary">${index + 1}</span></td>
                <td>${truncateLabel(record.label || '-', 55)}</td>
                <td>${formatNumber(record.order_count || 0)}</td>
                <td>${formatCurrency(record.total_amount || 0)}</td>
                <td>${quantity}</td>
            </tr>
        `;
        $tbody.append(row);
    });
}

// Render Profit Analysis Chart
function renderProfitAnalysisChart(data) {
    const ctx = document.getElementById('profitAnalysisChart');
    
    if (profitAnalysisChart) {
        profitAnalysisChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const profits = data.map(item => item.profit);
    const revenues = data.map(item => item.sales_amount);
    
    profitAnalysisChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'مبلغ فروش',
                    data: revenues,
                    backgroundColor: 'rgba(89, 105, 255, 0.5)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    borderWidth: 2
                },
                {
                    label: 'سود',
                    data: profits,
                    backgroundColor: 'rgba(36, 210, 181, 0.7)',
                    borderColor: 'rgba(36, 210, 181, 1)',
                    borderWidth: 2
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
                            label += formatNumber(context.parsed.y) + ' ریال';
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazir',
                            size: 9
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

// Update Profit Table
function updateProfitTable(data) {
    const tbody = $('#profitTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const profitMargin = item.sales_amount > 0 ? ((item.profit / item.sales_amount) * 100).toFixed(1) : 0;
        const profitClass = item.profit > 0 ? 'text-success' : 'text-danger';
        
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 30 ? item.product_name.substring(0, 30) + '...' : item.product_name}</strong></td>
                <td>${formatNumber(item.count)}</td>
                <td>${formatNumber(Math.round(item.sales_amount))}</td>
                <td>${formatNumber(Math.round(item.net_amount))}</td>
                <td class="${profitClass}"><strong>${formatNumber(Math.round(item.profit))}</strong></td>
                <td><span class="badge bg-secondary">${profitMargin}%</span></td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Financial Statistics
function calculateFinancialStatistics(data) {
    if (data.length === 0) return;
    
    // Total profit
    const totalProfit = data.reduce((sum, item) => sum + parseFloat(item.profit), 0);
    $('#totalProfit').text(formatNumber(Math.round(totalProfit)) + ' ریال');
    
    // Total revenue
    const totalRevenue = data.reduce((sum, item) => sum + parseFloat(item.sales_amount), 0);
    $('#totalRevenue').text(formatNumber(Math.round(totalRevenue)) + ' ریال');
    
    // Profit margin
    const profitMargin = totalRevenue > 0 ? ((totalProfit / totalRevenue) * 100).toFixed(1) : 0;
    $('#profitMargin').text(profitMargin + '%');
    
    // Most profitable product
    if (data.length > 0) {
        const mostProfitable = data[0];
        $('#mostProfitableProduct').text(mostProfitable.product_name);
        $('#mostProfitableAmount').text(formatNumber(Math.round(mostProfitable.profit)) + ' ریال');
        const margin = mostProfitable.sales_amount > 0 ? ((mostProfitable.profit / mostProfitable.sales_amount) * 100).toFixed(1) : 0;
        $('#mostProfitableMargin').text(margin + '%');
    }
}
</script>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #24d2b5 0%, #1ea896 100%);
}
.border-success {
    border-right: 4px solid #24d2b5 !important;
}
.border-primary {
    border-right: 4px solid #5969ff !important;
}
.border-warning {
    border-right: 4px solid #ffb64d !important;
}
.border-info {
    border-right: 4px solid #36bffa !important;
}
</style>

