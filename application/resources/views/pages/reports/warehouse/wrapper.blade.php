<!-- Warehouse Reports Dashboard -->
<div class="row" id="warehouse-reports-dashboard">
    <div class="col-12">
        @php
            $categoryOptions = isset($categories) ? $categories : collect();
            $customCategoryOptions = isset($customCategories) ? $customCategories : collect();
        @endphp
        @once
            <link rel="stylesheet" href="{{ asset('public/css/warehouse-reports.css') }}">
        @endonce
        <!-- Focus Status -->
        <div class="card quick-status-card mb-4">
            <div class="card-body">
                <div class="row text-center" id="warehouse-critical-status">
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="status-pill text-primary" data-filter="all">
                            <span class="status-icon bg-primary-light"><i class="ti-layers"></i></span>
                            <div>
                                <small>کل کالاها</small>
                                <h3 id="summary-total-items" class="mb-0">-</h3>
                            </div>
                        </div>

@once
<script>
(function () {
    function bootWarehouseScripts() {
        if (window.initWarehouseReports) {
            window.initWarehouseReports();
            return;
        }

        if (!document.getElementById('warehouse-reports-script')) {
            var script = document.createElement('script');
            script.id = 'warehouse-reports-script';
            script.src = '{{ asset('public/js/core/warehouse-reports.js') }}';
            script.onload = function () {
                if (window.initWarehouseReports) {
                    window.initWarehouseReports();
                }
            };
            document.body.appendChild(script);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootWarehouseScripts);
    } else {
        bootWarehouseScripts();
    }
})();
</script>
@endonce
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="status-pill text-success" data-filter="value">
                            <span class="status-icon bg-success-light"><i class="ti-money"></i></span>
                            <div>
                                <small>ارزش کل موجودی</small>
                                <h3 id="summary-total-value" class="mb-0">-</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="status-pill text-warning" data-filter="expiring">
                            <span class="status-icon bg-warning-light"><i class="ti-alarm-clock"></i></span>
                            <div>
                                <small>نزدیک به انقضا</small>
                                <h3 id="summary-expiring" class="mb-0">-</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="status-pill text-danger" data-filter="expired">
                            <span class="status-icon bg-danger-light"><i class="ti-na"></i></span>
                            <div>
                                <small>منقضی شده</small>
                                <h3 id="summary-expired" class="mb-0">-</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="card mb-4" id="warehouse-quick-filters">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3" id="warehouse-actions-toolbar">
                    <div>
                        <h6 class="mb-0">فیلترهای سریع</h6>
                        <small class="text-muted">بدون جابجایی تب‌ها، نتیجه بلافاصله به‌روزرسانی می‌شود</small>
                    </div>
                    <div class="toolbar-actions">
                        <button class="btn btn-outline-secondary btn-sm" id="btn-open-custom-panel">
                            <i class="ti-layout"></i> دسته‌های دلخواه
                        </button>
                        <button class="btn btn-outline-primary btn-sm" id="btn-open-alert-panel">
                            <i class="ti-bell"></i> تنظیم هشدار
                        </button>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">بازه زمانی</label>
                        <div class="btn-group btn-group-sm w-100" role="group" aria-label="filter-date-range">
                            <button class="btn btn-outline-secondary js-quick-range active" data-range="all">همه</button>
                            <button class="btn btn-outline-secondary js-quick-range" data-range="today">امروز</button>
                            <button class="btn btn-outline-secondary js-quick-range" data-range="week">۷ روز</button>
                            <button class="btn btn-outline-secondary js-quick-range" data-range="month">۳۰ روز</button>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">تاریخ دقیق</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="filter_from_date" class="form-control persian-date-input" placeholder="از تاریخ" autocomplete="off">
                            <input type="text" id="filter_to_date" class="form-control persian-date-input" placeholder="تا تاریخ" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">دسته‌بندی / دسته سفارشی</label>
                        <select id="filter_category" class="form-control form-control-sm">
                            <option value="">همه دسته‌ها</option>
                            @foreach($categoryOptions as $category)
                                <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        <div class="custom-category-badges mt-2" id="active-custom-categories">
                            @forelse($customCategoryOptions as $customCategory)
                                @php $color = $customCategory->category_color ?: '#e2e8f0'; @endphp
                                <span class="badge custom-category-badge"
                                      data-custom-id="{{ $customCategory->category_id }}"
                                      style="background-color: {{ $color }}; color: #1f2933;">
                                    <i class="ti-tag mr-1"></i> {{ $customCategory->category_name }}
                                </span>
                            @empty
                                <span class="text-muted small">دسته‌بندی سفارشی ثبت نشده است.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">جستجوی سریع</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="filter_search" class="form-control" placeholder="نام کالا، کد، دسته" autocomplete="off">
                            <button class="btn btn-primary" id="btn-apply-filters"><i class="ti-search"></i></button>
                        </div>
                        <div class="quick-flags mt-2">
                            <span class="badge badge-outline log-flag" data-flag="critical">بحرانی</span>
                            <span class="badge badge-outline log-flag" data-flag="low-stock">کمبود</span>
                            <span class="badge badge-outline log-flag" data-flag="negative">منفی</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Actions Widget -->
        <div class="card custom-actions-wrapper mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">فعالیت‌های پیشنهادی</h5>
                        <small class="text-muted">برای مدیریت بهتر انبار، این اقدامات پیشنهاد می‌شود</small>
                    </div>
                    <button class="btn btn-link btn-sm p-0" id="btn-refresh-actions"><i class="ti-reload"></i> بروزرسانی</button>
                </div>
                <div class="row mt-3" id="custom-actions-container">
                    <div class="col-md-4 mb-3">
                        <div class="action-card skeleton"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="action-card skeleton"></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="action-card skeleton"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="warehouse-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="current-stock-tab" data-toggle="tab" href="#current-stock" role="tab">
                            <i class="ti-package"></i> موجودی فعلی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="expiry-tab" data-toggle="tab" href="#expiry" role="tab">
                            <i class="ti-alarm-clock"></i> هشدارهای انقضا
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab">
                            <i class="ti-shopping-cart"></i> گزارش فروش
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="outside-tab" data-toggle="tab" href="#outside" role="tab">
                            <i class="ti-alert"></i> خارج از موجودی
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="analytics-tab" data-toggle="tab" href="#analytics" role="tab">
                            <i class="ti-bar-chart"></i> تحلیل‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab">
                            <i class="ti-list"></i> لاگ تراکنش‌ها
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-4" id="warehouse-tab-content">
                    <!-- Current Stock Tab -->
                    <div class="tab-pane fade show active" id="current-stock" role="tabpanel">
                        @include('pages.reports.warehouse.components.current-stock')
                    </div>

                    <!-- Expiry Tab -->
                    <div class="tab-pane fade" id="expiry" role="tabpanel">
                        @include('pages.reports.warehouse.components.expiry-alerts')
                    </div>

                    <!-- Sales Tab -->
                    <div class="tab-pane fade" id="sales" role="tabpanel">
                        @include('pages.reports.warehouse.components.sales-report')
                    </div>

                    <!-- Outside Inventory Tab -->
                    <div class="tab-pane fade" id="outside" role="tabpanel">
                        @include('pages.reports.warehouse.components.outside-inventory')
                    </div>

                    <!-- Analytics Tab -->
                    <div class="tab-pane fade" id="analytics" role="tabpanel">
                        @include('pages.reports.warehouse.components.analytics')
                    </div>

                    <!-- Transactions Tab -->
                    <div class="tab-pane fade" id="transactions" role="tabpanel">
                        @include('pages.reports.warehouse.components.transactions-log')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Category Side Panel -->
<div id="custom-category-panel" class="custom-panel">
    <div class="custom-panel-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1"><i class="ti-tag"></i> دسته‌بندی‌های دلخواه</h5>
            <small class="text-muted">نام، رنگ، آیکن و بازه‌ی فعال‌سازی را شخصی‌سازی کنید</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-close-panel" aria-label="Close">
            <i class="ti-close"></i>
        </button>
    </div>
    <div class="custom-panel-body">
        <button class="btn btn-primary btn-sm mb-3" id="btn-new-custom-category">
            <i class="ti-plus"></i> دسته‌بندی جدید
        </button>
        <div id="custom-category-list" class="custom-category-list">
            <div class="text-center text-muted py-4">در حال بارگذاری...</div>
        </div>
    </div>
</div>

<!-- Custom Category Modal -->
<div class="modal fade" id="customCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right" role="document">
        <div class="modal-content">
            <form id="custom-category-form">
                <div class="modal-header">
                    <h5 class="modal-title">دسته‌بندی دلخواه</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="custom-category-id" name="category_id">
                    <div class="form-group">
                        <label>عنوان</label>
                        <input type="text" class="form-control" id="custom-category-name" name="category_name" required>
                    </div>
                    <div class="form-group">
                        <label>نوع</label>
                        <select class="form-control" id="custom-category-type" name="category_type" required>
                            <option value="item">کالا</option>
                            <option value="customer">مشتری</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>رنگ</label>
                        <input type="color" class="form-control form-control-color" id="custom-category-color" name="category_color" value="#5a9ba5">
                    </div>
                    <div class="form-group">
                        <label>آیکن (کلاس یا متن)</label>
                        <input type="text" class="form-control" id="custom-category-icon" name="category_icon" placeholder="مثال: ti-package یا 🔥">
                    </div>
                    <div class="form-group">
                        <label>تصویر (URL)</label>
                        <input type="text" class="form-control" id="custom-category-image" name="category_image" placeholder="https://example.com/image.png">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label>شروع بازه فعال</label>
                            <input type="date" class="form-control" id="custom-category-start-date" name="start_date">
                        </div>
                        <div class="form-group col-sm-6">
                            <label>پایان بازه فعال</label>
                            <input type="date" class="form-control" id="custom-category-end-date" name="end_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>توضیحات</label>
                        <textarea class="form-control" id="custom-category-description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Category Item Modal -->
<div class="modal fade" id="customCategoryItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="custom-category-item-form">
                <div class="modal-header">
                    <h5 class="modal-title">افزودن کالا به دسته‌بندی</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="item-category-id" name="custom_category_id">
                    <div class="form-group">
                        <label>شناسه کالا</label>
                        <input type="number" class="form-control" id="item-inventory-id" name="inventory_id" required>
                        <small class="text-muted">برای جستجوی دقیق‌تر از شناسه یا کد کالا استفاده کنید</small>
                    </div>
                    <div class="form-group">
                        <label>نام مستعار</label>
                        <input type="text" class="form-control" id="item-alias-name" name="alias_name">
                    </div>
                    <div class="form-group">
                        <label>رنگ مستعار</label>
                        <input type="color" class="form-control form-control-color" id="item-alias-color" name="alias_color" value="#5a9ba5">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label>شروع</label>
                            <input type="date" class="form-control" id="item-start-date" name="start_date">
                        </div>
                        <div class="form-group col-sm-6">
                            <label>پایان</label>
                            <input type="date" class="form-control" id="item-end-date" name="end_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>تصویر مستعار (URL)</label>
                        <input type="text" class="form-control" id="item-alias-image" name="alias_image">
                    </div>
                    <div class="form-group">
                        <label>یادداشت</label>
                        <textarea class="form-control" id="item-notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-success">افزودن</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inventory Alert Panel -->
<div id="inventory-alert-panel" class="custom-panel alert-panel">
    <div class="custom-panel-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1"><i class="ti-bell"></i> هشدارهای انبار</h5>
            <small class="text-muted">تنظیم هشدار برای انقضا و موجودی</small>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-close-panel" aria-label="Close">
            <i class="ti-close"></i>
        </button>
    </div>
    <div class="custom-panel-body">
        <div class="row mb-3 g-2">
            <div class="col-4">
                <div class="alert-summary-card bg-soft-primary text-center p-3 rounded">
                    <div class="text-muted small">کل هشدارها</div>
                    <h4 class="mb-0" id="alert-total-count">-</h4>
                </div>
            </div>
            <div class="col-4">
                <div class="alert-summary-card bg-soft-warning text-center p-3 rounded">
                    <div class="text-muted small">فعالسازی</div>
                    <h4 class="mb-0" id="alert-active-count">-</h4>
                </div>
            </div>
            <div class="col-4">
                <div class="alert-summary-card bg-soft-danger text-center p-3 rounded">
                    <div class="text-muted small">در انتظار اقدام</div>
                    <h4 class="mb-0" id="alert-critical-count">-</h4>
                </div>
            </div>
        </div>
        <button class="btn btn-primary btn-sm mb-3 w-100" id="btn-create-alert">
            <i class="ti-plus"></i> هشدار جدید
        </button>
        <div id="inventory-alerts-list" class="inventory-alert-list">
            <div class="text-muted text-center py-4">برای مشاهده، هشدارها را بارگذاری کنید.</div>
        </div>
    </div>
</div>

<!-- Inventory Alert Modal -->
<div class="modal fade" id="inventoryAlertModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="inventory-alert-form">
                <div class="modal-header">
                    <h5 class="modal-title">ثبت هشدار</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="alert-id" name="alert_id">
                    <div class="form-group">
                        <label>شناسه کالا (اختیاری برای هشدار کلی)</label>
                        <input type="number" class="form-control" id="alert-inventory-id" name="inventory_id" placeholder="مثال: 1024">
                    </div>
                    <div class="form-group">
                        <label>نوع هشدار</label>
                        <select class="form-control" id="alert-type" name="alert_type" required>
                            <option value="expiry">انقضا</option>
                            <option value="minimum">کمبود موجودی</option>
                            <option value="maximum">مازاد موجودی</option>
                            <option value="quantity">سقف موجودی</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>تعداد روز برای هشدار (برای انقضا)</label>
                        <input type="number" class="form-control" id="alert-threshold-days" name="threshold_days" min="0" placeholder="مثال: 7">
                    </div>
                    <div class="form-group">
                        <label>مقدار آستانه (برای موجودی)</label>
                        <input type="number" step="0.01" class="form-control" id="alert-threshold-value" name="threshold_value" placeholder="مثال: 50">
                    </div>
                    <div class="form-group">
                        <label>کانال‌های اطلاع‌رسانی</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="alert-email" name="alert_email" checked>
                            <label class="form-check-label" for="alert-email">ایمیل</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="alert-sms" name="alert_sms">
                            <label class="form-check-label" for="alert-sms">پیامک</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>آدرس‌های ایمیل (با کاما جدا کنید)</label>
                        <textarea class="form-control" id="alert-email-addresses" name="alert_email_addresses" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>شماره‌های موبایل (با کاما جدا کنید)</label>
                        <textarea class="form-control" id="alert-phone-numbers" name="alert_phone_numbers" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>وضعیت</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="alert-status" name="is_active" checked>
                            <label class="custom-control-label" for="alert-status">فعال</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-success">ذخیره هشدار</button>
                </div>
            </form>
        </div>
    </div>
</div>


