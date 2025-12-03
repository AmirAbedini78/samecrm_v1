<div class="warehouse-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">هشدارهای انقضا</h5>
        <div>
            <select id="expiry-status-filter" class="form-control form-control-sm" style="width: 200px; display: inline-block;">
                <option value="">همه</option>
                <option value="expired">منقضی شده</option>
                <option value="approaching">نزدیک به انقضا</option>
                <option value="normal">عادی</option>
            </select>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="alert alert-danger">
                <strong>منقضی شده:</strong> <span id="expired-count">0</span> کالا
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-warning">
                <strong>نزدیک به انقضا:</strong> <span id="approaching-count">0</span> کالا
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">
                <strong>عادی:</strong> <span id="normal-count">0</span> کالا
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="expiry-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>کد کالا</th>
                    <th>نام کالا</th>
                    <th>تاریخ انقضا</th>
                    <th>روزهای باقی‌مانده</th>
                    <th>موجودی</th>
                    <th>وضعیت</th>
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



