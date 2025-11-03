@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">{{ cleanLang(__('lang.sales')) }} - {{ cleanLang(__('lang.reports')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/reports">{{ cleanLang(__('lang.reports')) }}</a></li>
                    <li class="breadcrumb-item active">مقایسه بازه‌های تاریخ</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @include('pages.reports.sales.comparison-wrapper', ['report' => $report ?? []])
            </div>
        </div>
@endsection
