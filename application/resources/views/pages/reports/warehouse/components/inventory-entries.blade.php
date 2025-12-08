<div class="warehouse-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">ورودهای انبار</h5>
        <div class="d-flex gap-2">
            <select id="entries-type-filter" class="form-control form-control-sm" style="width: 150px;">
                <option value="">همه انواع</option>
                <option value="ورودی">ورودی</option>
                <option value="خروجی">خروجی</option>
            </select>
            <input type="text" id="entries-search" class="form-control form-control-sm" placeholder="جستجو..." style="width: 200px;">
        </div>
    </div>
    
    <div class="table-responsive">
        <table id="inventory-entries-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>کد کالا</th>
                    <th>نام کالا</th>
                    <th>تاریخ</th>
                    <th>سند</th>
                    <th>نوع</th>
                    <th>شماره سند مبنا</th>
                    <th>مقدار</th>
                    <th>فی</th>
                    <th>مبلغ تمام شده</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10" class="text-center">در حال بارگذاری...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for viewing entries of a specific inventory -->
<div class="modal fade" id="inventoryEntriesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="entries-modal-title">ورودهای کالا</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>کد کالا:</strong> <span id="entries-modal-code"></span><br>
                    <strong>نام کالا:</strong> <span id="entries-modal-name"></span>
                </div>
                <div class="table-responsive">
                    <table id="inventory-item-entries-table" class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>سند</th>
                                <th>نوع</th>
                                <th>شماره سند مبنا</th>
                                <th>مقدار</th>
                                <th>فی</th>
                                <th>مبلغ تمام شده</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">در حال بارگذاری...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>



