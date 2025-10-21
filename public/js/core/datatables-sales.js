/**
 * DataTables Implementation for Sales
 * Professional table with advanced features
 */

$(document).ready(function() {
    // Initialize DataTable for sales
    if ($('#sales-table').length) {
        var salesTable = $('#sales-table').DataTable({
            // Basic configuration
            processing: true,
            serverSide: true,
            ajax: {
                url: '/sales',
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
                    data: 'sales_id',
                    name: 'sales_id',
                    title: 'ID',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'product_name',
                    name: 'product_name',
                    title: 'نام محصول',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                    title: 'نام مشتری',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'document_number',
                    name: 'document_number',
                    title: 'شماره سند',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'main_quantity',
                    name: 'main_quantity',
                    title: 'مقدار اصلی',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'base_sales_amount',
                    name: 'base_sales_amount',
                    title: 'مبلغ فروش',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return '<span class="currency">' + formatCurrency(data, 'IRR') + '</span>';
                    }
                },
                {
                    data: 'base_net_amount',
                    name: 'base_net_amount',
                    title: 'خالص',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return '<span class="currency">' + formatCurrency(data, 'IRR') + '</span>';
                    }
                },
                {
                    data: 'sales_status',
                    name: 'sales_status',
                    title: 'وضعیت',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return getStatusBadge(data);
                    }
                },
                {
                    data: 'document_date',
                    name: 'document_date',
                    title: 'تاریخ سند',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return formatDate(data);
                    }
                },
                {
                    data: 'creator',
                    name: 'creator',
                    title: 'ایجادکننده',
                    orderable: true,
                    searchable: true
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
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fa.json'
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
                
                // Update stats
                updateStats();
            }
        });
        
        // Column-specific search functionality
        $('.column-search-input').on('keyup', function() {
            var column = $(this).data('column');
            var value = $(this).val();
            
            // Update DataTable search
            salesTable.column(column + ':name').search(value).draw();
        });
        
        // Clear search functionality
        $('.clear-column-searches').on('click', function() {
            $('.column-search-input').val('');
            salesTable.search('').columns().search('').draw();
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

function getStatusBadge(status) {
    var badges = {
        'completed': '<span class="badge bg-success">تکمیل شده</span>',
        'pending': '<span class="badge bg-warning">در انتظار</span>',
        'cancelled': '<span class="badge bg-danger">لغو شده</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">نامشخص</span>';
}

function generateActionButtons(row) {
    var buttons = '';
    
    // View button
    buttons += '<a href="/sales/' + row.sales_id + '" class="btn btn-sm btn-primary" title="مشاهده">';
    buttons += '<i class="ti-eye"></i>';
    buttons += '</a> ';
    
    // Edit button
    buttons += '<a href="/sales/' + row.sales_id + '/edit" class="btn btn-sm btn-warning" title="ویرایش">';
    buttons += '<i class="ti-pencil"></i>';
    buttons += '</a> ';
    
    // Delete button
    buttons += '<button class="btn btn-sm btn-danger delete-item" data-id="' + row.sales_id + '" title="حذف">';
    buttons += '<i class="ti-trash"></i>';
    buttons += '</button>';
    
    return buttons;
}

function updateStats() {
    // Calculate stats from current filtered data
    var api = $('#sales-table').DataTable();
    var data = api.rows({search: 'applied'}).data();
    
    var totalSalesAmount = 0;
    var averageSalesAmount = 0;
    
    data.each(function(row) {
        totalSalesAmount += parseFloat(row.base_sales_amount) || 0;
    });
    
    if (data.count() > 0) {
        averageSalesAmount = totalSalesAmount / data.count();
    }
    
    // Update stats boxes
    $('.stats-total-sales-amount').text(formatCurrency(totalSalesAmount, 'IRR'));
    $('.stats-average-sales-amount').text(formatCurrency(averageSalesAmount, 'IRR'));
}
