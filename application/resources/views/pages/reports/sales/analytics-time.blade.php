<!-- Time Analytics Tab Content -->
<div class="row">
    <!-- Monthly Trend Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-stats-up"></i> روند فروش ماهانه
                </h5>
                <p class="text-muted">نمایش تعداد و مبلغ فروش به تفکیک ماه</p>
                <div class="chart-container">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Seasonal Analysis Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-pie-chart"></i> تحلیل فصلی
                </h5>
                <p class="text-muted">سهم هر فصل از کل فروش</p>
                <div class="chart-container">
                    <canvas id="seasonalChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Table -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti-list"></i> آمار ماهانه
                </h5>
                <p class="text-muted">جزئیات فروش به تفکیک ماه</p>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover" id="monthlyStatsTable">
                        <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                            <tr>
                                <th>ماه</th>
                                <th>تعداد</th>
                                <th>مبلغ کل</th>
                                <th>میانگین</th>
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

    <!-- Key Statistics Cards -->
    <div class="col-12">
        <div class="row" id="timeStatsCards">
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">بهترین ماه</p>
                                <h4 class="mb-0" id="bestMonth">-</h4>
                            </div>
                            <div class="text-primary">
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
                                <p class="text-muted mb-1">کل فروش</p>
                                <h4 class="mb-0" id="totalSales">-</h4>
                            </div>
                            <div class="text-success">
                                <i class="ti-wallet" style="font-size: 32px;"></i>
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
                                <p class="text-muted mb-1">میانگین ماهانه</p>
                                <h4 class="mb-0" id="avgMonthlySales">-</h4>
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
                                <p class="text-muted mb-1">بهترین فصل</p>
                                <h4 class="mb-0" id="bestSeason">-</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ti-flag" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let monthlyTrendChart = null;
let seasonalChart = null;
window.timeChartsLoaded = false;

// Load Time Analytics Data
function loadTimeAnalytics() {
    console.log('Loading time analytics...');
    const dates = getFilterDates();
    
    // Load Monthly Trend
    $.ajax({
        url: '/report/sales/analytics/monthly-trend',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Monthly trend response:', response);
            if (response.success && response.data) {
                renderMonthlyTrendChart(response.data);
                updateMonthlyStatsTable(response.data);
                calculateTimeStatistics(response.data);
            } else {
                console.error('Invalid response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading monthly trend:', status, error);
            console.error('Response:', xhr.responseText);
            alert('خطا در بارگذاری داده‌ها. لطفاً کنسول مرورگر را بررسی کنید.');
        }
    });
    
    // Load Seasonal Analysis
    $.ajax({
        url: '/report/sales/analytics/seasonal-analysis',
        method: 'POST',
        data: dates,
        dataType: 'json',
        success: function(response) {
            console.log('Seasonal analysis response:', response);
            if (response.success && response.data) {
                renderSeasonalChart(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading seasonal analysis:', status, error);
        }
    });
    
    window.timeChartsLoaded = true;
}

// Render Monthly Trend Chart
function renderMonthlyTrendChart(data) {
    console.log('Rendering monthly trend chart with data:', data);
    
    const ctx = document.getElementById('monthlyTrendChart');
    
    if (!ctx) {
        console.error('Canvas element #monthlyTrendChart not found!');
        return;
    }
    
    if (monthlyTrendChart) {
        monthlyTrendChart.destroy();
    }
    
    // Check if data is empty
    if (!data || data.length === 0) {
        console.warn('No data for monthly trend chart');
        $('#monthlyTrendChart').parent().html('<div class="alert alert-warning text-center"><i class="ti-info-alt"></i> داده‌ای برای نمایش یافت نشد. لطفاً بازه تاریخی دیگری انتخاب کنید.</div>');
        return;
    }
    
    // Create labels with year and month (e.g., "شهریور 1403")
    const labels = data.map(item => {
        const monthName = persianMonths[item.month - 1];
        // If multiple years exist, show year in label
        const uniqueYears = [...new Set(data.map(d => d.year))];
        if (uniqueYears.length > 1) {
            return `${monthName} ${item.year}`;
        } else {
            return monthName;
        }
    });
    
    const counts = data.map(item => item.count);
    const amounts = data.map(item => item.total_amount);
    
    console.log('Chart labels:', labels);
    console.log('Chart counts:', counts);
    console.log('Chart amounts:', amounts);
    
    monthlyTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'تعداد فروش',
                    data: counts,
                    borderColor: '#5969ff',
                    backgroundColor: 'rgba(89, 105, 255, 0.1)',
                    yAxisID: 'y',
                    tension: 0.4
                },
                {
                    label: 'مبلغ فروش (ریال)',
                    data: amounts,
                    borderColor: '#24d2b5',
                    backgroundColor: 'rgba(36, 210, 181, 0.1)',
                    yAxisID: 'y1',
                    tension: 0.4
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
                        text: 'تعداد',
                        font: {
                            family: 'Vazir'
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'مبلغ (ریال)',
                        font: {
                            family: 'Vazir'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

// Render Seasonal Chart
function renderSeasonalChart(data) {
    const ctx = document.getElementById('seasonalChart');
    
    if (seasonalChart) {
        seasonalChart.destroy();
    }
    
    // Check if data is empty
    if (!data || data.length === 0) {
        console.warn('No data for seasonal chart');
        return;
    }
    
    const labels = data.map(item => item.name);
    const totals = data.map(item => item.total);
    
    seasonalChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: totals,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(54, 162, 235, 0.8)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
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
                            size: 12
                        },
                        padding: 15
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
    
    // Update best season
    const bestSeasonData = data.reduce((max, item) => item.total > max.total ? item : max, data[0]);
    $('#bestSeason').text(bestSeasonData.name);
}

// Update Monthly Stats Table
function updateMonthlyStatsTable(data) {
    const tbody = $('#monthlyStatsTable tbody');
    tbody.empty();
    
    if (data.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted">داده‌ای یافت نشد</td></tr>');
        return;
    }
    
    // Check if multiple years exist
    const uniqueYears = [...new Set(data.map(d => d.year))];
    const showYear = uniqueYears.length > 1;
    
    data.forEach(item => {
        const monthName = showYear ? `${persianMonths[item.month - 1]} ${item.year}` : persianMonths[item.month - 1];
        
        const row = `
            <tr>
                <td><strong>${monthName}</strong></td>
                <td>${formatNumber(item.count)}</td>
                <td>${formatNumber(Math.round(item.total_amount))} ریال</td>
                <td>${formatNumber(Math.round(item.avg_amount))} ریال</td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Calculate Time Statistics
function calculateTimeStatistics(data) {
    if (data.length === 0) return;
    
    // Best month
    const bestMonth = data.reduce((max, item) => item.total_amount > max.total_amount ? item : max, data[0]);
    const uniqueYears = [...new Set(data.map(d => d.year))];
    const monthLabel = uniqueYears.length > 1 ? 
        `${persianMonths[bestMonth.month - 1]} ${bestMonth.year}` : 
        persianMonths[bestMonth.month - 1];
    $('#bestMonth').text(monthLabel);
    
    // Total sales
    const totalSales = data.reduce((sum, item) => sum + parseFloat(item.total_amount), 0);
    $('#totalSales').text(formatNumber(Math.round(totalSales)) + ' ریال');
    
    // Average monthly sales
    const avgSales = totalSales / data.length;
    $('#avgMonthlySales').text(formatNumber(Math.round(avgSales)) + ' ریال');
}
</script>

