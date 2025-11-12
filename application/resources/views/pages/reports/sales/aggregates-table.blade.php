<!--table-->
<div class="table-responsive report-results-table-container" id="report-results-container">
    <div class="row g-3">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="mb-3">نتایج تجمیعی</h5>
                    <p class="mb-1">تعداد رکورد: <span id="agg-count">{{ $report['count'] ?? 0 }}</span></p>
                    <p class="mb-0">مجموع مبلغ فروش: <span id="agg-total">{{ number_format($report['total_sales_amount'] ?? 0) }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
