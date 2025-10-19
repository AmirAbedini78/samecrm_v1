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
                        <li class="breadcrumb-item active">{{ cleanLang(__('lang.inventory')) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ cleanLang(__('lang.inventory')) }}</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="ti-package font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_items')) }}</p>
                            <h4 class="my-1">{{ $stats['total_items'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-soft">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i class="ti-check font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.active_items')) }}</p>
                            <h4 class="my-1">{{ $stats['active_items'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i class="ti-alert font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.low_stock')) }}</p>
                            <h4 class="my-1">{{ $stats['low_stock'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-soft">
                                <span class="avatar-title rounded-circle bg-info">
                                    <i class="ti-money font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_value')) }}</p>
                            <h4 class="my-1">{{ formatCurrency($stats['total_value'] ?? 0, 'IRR') }}</h4>
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
                                    placeholder="{{ cleanLang(__('lang.search_inventory')) }}"
                                    value="{{ request('search_query') }}">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <!--filter button-->
                            @if(config('visibility.list_page_actions_filter_button'))
                            <button type="button" class="btn btn-outline-secondary js-toggle-side-panel"
                                data-target="sidepanel-filter-inventory">
                                <i class="ti-filter"></i> {{ cleanLang(__('lang.filter')) }}
                            </button>
                            @endif
                            <!--import button-->
                            <a href="{{ _url('/import/inventory') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_inventory')) }}
                            </a>
                                <!--clear column searches button -->
                                <button type="button" class="btn btn-outline-warning me-2 clear-column-searches">
                                    <i class="ti-refresh"></i> {{ cleanLang(__('lang.clear_searches')) }}
                                </button>
                                <!--add button-->
                                @if(config('visibility.list_page_actions_add_button'))
                                <a href="{{ _url('/inventory/create') }}" class="btn btn-primary">
                                    <i class="ti-plus"></i> {{ cleanLang(__('lang.add_inventory_item')) }}
                                </a>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('pages.inventory.components.table.wrapper')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection