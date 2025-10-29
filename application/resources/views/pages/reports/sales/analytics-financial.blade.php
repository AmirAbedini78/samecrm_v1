<!-- Financial Analytics Tab Content -->
<div class="row">
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
    <div class="col-md-3 mb-4">
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

    <div class="col-md-3 mb-4">
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

    <div class="col-md-3 mb-4">
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

    <div class="col-md-3 mb-4">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">میانگین مبلغ فاکتور</p>
                        <h4 class="mb-0 text-info" id="avgInvoice">-</h4>
                    </div>
                    <div class="text-info">
                        <i class="ti-receipt" style="font-size: 32px;"></i>
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
window.financialChartsLoaded = false;

// Load Financial Analytics Data
function loadFinancialAnalytics() {
    console.log('Loading financial analytics...');
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
    
    // Average invoice
    const totalCount = data.reduce((sum, item) => sum + parseInt(item.count), 0);
    const avgInvoice = totalCount > 0 ? totalRevenue / totalCount : 0;
    $('#avgInvoice').text(formatNumber(Math.round(avgInvoice)) + ' ریال');
    
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

