@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ _url('/') }}">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ _url('/accounting') }}">{{ cleanLang(__('lang.accounting')) }}</a></li>
                        <li class="breadcrumb-item active">{{ cleanLang(__('lang.invoice_settlements')) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ cleanLang(__('lang.invoice_settlements')) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-file font-24 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_records')) }}</p>
                        <h4 class="my-1">{{ number_format($stats['total_records'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-money font-24 text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_net_amount')) }}</p>
                        <h4 class="my-1 stats-total-net-amount">{{ formatCurrency($stats['total_net'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-check-box font-24 text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_paid_amount')) }}</p>
                        <h4 class="my-1 stats-total-paid-amount">{{ formatCurrency($stats['total_paid'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-alert font-24 text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_balance_amount')) }}</p>
                        <h4 class="my-1 stats-total-balance-amount">{{ formatCurrency($stats['total_balance'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            @if(config('visibility.list_page_actions_search'))
                            <input type="text" name="search_query" id="search_query" class="form-control"
                                placeholder="{{ cleanLang(__('lang.search_invoice_settlements')) }}"
                                value="{{ request('search_query') }}">
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            @if(config('visibility.list_page_actions_filter_button'))
                            <button type="button" class="btn btn-outline-secondary js-toggle-side-panel"
                                data-target="sidepanel-filter-invoice-settlements">
                                <i class="ti-filter"></i> {{ cleanLang(__('lang.filter')) }}
                            </button>
                            @endif
                            <a href="{{ _url('/import/invoice-settlements') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_invoice_settlements')) }}
                            </a>
                            <button type="button" class="btn btn-outline-warning me-2 clear-column-searches">
                                <i class="ti-refresh"></i> {{ cleanLang(__('lang.clear_searches')) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('pages.invoice-settlements.components.table.wrapper')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.column-search-input').forEach(function (input) {
        input.addEventListener('change', function () {
            let column = this.dataset.column;
            let value = encodeURIComponent(this.value);
            let url = this.dataset.url + '?action=search&column_search_' + column + '=' + value;
            NX.ajaxUxRequest(url);
        });
    });

    const clearBtn = document.querySelector('.clear-column-searches');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            document.querySelectorAll('.column-search-input').forEach(function (input) {
                input.value = '';
            });
            NX.ajaxUxRequest('{{ urlResource('/invoice-settlements') }}?action=search');
        });
    }
});
</script>
@endsection

