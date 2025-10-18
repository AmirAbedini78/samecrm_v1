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

    <!-- Search & Filter -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.search_inventory')) }}</label>
                                <input type="text" id="inventory-search" class="form-control" 
                                       placeholder="{{ cleanLang(__('lang.search_inventory')) }}" 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.status')) }}</label>
                                <select id="inventory-status-filter" class="form-control">
                                    <option value="">{{ cleanLang(__('lang.all_status')) }}</option>
                                    <option value="active" {{ request('filter_inventory_status') == 'active' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.active')) }}
                                    </option>
                                    <option value="inactive" {{ request('filter_inventory_status') == 'inactive' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.inactive')) }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ cleanLang(__('lang.minimum_stock')) }}</label>
                                <select id="inventory-stock-filter" class="form-control">
                                    <option value="">{{ cleanLang(__('lang.all_items')) }}</option>
                                    <option value="low_stock" {{ request('filter_stock') == 'low_stock' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.low_stock')) }}
                                    </option>
                                    <option value="out_of_stock" {{ request('filter_stock') == 'out_of_stock' ? 'selected' : '' }}>
                                        {{ cleanLang(__('lang.out_of_stock')) }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
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
                                        <input type="checkbox" class="column-toggle" data-column="2" checked> {{ cleanLang(__('lang.inventory_name')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="3" checked> {{ cleanLang(__('lang.inventory_code')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="4" checked> {{ cleanLang(__('lang.current_quantity')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="5" checked> {{ cleanLang(__('lang.current_avg_price')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="6" checked> {{ cleanLang(__('lang.current_amount')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="7" checked> {{ cleanLang(__('lang.minimum_stock')) }}
                                    </label></li>
                                    <li><label class="dropdown-item">
                                        <input type="checkbox" class="column-toggle" data-column="8" checked> {{ cleanLang(__('lang.status')) }}
                                    </label></li>
                                </ul>
                            </div>
                            <a href="{{ _url('/import/inventory') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_inventory')) }}
                            </a>
                            <a href="{{ _url('/inventory/create') }}" class="btn btn-primary">
                                <i class="ti-plus"></i> {{ cleanLang(__('lang.add_inventory_item')) }}
                            </a>
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
                    <div id="inventory-table-wrapper">
                        @include('pages.inventory.components.table.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteInventory(id) {
    if (confirm('{{ cleanLang(__('lang.confirm_delete')) }}')) {
        fetch('/inventory/' + id, {
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
            $('#inventory-table th:nth-child(' + column + '), #inventory-table td:nth-child(' + column + ')').show();
        } else {
            $('#inventory-table th:nth-child(' + column + '), #inventory-table td:nth-child(' + column + ')').hide();
        }
    });
    
    // Sortable columns
    $('#inventory-table th').click(function() {
        var column = $(this).index();
        var currentOrder = $(this).data('order') || 'asc';
        var newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        
        // Column mapping for inventory
        var columnMap = {
            1: 'inventory_id',
            2: 'inventory_name', 
            3: 'inventory_code',
            4: 'current_quantity',
            5: 'current_avg_price',
            6: 'current_amount',
            7: 'minimum_stock',
            8: 'inventory_status'
        };
        
        var orderBy = columnMap[column] || 'created_at';
        
        // Remove sort indicators from all columns
        $('#inventory-table th').removeClass('sort-asc sort-desc');
        
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
    $('#inventory-search').on('input', function() {
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val();
        
        searchTimeout = setTimeout(function() {
            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                loadInventoryData();
            }
        }, 500);
    });
    
    // Filter changes
    $('#inventory-status-filter, #inventory-stock-filter').on('change', function() {
        loadInventoryData();
    });
    
    function loadInventoryData() {
        var search = $('#inventory-search').val();
        var status = $('#inventory-status-filter').val();
        var stock = $('#inventory-stock-filter').val();
        
        $.ajax({
            url: '{{ _url("/inventory") }}',
            method: 'GET',
            data: {
                search: search,
                filter_inventory_status: status,
                filter_stock: stock
            },
            success: function(response) {
                // Extract table content from response
                var tableContent = $(response).find('#inventory-table-wrapper').html();
                $('#inventory-table-wrapper').html(tableContent);
            },
            error: function() {
                console.log('Error loading inventory data');
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

#inventory-table th {
    cursor: pointer;
    user-select: none;
}

#inventory-table th:hover {
    background-color: #f8f9fa;
}
</style>
@endsection