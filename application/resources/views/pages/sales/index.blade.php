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
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="ti-shopping-cart font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_sales')) }}</p>
                            <h4 class="my-1">{{ $stats['total_sales'] ?? 0 }}</h4>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.completed_sales')) }}</p>
                            <h4 class="my-1">{{ $stats['completed_sales'] ?? 0 }}</h4>
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
                                    <i class="ti-clock font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.pending_sales')) }}</p>
                            <h4 class="my-1">{{ $stats['pending_sales'] ?? 0 }}</h4>
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
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_revenue')) }}</p>
                            <h4 class="my-1">{{ formatCurrency($stats['total_revenue'] ?? 0, 'IRR') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.search_sales')) }}</label>
                                <input type="text" id="sales-search" class="form-control" 
                                       placeholder="{{ cleanLang(__('lang.search_sales')) }}" 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.sales_status')) }}</label>
                                <select id="sales-status-filter" class="form-control">
                                    <option value="">{{ cleanLang(__('lang.all_status')) }}</option>
                                    <option value="pending" {{ request('filter_sales_status') == 'pending' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.pending')) }}
                                    </option>
                                    <option value="completed" {{ request('filter_sales_status') == 'completed' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.completed')) }}
                                    </option>
                                    <option value="cancelled" {{ request('filter_sales_status') == 'cancelled' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.cancelled')) }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.document_type')) }}</label>
                                <select id="sales-document-type-filter" class="form-control">
                                    <option value="">{{ cleanLang(__('lang.all_types')) }}</option>
                                    <option value="sale" {{ request('filter_document_type') == 'sale' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.sale')) }}
                                    </option>
                                    <option value="invoice" {{ request('filter_document_type') == 'invoice' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.invoice')) }}
                                    </option>
                                    <option value="quote" {{ request('filter_document_type') == 'quote' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.quote')) }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.date_from')) }}</label>
                                <input type="date" id="sales-date-from" class="form-control" 
                                       value="{{ request('filter_document_date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="btn-group me-2">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                        data-toggle="dropdown" aria-expanded="false">
                                    <i class="ti-settings"></i> {{ cleanLang(__('lang.column_settings')) }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><h6 class="dropdown-header">{{ cleanLang(__('lang.show_hide_columns')) }}</h6></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="1" checked> {{ cleanLang(__('lang.id')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="2" checked> {{ cleanLang(__('lang.document_number')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="3" checked> {{ cleanLang(__('lang.customer_name')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="4" checked> {{ cleanLang(__('lang.product_name')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="5" checked> {{ cleanLang(__('lang.main_quantity')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="6" checked> {{ cleanLang(__('lang.base_price')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="7" checked> {{ cleanLang(__('lang.base_net_amount')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="8" checked> {{ cleanLang(__('lang.sales_status')) }}
                                    </label></li>
                                </ul>
                            </div>
                            <a href="{{ _url('/import/sales') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_sales')) }}
                            </a>
                            <a href="{{ _url('/sales/create') }}" class="btn btn-primary">
                                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_sales_record')) }}
                            </a>
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
                    <div id="sales-table-wrapper">
                        @include('pages.sales.components.table.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSales(id) {
    if (confirm('{{ cleanLang(__('lang.confirm_delete')) }}')) {
        fetch('/sales/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('{{ cleanLang(__('lang.error_occurred')) }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ cleanLang(__('lang.error_occurred')) }}');
        });
    }
}

// Column visibility toggle
$(document).ready(function() {
    $('.column-toggle').on('change', function() {
        var column = $(this).data('column');
        var isChecked = $(this).is(':checked');
        
        if (isChecked) {
            $('#sales-table th:nth-child(' + column + '), #sales-table td:nth-child(' + column + ')').show();
        } else {
            $('#sales-table th:nth-child(' + column + '), #sales-table td:nth-child(' + column + ')').hide();
        }
    });
    
    // Sortable columns
    $('#sales-table th').click(function() {
        var column = $(this).index();
        var currentOrder = $(this).data('order') || 'asc';
        var newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        
        // Column mapping for sales
        var columnMap = {
            1: 'sales_id',
            2: 'document_number',
            3: 'customer_name', 
            4: 'product_name',
            5: 'main_quantity',
            6: 'base_price',
            7: 'base_net_amount',
            8: 'sales_status'
        };
        
        var orderBy = columnMap[column] || 'created_at';
        
        // Remove sort indicators from all columns
        $('#sales-table th').removeClass('sort-asc sort-desc');
        
        // Add sort indicator to current column
        $(this).addClass('sort-' + newOrder);
        $(this).data('order', newOrder);
        
        // Get current URL parameters
        var url = new URL(window.location);
        url.searchParams.set('orderby', orderBy);
        url.searchParams.set('sortorder', newOrder);
        
        // Redirect to sorted URL
        window.location.href = url.toString();
    });
    
    // Live search
    var searchTimeout;
    $('#sales-search').on('input', function() {
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val();
        
        searchTimeout = setTimeout(function() {
            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                loadSalesData();
            }
        }, 500);
    });
    
    // Filter changes
    $('#sales-status-filter, #sales-document-type-filter, #sales-date-from').on('change', function() {
        loadSalesData();
    });
    
    function loadSalesData() {
        var search = $('#sales-search').val();
        var status = $('#sales-status-filter').val();
        var documentType = $('#sales-document-type-filter').val();
        var dateFrom = $('#sales-date-from').val();
        
        $.ajax({
            url: '{{ _url("/sales") }}',
            method: 'GET',
            data: {
                search: search,
                filter_sales_status: status,
                filter_document_type: documentType,
                filter_document_date_from: dateFrom
            },
            success: function(response) {
                // Extract table content from response
                var tableContent = $(response).find('#sales-table-wrapper').html();
                $('#sales-table-wrapper').html(tableContent);
            },
            error: function() {
                console.log('Error loading sales data');
            }
        });
    }
});
</script>
@endsection

@section('footerjs')
<style>
.sort-asc::after {
    content: ' ↑';
    color: #007bff;
}

.sort-desc::after {
    content: ' ↓';
    color: #007bff;
}

#sales-table th {
    cursor: pointer;
    user-select: none;
}

#sales-table th:hover {
    background-color: #f8f9fa;
}
</style>
@endsection