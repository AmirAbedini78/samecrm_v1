@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
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
                    <div class="row g-2 align-items-end">
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
                        <div class="col-md-8">
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
                    </div>

                    <div class="table-responsive mt-3">
                        <table id="belzona-inbounds-table" class="table table-striped table-hover table-bordered w-100 belzona-datatable-text">
                            <thead class="table-dark">
                                <tr>
                                    <th class="belzona-datatable-title">تاریخ ورود</th>
                                    <th class="belzona-datatable-title">محصول (شیت)</th>
                                    <th class="belzona-datatable-title">عنوان/توضیح پارت</th>
                                    <th class="belzona-datatable-title">تعداد ورود</th>
                                    <th class="belzona-datatable-title">جمع خروجی</th>
                                    <th class="belzona-datatable-title">مانده پارت</th>
                                    <th class="belzona-datatable-title">تعداد خروجی‌ها</th>
                                    <th class="belzona-datatable-title">شلف لایف (سال)</th>
                                    <th class="belzona-datatable-title">تاریخ انقضا / مانده</th>
                                    <th class="belzona-datatable-title">عملیات</th>
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
@endsection

@section('footerjs')
<script src="{{ url('public/js/core/belzona-inventory-inbounds.js') }}"></script>
@endsection

