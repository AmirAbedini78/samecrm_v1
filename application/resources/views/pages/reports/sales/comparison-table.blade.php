<!--table-->
<div class="table-responsive report-results-table-container" id="report-results-container">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="mb-3">بازه 1</h5>
                    <p class="mb-1">تعداد رکورد: <span id="r1-count">{{ $report['range1']['count'] ?? 0 }}</span></p>
                    <p class="mb-1">مجموع مبلغ فروش: <span id="r1-total">{{ number_format($report['range1']['total_sales_amount'] ?? 0) }}</span></p>
                    <p class="mb-0">میانگین مبلغ فروش: <span id="r1-avg">{{ number_format($report['range1']['average_sales_amount'] ?? 0) }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="mb-3">بازه 2</h5>
                    <p class="mb-1">تعداد رکورد: <span id="r2-count">{{ $report['range2']['count'] ?? 0 }}</span></p>
                    <p class="mb-1">مجموع مبلغ فروش: <span id="r2-total">{{ number_format($report['range2']['total_sales_amount'] ?? 0) }}</span></p>
                    <p class="mb-0">میانگین مبلغ فروش: <span id="r2-avg">{{ number_format($report['range2']['average_sales_amount'] ?? 0) }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row g-3 mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">جدول بازه 1</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>تاریخ سند</th>
                                    <th>مشتری</th>
                                    <th>محصول</th>
                                    <th>مقدار</th>
                                    <th>مبلغ فروش</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($report['range1']['rows']) && count($report['range1']['rows']) > 0)
                                    @foreach($report['range1']['rows'] as $row)
                                    <tr>
                                        <td>{{ $row->sales_id }}</td>
                                        <td>{{ $row->document_date_persian }}</td>
                                        <td>{{ $row->customer_name ?? '' }}</td>
                                        <td>{{ $row->product_name ?? '' }}</td>
                                        <td>{{ $row->main_quantity ?? 0 }}</td>
                                        <td>{{ number_format($row->base_sales_amount ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6" class="text-center">هیچ رکوردی یافت نشد</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">جدول بازه 2</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>تاریخ سند</th>
                                    <th>مشتری</th>
                                    <th>محصول</th>
                                    <th>مقدار</th>
                                    <th>مبلغ فروش</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($report['range2']['rows']) && count($report['range2']['rows']) > 0)
                                    @foreach($report['range2']['rows'] as $row)
                                    <tr>
                                        <td>{{ $row->sales_id }}</td>
                                        <td>{{ $row->document_date_persian }}</td>
                                        <td>{{ $row->customer_name ?? '' }}</td>
                                        <td>{{ $row->product_name ?? '' }}</td>
                                        <td>{{ $row->main_quantity ?? 0 }}</td>
                                        <td>{{ number_format($row->base_sales_amount ?? 0) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6" class="text-center">هیچ رکوردی یافت نشد</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
