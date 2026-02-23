@extends('layout.wrapper')

@section('content')
<div class="container-fluid" id="belzona-inventory-page" data-belzona-ajax-url="{{ url('belzona-inventory') }}">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ _url('/') }}">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ _url('/accounting') }}">{{ cleanLang(__('lang.accounting')) }}</a></li>
                        <li class="breadcrumb-item active">انبار بلزونا</li>
                    </ol>
                </div>
                <h4 class="page-title belzona-page-titles">انبار بلزونا</h4>
            </div>
        </div>
    </div>

    <!-- Page Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted belzona-page-text">
                        این صفحه فقط «لیست ورودی‌ها» را نمایش می‌دهد. برای گزارش‌ها و دیتاتیبل کامل، از «گزارشگیری انبار بلزونا» استفاده کنید.
                    </div>
                    <div class="text-end">
                        <a href="{{ _url('/import/belzona-inventory') }}" class="btn btn-success me-2 belzona-buttons">
                            <i class="ti-upload"></i> ایمپورت
                        </a>
                        <a href="{{ _url('/report/belzona-inventory') }}" class="btn btn-primary belzona-buttons">
                            <i class="ti-bar-chart"></i> گزارشگیری انبار بلزونا
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Manager - Inbound Batches (All Products) -->
    <div class="row">
        <div class="col-12">
            <div class="card border">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 belzona-datatable-title">
                            <i class="ti-import me-2"></i>
                            لیست ورودی‌ها (پارت‌ها)
                        </h5>
                        <small class="text-muted belzona-page-titles">نمایش پیش‌فرض بر اساس تاریخ ورود (جدیدترین)</small>
                    </div>
                    <div class="text-muted small belzona-page-text">
                        روی «مشاهده خروجی‌ها» کلیک کنید.
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2 align-items-end belzona-inbounds-filters">
                        <div class="col-lg-4">
                            <label class="form-label mb-1 belzona-page-titles">فیلتر محصول (نام شیت)</label>
                            <select id="belzona-inbounds-filter-sheet" class="form-control belzona-datatable-text">
                                <option value="">همه محصولات</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label mb-1 belzona-page-titles">از تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="belzona-inbounds-date-from" class="form-control persian-datepicker belzona-datatable-text" autocomplete="off" placeholder="مثلاً 1403/1/1">
                                <button type="button" class="btn btn-outline-secondary btn-sm belzona-buttons" onclick="showPersianDatePicker('belzona-inbounds-date-from')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label mb-1 belzona-page-titles">تا تاریخ</label>
                            <div class="input-group">
                                <input type="text" id="belzona-inbounds-date-to" class="form-control persian-datepicker belzona-datatable-text" autocomplete="off" placeholder="مثلاً 1403/12/29">
                                <button type="button" class="btn btn-outline-secondary btn-sm belzona-buttons" onclick="showPersianDatePicker('belzona-inbounds-date-to')">
                                    <i class="ti-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 text-end">
                            <button type="button" id="belzona-inbounds-refresh" class="btn btn-primary belzona-buttons">
                                <i class="ti-reload"></i> بروزرسانی
                            </button>
                            <button type="button" id="belzona-inbounds-clear" class="btn btn-outline-secondary ms-2 belzona-buttons">
                                <i class="ti-eraser"></i> پاک کردن
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3 g-3">
                        <div class="col-md-4">
                            <div class="card border mb-0">
                                <div class="card-body">
                                    <div class="text-muted belzona-page-text">جمع ورودی‌ها (بازه انتخابی)</div>
                                    <div class="h4 mb-0 belzona-datatable-text" id="belzona-inbounds-sum">-</div>
                                    <small class="text-muted belzona-page-text" id="belzona-inbounds-count">-</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border mb-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted belzona-page-text">آخرین پارت ورود</div>
                                        <div class="h5 mb-0 belzona-page-titles" id="belzona-latest-inbound-title">-</div>
                                        <small class="text-muted belzona-page-text" id="belzona-latest-inbound-meta">-</small>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-success belzona-buttons" id="belzona-latest-inbound-open" disabled>
                                            <i class="ti-eye"></i> مشاهده خروجی‌ها
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border mb-0">
                                <div class="card-body">
                                    <div class="text-muted belzona-page-text">آخرین مانده خروجی‌های پارت</div>
                                    <div class="h4 mb-0 belzona-datatable-text" id="belzona-last-outbound-balance">-</div>
                                    <small class="text-muted belzona-page-text">ماندهٔ آخرین رکورد خروجی در آخرین پارت ورود</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تب‌بندی -->
                    <ul class="nav nav-tabs mt-3 belzona-page-titles" id="belzona-main-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="belzona-tab-inbounds" data-toggle="tab" href="#belzona-pane-inbounds" role="tab">ورودها</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="belzona-tab-outputs" data-toggle="tab" href="#belzona-pane-outputs" role="tab">خروجی‌ها</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="belzona-tab-expiry" data-toggle="tab" href="#belzona-pane-expiry" role="tab">تاریخ انقضا</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2" id="belzona-main-tabs-content">
                        <!-- تب ورودها (بدون fade برای جلوگیری از مشکل DataTables در تب‌های پنهان) -->
                        <div class="tab-pane show active" id="belzona-pane-inbounds" role="tabpanel">
                            <div class="table-responsive">
                                <table id="belzona-inbounds-table" class="table table-striped table-hover table-bordered w-100 belzona-datatable-text" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="belzona-datatable-title">تاریخ ورود</th>
                                            <th class="belzona-datatable-title">محصول</th>
                                            <th class="belzona-datatable-title">عنوان</th>
                                            <th class="belzona-datatable-title">تعداد ورود</th>
                                            <th class="belzona-datatable-title">COC ها</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- تب خروجی‌ها — از فیلترهای بالای صفحه استفاده می‌کند -->
                        <div class="tab-pane" id="belzona-pane-outputs" role="tabpanel">
                            <div class="table-responsive">
                                <table id="belzona-inventory-outputs-table" class="table table-striped table-bordered table-hover w-100 belzona-datatable-text" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>محصول</th>
                                            <th>وزن</th>
                                            <th>تاریخ</th>
                                            <th>ورودی</th>
                                            <th>خروجی</th>
                                            <th>مانده</th>
                                            <th>فاکتور</th>
                                            <th>مشتری</th>
                                            <th>توضیحات</th>
                                            <th>شلف لایف</th>
                                            <th>تاریخ انقضا</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- تب تاریخ انقضا -->
                        <div class="tab-pane" id="belzona-pane-expiry" role="tabpanel">
                            <div class="table-responsive">
                                <table id="belzona-expiry-table" class="table table-striped table-bordered table-hover w-100 belzona-datatable-text" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>نام محصول</th>
                                            <th>تاریخ ورود</th>
                                            <th>شلف لایف (سال)</th>
                                            <th>تاریخ انقضا</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script src="{{ url('public/js/core/belzona-inventory-inbounds.js') }}"></script>
@endsection

