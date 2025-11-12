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
                        <li class="breadcrumb-item active">{{ cleanLang(__('lang.sales')) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ cleanLang(__('lang.sales')) }}</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti-shopping-cart font-24 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_sales')) }}</p>
                            <h4 class="my-1">{{ $stats['total_sales'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti-check-circle font-24 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.completed_sales')) }}</p>
                            <h4 class="my-1">{{ $stats['completed_sales'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti-time font-24 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.pending_sales')) }}</p>
                            <h4 class="my-1">{{ $stats['pending_sales'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ti-money font-24 text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">مجموع مبلغ فروش</p>
                            <h4 class="my-1 stats-total-sales-amount">{{ formatCurrency($stats['total_sales_amount'] ?? 0, 'IRR') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!--search-->
                            @if(config('visibility.list_page_actions_search'))
                            <div class="list-page-actions-search">
                                <input type="text" name="search_query" id="search_query" class="form-control"
                                    placeholder="{{ cleanLang(__('lang.search_sales')) }}"
                                    value="{{ request('search_query') }}">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <!--filter button-->
                            @if(config('visibility.list_page_actions_filter_button'))
                            <button type="button" class="btn btn-outline-secondary js-toggle-side-panel"
                                data-target="sidepanel-filter-sales">
                                <i class="ti-filter"></i> {{ cleanLang(__('lang.filter')) }}
                            </button>
                            @endif
                            <!--import button-->
                            <a href="{{ _url('/import/sales') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_sales')) }}
                            </a>
                                <!--clear column searches button-->
                                <button type="button" class="btn btn-outline-warning me-2 clear-column-searches">
                                    <i class="ti-refresh"></i> {{ cleanLang(__('lang.clear_searches')) }}
                                </button>
                                <!--add button-->
                                @if(config('visibility.list_page_actions_add_button'))
                                <a href="{{ _url('/sales/create') }}" class="btn btn-primary">
                                    <i class="ti-plus"></i> {{ cleanLang(__('lang.add_sales_record')) }}
                                </a>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('pages.sales.components.table.wrapper')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection