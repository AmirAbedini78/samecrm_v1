<!-- Customers Analytics Tab Content -->
<div class="row">
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
                                <th>میانگین</th>
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
                <div class="mb-4">
                    <p class="text-muted mb-2">میانگین خرید هر مشتری</p>
                    <h4 class="mb-0" id="avgCustomerPurchase">-</h4>
                </div>
                <div>
                    <p class="text-muted mb-2">میانگین تعداد سفارش</p>
                    <h4 class="mb-0" id="avgOrdersPerCustomer">-</h4>
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

<script>
let topCustomersChart = null;
window.customersChartsLoaded = false;

// Load Customers Analytics Data
function loadCustomersAnalytics() {
    const dates = getFilterDates();
    
    $.ajax({
        url: '/report/sales/analytics/top-customers',
        method: 'POST',
        data: { ...dates, limit: 10 },
        success: function(response) {
            if (response.success) {
                renderTopCustomersChart(response.data);
                updateTopCustomersTable(response.data);
                calculateCustomerStatistics(response.data);
                calculateParetoAnalysis(response.data);
            }
        },
        error: function(xhr) {
            console.error('Error loading top customers:', xhr);
        }
    });
    
    window.customersChartsLoaded = true;
}

// Render Top Customers Chart
function renderTopCustomersChart(data) {
    const ctx = document.getElementById('topCustomersChart');
    
    if (topCustomersChart) {
        topCustomersChart.destroy();
    }
    
    const labels = data.map(item => item.customer_name.length > 25 ? item.customer_name.substring(0, 25) + '...' : item.customer_name);
    const amounts = data.map(item => item.total_amount);
    const counts = data.map(item => item.order_count);
    
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
                            label += formatNumber(context.parsed.y);
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
        tbody.append('<tr><td colspan="5" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    data.forEach((item, index) => {
        const badgeClass = index === 0 ? 'bg-warning' : (index === 1 ? 'bg-secondary' : (index === 2 ? 'bg-bronze' : 'bg-light text-dark'));
        const row = `
            <tr>
                <td><span class="badge ${badgeClass}">${index + 1}</span></td>
                <td><strong>${item.customer_name}</strong></td>
                <td>${formatNumber(item.order_count)}</td>
                <td>${formatNumber(Math.round(item.total_amount))} ریال</td>
                <td>${formatNumber(Math.round(item.avg_amount))} ریال</td>
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
    
    // Average purchase per customer
    const totalSales = data.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
    const avgPurchase = totalSales / data.length;
    $('#avgCustomerPurchase').text(formatNumber(Math.round(avgPurchase)) + ' ریال');
    
    // Average orders per customer
    const totalOrders = data.reduce((sum, item) => sum + parseInt(item.order_count), 0);
    const avgOrders = totalOrders / data.length;
    $('#avgOrdersPerCustomer').text(formatNumber(avgOrders.toFixed(1)));
}

// Calculate Pareto Analysis (80/20 rule)
function calculateParetoAnalysis(data) {
    if (data.length === 0) {
        $('#paretoAnalysis').html('<i class="ti-alert"></i> داده کافی برای تحلیل موجود نیست');
        return;
    }
    
    const totalSales = data.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
    const target80 = totalSales * 0.8;
    
    let cumulativeSum = 0;
    let count80 = 0;
    
    for (let i = 0; i < data.length; i++) {
        cumulativeSum += parseFloat(data[i].total_amount);
        count80++;
        if (cumulativeSum >= target80) {
            break;
        }
    }
    
    const percentage = ((count80 / data.length) * 100).toFixed(1);
    
    $('#paretoAnalysis').html(`
        <i class="ti-info-alt"></i> 
        <strong>${count80}</strong> مشتری از ${data.length} (${percentage}%) مشتری برتر، 
        <strong>80%</strong> از کل فروش را تشکیل می‌دهند.
        <br>
        <small class="text-muted">این تحلیل به شما کمک می‌کند روی مشتریان کلیدی تمرکز کنید.</small>
    `);
}
</script>

<style>
.bg-bronze {
    background-color: #CD7F32;
    color: white;
}
</style>

