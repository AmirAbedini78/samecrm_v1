@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">گزارشگیری انبار بلزونا</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/reports">گزارشات</a></li>
                    <li class="breadcrumb-item active">گزارشگیری انبار بلزونا</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @include('pages.reports.belzona-inventory.wrapper')
    </div>
</div>
@endsection

