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
                        <li class="breadcrumb-item active">{{ cleanLang(__('lang.guarantee_letters')) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ cleanLang(__('lang.guarantee_letters')) }}</h4>
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
                            <i class="ti-receipt font-24 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_guarantee_letters')) }}</p>
                            <h4 class="my-1">{{ $stats['total_guarantees'] ?? 0 }}</h4>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.active_guarantees')) }}</p>
                            <h4 class="my-1">{{ $stats['active_guarantees'] ?? 0 }}</h4>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.expiring_soon')) }}</p>
                            <h4 class="my-1">{{ $stats['expiring_soon'] ?? 0 }}</h4>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_amount')) }}</p>
                            <h4 class="my-1 stats-total-amount">{{ formatCurrency($stats['total_amount'] ?? 0, 'IRR') }}</h4>
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
                                    placeholder="{{ cleanLang(__('lang.search_guarantee_letters')) }}"
                                    value="{{ request('search_query') }}">
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <!--filter button-->
                            @if(config('visibility.list_page_actions_filter_button'))
                            <button type="button" class="btn btn-outline-secondary js-toggle-side-panel"
                                data-target="sidepanel-filter-guarantee-letters">
                                <i class="ti-filter"></i> {{ cleanLang(__('lang.filter')) }}
                            </button>
                            @endif
                            <!--import button-->
                            <a href="{{ _url('/import/guarantee-letters') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_guarantee_letters')) }}
                            </a>
                            <!--clear column searches button-->
                            <button type="button" class="btn btn-outline-warning me-2 clear-column-searches">
                                <i class="ti-refresh"></i> {{ cleanLang(__('lang.clear_searches')) }}
                            </button>
                            <!--add button-->
                            @if(config('visibility.list_page_actions_add_button'))
                            <a href="{{ _url('/guarantee-letters/create') }}" class="btn btn-primary">
                                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_guarantee_letter')) }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guarantee Letters List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('pages.guarantee-letters.components.table.wrapper')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

