<div class="warehouse-section">
    <h5 class="mb-3">کالاهای خارج از موجودی</h5>
    
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>موجودی منفی</h6>
                    <h3 id="negative-stock-count">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>غیرفیزیکی</h6>
                    <h3 id="not-physical-count">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>مغایرت</h6>
                    <h3 id="discrepancy-count">0</h3>
                </div>
            </div>
        </div>
    </div>
    
    <ul class="nav nav-tabs" id="outside-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#negative-stock">موجودی منفی</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#not-physical">غیرفیزیکی</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#discrepancy">مغایرت</a>
        </li>
    </ul>
    
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="negative-stock">
            <table id="negative-stock-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>کد کالا</th>
                        <th>نام کالا</th>
                        <th>موجودی</th>
                        <th>واحد</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4" class="text-center">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="not-physical">
            <table id="not-physical-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>کد کالا</th>
                        <th>نام کالا</th>
                        <th>موجودی</th>
                        <th>واحد</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4" class="text-center">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="discrepancy">
            <table id="discrepancy-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>کد کالا</th>
                        <th>نام کالا</th>
                        <th>موجودی</th>
                        <th>واحد</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4" class="text-center">در حال بارگذاری...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>





