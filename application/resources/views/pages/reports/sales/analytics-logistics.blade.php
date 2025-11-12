<!-- Logistics Analytics Tab Content -->
<div id="logistics-focus-banner" class="alert alert-warning mb-3" style="display: none;">
    <i class="ti-target mr-2"></i>
    <span id="logistics-focus-text"></span>
</div>

<div class="row" id="logistics-focus-highlights" style="display: none;">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-package"></i> محصولات شاخص این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusProductsList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusProductsEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> محصول شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti-user"></i> مشتریان کلیدی این انبار
                </h5>
                <ul class="list-group list-group-sm focus-warehouse-list" id="logisticsFocusCustomersList"></ul>
                <div class="alert alert-info focus-empty-state mt-3" id="logisticsFocusCustomersEmpty" style="display: none;">
                    <i class="ti-info-alt"></i> مشتری شاخصی برای این انبار یافت نشد.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logistics-general-view" class="row">
    <!-- Delivery Rate Gauge -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-truck"></i> نرخ تحویل
                </h5>
                <p class="text-muted">درصد کالاهای تحویل شده از کل سفارشات</p>
                <div class="chart-container">
                    <canvas id="deliveryRateChart"></canvas>
                </div>
                <div class="text-center mt-3">
                    <h2 class="mb-0 text-success" id="deliveryRatePercent">-</h2>
                    <p class="text-muted">نرخ تحویل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Statistics Cards -->
    <div class="col-md-6 mb-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card stat-card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار تحویل شده</p>
                                <h4 class="mb-0 text-success" id="deliveredQty">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-check-box" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مقدار در انتظار تحویل</p>
                                <h4 class="mb-0 text-warning" id="pendingQty">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-time" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stat-card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">مجموع سفارشات</p>
                                <h4 class="mb-0 text-primary" id="totalOrders">-</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ti-shopping-cart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Chart -->
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-package"></i> محصولات با تحویل معلق (Top 10)
                </h5>
                <p class="text-muted">محصولاتی که بیشترین مقدار در انتظار تحویل دارند</p>
                <div class="chart-container">
                    <canvas id="pendingProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Products Table -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> جدول محصولات معلق
                </h5>
                <p class="text-muted">جزئیات محصولات در انتظار تحویل</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm" id="pendingProductsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>محصول</th>
                                <th>مقدار معلق</th>
                                <th>تعداد سفارش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="spinner-border spinner-border-sm"></i> در حال بارگذاری...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Performance Alert -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-info-alt"></i> ارزیابی عملکرد تحویل
                </h5>
                <div id="deliveryPerformanceAlert" class="alert" role="alert">
                    <i class="spinner-border spinner-border-sm"></i> در حال تحلیل...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deliveryRateChart = null;
let pendingProductsChart = null;
window.logisticsChartsLoaded = false;

// Load Logistics Analytics Data
function loadLogisticsAnalytics() {
    console.log('Loading logistics analytics...');

    const focus = window.currentFocus;
    if (focus && focus.type === 'warehouse') {
        if (focusDataLoading) {
            console.log('Focus data is still loading for logistics view, delaying render...');
            setTimeout(loadLogisticsAnalytics, 350);
            return;
        }

        if (window.focusSummary && window.focusDistributions) {
            renderLogisticsFocusHighlights(window.focusSummary, window.focusDistributions);
        }
    } else {
        toggleLogisticsFocusView(false);
    }

    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/delivery-status',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Delivery status response:', response);
            if (response.success && response.data) {
                renderDeliveryRateChart(response.data.delivery_rate);
                renderPendingProductsChart(response.data.pending_products);
                updatePendingProductsTable(response.data.pending_products);
                updateDeliveryStatistics(response.data.stats, response.data.delivery_rate);
                evaluateDeliveryPerformance(response.data.delivery_rate, response.data.stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading delivery status:', status, error);
            console.error('Response:', xhr.responseText);
        }
    });
    
    window.logisticsChartsLoaded = true;
}

function toggleLogisticsFocusView(isFocusMode) {
    if (isFocusMode) {
        $('#logistics-focus-banner').fadeIn(150);
        $('#logistics-focus-highlights').fadeIn(150);
    } else {
        $('#logistics-focus-banner').hide();
        $('#logistics-focus-highlights').hide();
    }
}

function renderLogisticsFocusHighlights(summary, distributionsData) {
    if (!summary || summary.focus !== 'warehouse') {
        toggleLogisticsFocusView(false);
        return;
    }

    toggleLogisticsFocusView(true);
    $('#logistics-focus-text').text(`نمای عملکرد انبار: ${truncateLabel(summary.label || '-', 60)}`);

    const products = summary.top_entities ? summary.top_entities.products || [] : [];
    const customers = summary.top_entities ? summary.top_entities.customers || [] : [];

    renderLogisticsFocusList('#logisticsFocusProductsList', '#logisticsFocusProductsEmpty', products, 'product');
    renderLogisticsFocusList('#logisticsFocusCustomersList', '#logisticsFocusCustomersEmpty', customers, 'customer');
}

function renderLogisticsFocusList(listSelector, emptySelector, data, type) {
    const $list = $(listSelector);
    const $empty = $(emptySelector);

    if (!$list.length) return;

    if (!data || data.length === 0) {
        $list.empty();
        $empty.show();
        return;
    }

    $empty.hide();
    $list.empty();

    data.forEach((item, index) => {
        const label = truncateLabel(item.label || '-', 50);
        const amount = formatCurrency(item.total_amount || 0);
        const quantity = item.total_quantity ? formatNumber(Math.round(item.total_quantity)) + ' واحد' : '';
        const orders = item.order_count ? formatNumber(item.order_count) + ' سفارش' : '';
        const meta = [quantity, orders].filter(Boolean).join(' • ');

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

// Render Delivery Rate Chart (Gauge-like Doughnut)
function renderDeliveryRateChart(deliveryRate) {
    const ctx = document.getElementById('deliveryRateChart');
    
    if (deliveryRateChart) {
        deliveryRateChart.destroy();
    }
    
    const remaining = 100 - deliveryRate;
    
    deliveryRateChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['تحویل شده', 'باقیمانده'],
            datasets: [{
                data: [deliveryRate, remaining],
                backgroundColor: [
                    'rgba(36, 210, 181, 0.8)',
                    'rgba(255, 180, 77, 0.3)'
                ],
                borderColor: [
                    'rgba(36, 210, 181, 1)',
                    'rgba(255, 180, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            circumference: 180,
            rotation: 270,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });
    
    $('#deliveryRatePercent').text(deliveryRate.toFixed(1) + '%');
}

// Render Pending Products Chart
function renderPendingProductsChart(data) {
    const ctx = document.getElementById('pendingProductsChart');
    
    if (pendingProductsChart) {
        pendingProductsChart.destroy();
    }
    
    const labels = data.map(item => item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name);
    const quantities = data.map(item => item.pending_quantity);
    
    pendingProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'مقدار معلق',
                data: quantities,
                backgroundColor: 'rgba(255, 180, 77, 0.7)',
                borderColor: 'rgba(255, 180, 77, 1)',
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
                            return 'مقدار: ' + formatNumber(context.parsed.x);
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
                            size: 10
                        }
                    }
                }
            }
        }
    });
}

// Update Pending Products Table
function updatePendingProductsTable(data) {
    const tbody = $('#pendingProductsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center text-success"><i class="ti-check"></i> همه محصولات تحویل شده‌اند!</td></tr>');
        return;
    }
    
    data.forEach(item => {
        const row = `
            <tr>
                <td><strong>${item.product_name.length > 25 ? item.product_name.substring(0, 25) + '...' : item.product_name}</strong></td>
                <td class="text-warning"><strong>${formatNumber(item.pending_quantity)}</strong></td>
                <td>${formatNumber(item.order_count)}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update Delivery Statistics
function updateDeliveryStatistics(stats, deliveryRate) {
    $('#deliveredQty').text(formatNumber(Math.round(stats.total_issued || 0)));
    $('#pendingQty').text(formatNumber(Math.round(stats.total_remaining || 0)));
    $('#totalOrders').text(formatNumber(stats.total_orders || 0));
}

// Evaluate Delivery Performance
function evaluateDeliveryPerformance(deliveryRate, stats) {
    let alertClass = 'alert-success';
    let icon = 'ti-check';
    let message = '';
    let recommendation = '';
    
    if (deliveryRate >= 90) {
        alertClass = 'alert-success';
        icon = 'ti-check';
        message = 'عملکرد عالی! نرخ تحویل شما بسیار بالاست.';
        recommendation = 'ادامه دهید و این عملکرد را حفظ کنید.';
    } else if (deliveryRate >= 75) {
        alertClass = 'alert-info';
        icon = 'ti-info-alt';
        message = 'عملکرد خوب! نرخ تحویل شما قابل قبول است.';
        recommendation = 'سعی کنید فرآیندهای تحویل را بهینه‌تر کنید تا به نرخ 90% برسید.';
    } else if (deliveryRate >= 50) {
        alertClass = 'alert-warning';
        icon = 'ti-alert';
        message = 'توجه! نرخ تحویل شما نیاز به بهبود دارد.';
        recommendation = 'لطفاً سفارشات معلق را بررسی و اولویت‌بندی کنید.';
    } else {
        alertClass = 'alert-danger';
        icon = 'ti-close';
        message = 'هشدار! نرخ تحویل شما بسیار پایین است.';
        recommendation = 'فوراً به سفارشات معلق رسیدگی کنید و موانع تحویل را شناسایی نمایید.';
    }
    
    const pendingOrders = stats.total_orders - (stats.total_orders * (deliveryRate / 100));
    
    $('#deliveryPerformanceAlert').removeClass('alert-success alert-info alert-warning alert-danger')
        .addClass(alertClass)
        .html(`
            <h5><i class="${icon}"></i> ${message}</h5>
            <p class="mb-2">${recommendation}</p>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">سفارشات تحویل شده:</small>
                    <br><strong>${formatNumber(Math.round(stats.total_orders * (deliveryRate / 100)))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">سفارشات معلق:</small>
                    <br><strong class="text-warning">${formatNumber(Math.round(pendingOrders))}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">نرخ تحویل:</small>
                    <br><strong>${deliveryRate.toFixed(1)}%</strong>
                </div>
            </div>
        `);
}
</script>

