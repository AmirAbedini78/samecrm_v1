/**
 * DataTables Implementation for Inventory
 * Professional table with advanced features
 */

$(document).ready(function() {
    // Initialize DataTable for inventory
    if ($('#inventory-table').length) {
        var inventoryTable = $('#inventory-table').DataTable({
            // Basic configuration
            processing: true,
            serverSide: true,
            ajax: {
                url: '/inventory',
                type: 'GET',
                data: function(d) {
                    // Add custom parameters
                    d.action = 'datatables';
                    d.column_search = {};
                    
                    // Add column-specific searches
                    $('.column-search-input').each(function() {
                        var column = $(this).data('column');
                        var value = $(this).val();
                        if (value) {
                            d.column_search[column] = value;
                        }
                    });
                }
            },
            
            // Columns configuration
            columns: [
                {
                    data: 'inventory_id',
                    name: 'inventory_id',
                    title: 'ID',
                    orderable: true,
                    searchable: true,
                    width: '5%'
                },
                {
                    data: 'product_name',
                    name: 'product_name',
                    title: 'نام محصول',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'category',
                    name: 'category',
                    title: 'دسته‌بندی',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'warehouse',
                    name: 'warehouse',
                    title: 'انبار',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    title: 'تعداد',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'unit_price',
                    name: 'unit_price',
                    title: 'قیمت واحد',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return '<span class="currency">' + formatCurrency(data, 'IRR') + '</span>';
                    }
                },
                {
                    data: 'total_value',
                    name: 'total_value',
                    title: 'ارزش کل',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return '<span class="currency">' + formatCurrency(data, 'IRR') + '</span>';
                    }
                },
                {
                    data: 'supplier',
                    name: 'supplier',
                    title: 'تامین‌کننده',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    title: 'تاریخ ایجاد',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return formatDate(data);
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    title: 'عملیات',
                    orderable: false,
                    searchable: false,
                    width: '10%',
                    render: function(data, type, row) {
                        return generateActionButtons(row);
                    }
                }
            ],
            
            // Language configuration
            language: {
                // local i18n (avoid CDN load errors)
                url: '/public/js/datatables-persian.json'
            },
            
            // Advanced features
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "همه"]],
            
            // Styling
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' +
                 '<"row"<"col-sm-12"B>>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="ti-file-excel"></i> خروجی Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="ti-file-pdf"></i> خروجی PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="ti-printer"></i> چاپ',
                    className: 'btn btn-info btn-sm'
                },
                {
                    text: '<i class="ti-refresh"></i> بروزرسانی',
                    className: 'btn btn-primary btn-sm',
                    action: function(e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ],
            
            // Search configuration
            search: {
                regex: true,
                smart: true
            },
            
            // Order configuration
            order: [[0, 'desc']],
            
            // Callbacks
            drawCallback: function(settings) {
                // Re-initialize tooltips
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        
        // Column-specific search functionality
        $('.column-search-input').on('keyup', function() {
            var column = $(this).data('column');
            var value = $(this).val();
            
            // Update DataTable search
            inventoryTable.column(column + ':name').search(value).draw();
        });
        
        // Clear search functionality
        $('.clear-column-searches').on('click', function() {
            $('.column-search-input').val('');
            inventoryTable.search('').columns().search('').draw();
        });
    }
});

// Helper functions
function formatCurrency(amount, currency) {
    if (currency === 'IRR') {
        return new Intl.NumberFormat('fa-IR', {
            style: 'currency',
            currency: 'IRR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
    return amount;
}

function formatDate(dateString) {
    var date = new Date(dateString);
    return date.toLocaleDateString('fa-IR');
}

function generateActionButtons(row) {
    var buttons = '';
    
    // View button
    buttons += '<a href="/inventory/' + row.inventory_id + '" class="btn btn-sm btn-primary" title="مشاهده">';
    buttons += '<i class="ti-eye"></i>';
    buttons += '</a> ';
    
    // Edit button
    buttons += '<a href="/inventory/' + row.inventory_id + '/edit" class="btn btn-sm btn-warning" title="ویرایش">';
    buttons += '<i class="ti-pencil"></i>';
    buttons += '</a> ';
    
    // Delete button
    buttons += '<button class="btn btn-sm btn-danger delete-item" data-id="' + row.inventory_id + '" title="حذف">';
    buttons += '<i class="ti-trash"></i>';
    buttons += '</button>';
    
    return buttons;
}
