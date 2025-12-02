<div class="warehouse-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">گزارش فروش کالاها</h5>
        <div>
            <input type="text" id="sales-year-filter" class="form-control form-control-sm" placeholder="سال (مثلا 1403)" style="width: 150px; display: inline-block;">
            <button id="load-sales-report" class="btn btn-sm btn-primary">بارگذاری</button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="sales-report-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>کد کالا</th>
                    <th>نام کالا</th>
                    <th>تعداد فروش</th>
                    <th>مقدار کل</th>
                    <th>مبلغ کل</th>
                    <th>میانگین قیمت</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">در حال بارگذاری...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


