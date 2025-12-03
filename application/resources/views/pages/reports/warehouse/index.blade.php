@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">گزارش انبار</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/reports">گزارشات</a></li>
                    <li class="breadcrumb-item active">گزارش انبار</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @include('pages.reports.warehouse.wrapper')
    </div>
</div>
@endsection



