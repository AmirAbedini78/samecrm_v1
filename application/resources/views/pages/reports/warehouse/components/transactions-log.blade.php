<div class="warehouse-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">لاگ تراکنش‌های انبار</h5>
        <div>
            <select id="transaction-type-filter" class="form-control form-control-sm" style="width: 150px; display: inline-block;">
                <option value="">همه</option>
                <option value="input">ورود</option>
                <option value="output">خروج</option>
            </select>
            <input type="text" id="transaction-search" class="form-control form-control-sm" placeholder="جستجو..." style="width: 200px; display: inline-block;">
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="transactions-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>نوع</th>
                    <th>کالا</th>
                    <th>مقدار</th>
                    <th>مبلغ</th>
                    <th>انبار</th>
                    <th>شماره سند</th>
                    <th>کاربر</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" class="text-center">در حال بارگذاری...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

