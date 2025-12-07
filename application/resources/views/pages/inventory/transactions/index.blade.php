@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">گردش کالا</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">حسابداری</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">انبار</a></li>
                    <li class="breadcrumb-item active">گردش کالا</li>
                </ul>
            </div>
            <div class="col-md-8 col-sm-12 text-right">
                <a href="{{ route('inventory.transactions.create') }}" class="btn btn-primary">
                    <i class="ti-plus"></i> ایجاد تراکنش جدید
                </a>
                <a href="{{ route('inventory.transactions.import') }}" class="btn btn-info">
                    <i class="ti-upload"></i> وارد کردن از اکسل
                </a>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('pages.inventory.transactions.components.filter', ['inventories' => $inventories ?? []])

                        @include('pages.inventory.transactions.components.table', ['transactions' => $transactions ?? collect()])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script src="{{ asset('js/core/inventory-transactions.js') }}"></script>
@endsection

