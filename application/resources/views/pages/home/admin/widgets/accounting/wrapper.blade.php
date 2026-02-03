{{-- داشبورد حسابداری/گزارش‌ها (نسخه جدید) --}}

@php
    $inv = data_get($payload, 'accounting.inventory', []);
    $bel = data_get($payload, 'accounting.belzona', []);
    $sal = data_get($payload, 'accounting.sales', []);
    $set = data_get($payload, 'accounting.settlements', []);

    $lowStockItems = data_get($payload, 'accounting.tables.low_stock_items', collect());
    $latestSales = data_get($payload, 'accounting.tables.latest_sales', collect());
    $latestSettlements = data_get($payload, 'accounting.tables.latest_settlements', collect());

    $salesChartLabels = data_get($payload, 'accounting.charts.sales_monthly.labels', []);
    $salesChartSeries = data_get($payload, 'accounting.charts.sales_monthly.series', []);
@endphp

<!-- TOP SUMMARY CARDS -->
<div class="row">
    <!-- Inventory -->
    <div class="col-lg-3 col-md-6 click-url cursor-pointer" data-url="{{ url('inventory') }}">
        <div class="card">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ number_format((float)($inv['items_count'] ?? 0)) }}</h2>
                        <h6 class="text-muted m-b-0">انبار - تعداد کالا</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto">
                        <i class="text-info icon-Box-Open"></i>
                    </div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="col-lg-3 col-md-6 click-url cursor-pointer" data-url="{{ url('inventory') }}">
        <div class="card">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ number_format((float)($inv['low_stock_count'] ?? 0)) }}</h2>
                        <h6 class="text-muted m-b-0">انبار - کمبود موجودی</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto">
                        <i class="text-warning icon-Notification"></i>
                    </div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-warning w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Belzona Inventory -->
    <div class="col-lg-3 col-md-6 click-url cursor-pointer" data-url="{{ url('belzona-inventory') }}">
        <div class="card">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ number_format((float)($bel['total_balance'] ?? 0)) }}</h2>
                        <h6 class="text-muted m-b-0">انبار بلزونا - موجودی کل</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto">
                        <i class="text-primary icon-Warehouse"></i>
                    </div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-primary w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Sales (this month) -->
    <div class="col-lg-3 col-md-6 click-url cursor-pointer" data-url="{{ url('sales') }}">
        <div class="card">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ runtimeMoneyFormat($sal['this_month_net'] ?? 0) }}</h2>
                        <h6 class="text-muted m-b-0">فروش - خالص این ماه</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto">
                        <i class="text-success icon-Shopping-Cart"></i>
                    </div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-success w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Invoices + Settlements (single menu) -->
    <div class="col-lg-3 col-md-6 click-url cursor-pointer" data-url="{{ url('invoice-settlements') }}">
        <div class="card">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ runtimeMoneyFormat($set['total_balance'] ?? 0) }}</h2>
                        <h6 class="text-muted m-b-0">فاکتور و تسویه - مانده کل</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto">
                        <i class="text-dark icon-Money-2"></i>
                    </div>
                </div>
                <div class="small text-muted">
                    سررسید: <strong>{{ runtimeMoneyFormat($payload['invoices']['due'] ?? 0) }}</strong>
                    &nbsp;|&nbsp;
                    معوق: <strong>{{ runtimeMoneyFormat($payload['invoices']['overdue'] ?? 0) }}</strong>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-dark w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<!-- CHART + QUICK LINKS -->
<div class="row">
    <!-- Sales Monthly Trend Chart -->
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-30">
                    <h5 class="card-title m-b-0 align-self-center">روند فروش (۱۲ ماه آخر)</h5>
                    <div class="ml-auto align-self-center">
                        <a class="btn btn-sm btn-outline-primary" href="{{ url('report/sales/analytics') }}">گزارش فروش</a>
                    </div>
                </div>
                <div class="ct-charts" id="admin-dashboard-sales-monthly-trend"></div>
                <div class="row text-center">
                    <div class="col-lg-4 col-md-4 m-t-20">
                        <h2 class="m-b-0 font-light">{{ number_format((float)($sal['rows_count'] ?? 0)) }}</h2>
                        <small>تعداد ردیف فروش</small>
                    </div>
                    <div class="col-lg-4 col-md-4 m-t-20">
                        <h2 class="m-b-0 font-light">{{ runtimeMoneyFormat($sal['all_time_net'] ?? 0) }}</h2>
                        <small>جمع خالص</small>
                    </div>
                    <div class="col-lg-4 col-md-4 m-t-20">
                        <h2 class="m-b-0 font-light">{{ runtimeMoneyFormat($set['total_balance'] ?? 0) }}</h2>
                        <small>مانده تسویه‌ها</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">دسترسی سریع</h5>
                <div class="list-group">
                    <a class="list-group-item list-group-item-action" href="{{ url('inventory') }}">انبار</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('belzona-inventory') }}">انبار بلزونا</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('sales') }}">فروش</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('invoice-settlements') }}">فاکتور و تسویه</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('report/warehouse') }}">گزارش انبار</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('report/sales/analytics') }}">گزارش فروش</a>
                </div>
                <hr>
                <div class="small text-muted">
                    آخرین ایمپورت بلزونا:
                    <strong>{{ $bel['last_import_at'] ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABLES -->
<div class="row">
    <!-- Low Stock Items -->
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-20">
                    <h5 class="card-title m-b-0 align-self-center">کالاهای کمبود موجودی</h5>
                    <div class="ml-auto align-self-center">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ url('inventory') }}">مشاهده انبار</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>کد</th>
                                <th>نام</th>
                                <th class="text-center">موجودی</th>
                                <th class="text-center">حداقل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $item)
                            <tr class="click-url cursor-pointer" data-url="{{ url('inventory/'.$item->inventory_id) }}">
                                <td>{{ $item->inventory_code }}</td>
                                <td>{{ $item->inventory_name }}</td>
                                <td class="text-center">{{ number_format((float)$item->current_quantity) }} {{ $item->main_unit }}</td>
                                <td class="text-center">{{ number_format((float)$item->minimum_stock) }} {{ $item->main_unit }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center">موردی یافت نشد</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Settlements -->
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-20">
                    <h5 class="card-title m-b-0 align-self-center">آخرین تسویه‌ها</h5>
                    <div class="ml-auto align-self-center">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ url('invoice-settlements') }}">مشاهده تسویه</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>شماره</th>
                                <th>تاریخ</th>
                                <th>مشتری</th>
                                <th class="text-right">مانده</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestSettlements as $s)
                            <tr>
                                <td>{{ $s->document_number }}</td>
                                <td>{{ $s->document_date }}</td>
                                <td>{{ $s->customer_name }}</td>
                                <td class="text-right">{{ runtimeMoneyFormat($s->balance_amount ?? 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center">موردی یافت نشد</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Sales -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex m-b-20">
                    <h5 class="card-title m-b-0 align-self-center">آخرین فروش‌ها</h5>
                    <div class="ml-auto align-self-center">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ url('sales') }}">مشاهده فروش</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>شماره سند</th>
                                <th>تاریخ</th>
                                <th>مشتری</th>
                                <th>محصول</th>
                                <th class="text-right">خالص</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestSales as $r)
                            <tr class="click-url cursor-pointer" data-url="{{ url('sales/'.$r->sales_id) }}">
                                <td>{{ $r->document_number }}</td>
                                <td>{{ $r->document_date }}</td>
                                <td>{{ $r->customer_name }}</td>
                                <td>{{ $r->product_name }}</td>
                                <td class="text-right">{{ runtimeMoneyFormat($r->base_net_amount ?? 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-muted text-center">موردی یافت نشد</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--[DYNAMIC INLINE SCRIPT] - Sales Trend Chart-->
<script>
    NX.admin_home_sales_monthly_labels = JSON.parse('{!! json_encode($salesChartLabels) !!}', true);
    NX.admin_home_sales_monthly_series = JSON.parse('{!! json_encode($salesChartSeries) !!}', true);

    $(document).ready(function () {
        if ($("#admin-dashboard-sales-monthly-trend").length && typeof Chartist !== 'undefined') {
            var plugins = [];
            if (Chartist.plugins && typeof Chartist.plugins.tooltip === 'function') {
                plugins.push(Chartist.plugins.tooltip());
            }
            new Chartist.Line('#admin-dashboard-sales-monthly-trend', {
                labels: NX.admin_home_sales_monthly_labels,
                series: [NX.admin_home_sales_monthly_series]
            }, {
                lineSmooth: Chartist.Interpolation.simple({ divisor: 2 }),
                showArea: true,
                low: 0,
                fullWidth: true,
                plugins: plugins,
            });
        }
    });
</script>

