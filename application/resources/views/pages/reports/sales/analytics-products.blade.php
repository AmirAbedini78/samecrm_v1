<!-- Products Analytics Tab Content -->
<div class="row">
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
            <div class="col-md-3 mb-3">
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
            <div class="col-md-3 mb-3">
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
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">میانگین فروش محصول</p>
                                <h4 class="mb-0" id="avgProductSales">-</h4>
                            </div>
                            <div class="text-info">
                                <i class="ti-bar-chart" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
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
window.productsChartsLoaded = false;

// Load Products Analytics Data
function loadProductsAnalytics() {
    console.log('Loading products analytics...');
    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/top-products',
        method: 'POST',
        data: { ...dates, limit: 10 },
        dataType: 'json',
        success: function(response) {
            console.log('Top products response:', response);
            if (response.success && response.data) {
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
    
    // Average sales per product
    const totalSales = data.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
    const avgSales = totalSales / data.length;
    $('#avgProductSales').text(formatNumber(Math.round(avgSales)) + ' ریال');
    
    // Total quantity
    const totalQty = data.reduce((sum, item) => sum + parseFloat(item.total_quantity), 0);
    $('#totalQuantity').text(formatNumber(Math.round(totalQty)));
}
</script>

