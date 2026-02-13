/**
 * DataTables Implementation for Belzona Inventory
 */

function initBelzonaInventoryDataTable() {
    if (!$('#belzona-inventory-table').length) {
        return;
    }

    var $table = $('#belzona-inventory-table');

    // reset if already initialized
    if ($.fn.DataTable && $.fn.DataTable.isDataTable($table)) {
        $table.DataTable().destroy();
        $table.find('tbody').empty();
    }

    var baseUrl = (window.NX && NX.site_url) ? String(NX.site_url).replace(/\/$/, '') : '';
    function nxUrl(path) {
        path = String(path || '').replace(/^\//, '');
        return baseUrl ? (baseUrl + '/' + path) : ('/' + path);
    }

    function getColumnSearchPayload() {
        var payload = {};
        $('.belzona-filter').each(function() {
            var key = $(this).data('filter');
            var val = $(this).val();
            if (val !== undefined && val !== null && String(val).trim() !== '') {
                payload[key] = val;
            }
        });
        return payload;
    }

    var dt = $table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: nxUrl('belzona-inventory'),
                type: 'GET',
                // keep default DataTables request params (draw/start/length/columns/order/search)
                // and just append our custom filters
                data: function(d) {
                    d.action = 'datatables';
                    d.column_search = getColumnSearchPayload();
                    d.filter_date_from = $('#belzona-date-from').val();
                    d.filter_date_to = $('#belzona-date-to').val();
                },
                error: function(xhr) {
                    // If server returns HTML (login page) or invalid JSON, DataTables can stay empty silently.
                    console.error('Belzona DataTables AJAX failed', xhr.status, xhr.responseText);
                }
            },
            columns: [
                { data: 'belzona_inventory_id', name: 'belzona_inventory_id', title: 'ID' },
                { data: 'sheet_name', name: 'sheet_name', title: 'محصول (نام شیت)' },
                { data: 'product_weight_raw', name: 'product_weight_raw', title: 'وزن' },
                { data: 'date_raw', name: 'date_raw', title: 'تاریخ' },
                { data: 'input', name: 'input', title: 'ورودی' },
                { data: 'output', name: 'output', title: 'خروجی' },
                { data: 'balance', name: 'balance', title: 'مانده' },
                { data: 'invoice_number', name: 'invoice_number', title: 'شماره فاکتور', render: function(d) {
                        if (!d) return '';
                        var safe = String(d)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                        return '<a href="javascript:void(0)" class="belzona-invoice-link" data-invoice-number="' + safe + '">' + safe + '</a>';
                    }
                },
                { data: 'customer_name', name: 'customer_name', title: 'نام مشتری' },
                { data: 'notes', name: 'notes', title: 'توضیحات' },
                { data: 'shelf_life_years', name: 'shelf_life_years', title: 'شلف لایف (سال)' },
                { data: 'remaining_shelf_life', name: 'remaining_shelf_life', title: 'تاریخ انقضا (شمسی) / مانده', render: function(remaining, type, row) {
                        if (type !== 'display') return remaining;
                        var expiry = (row.expiry_date_shamsi || row.expiry_date) ? String(row.expiry_date_shamsi || row.expiry_date) : '';
                        var rem = row.remaining_shelf_life ? String(row.remaining_shelf_life) : '—';
                        if (expiry && rem !== '—') return expiry + ' <small class="text-muted">(' + rem + ')</small>';
                        return rem;
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    title: 'عملیات',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var id = row.belzona_inventory_id;
                        var buttons = '';
                        buttons += '<a href="' + nxUrl('belzona-inventory/' + id) + '" class="btn btn-sm btn-primary" title="مشاهده"><i class="ti-eye"></i></a> ';
                        buttons += '<a href="' + nxUrl('belzona-inventory/' + id + '/edit') + '" class="btn btn-sm btn-warning" title="ویرایش"><i class="ti-pencil"></i></a> ';
                        return buttons;
                    }
                }
            ],
            language: {
                // local i18n (avoid CDN load errors)
                url: nxUrl('public/js/datatables-persian.json')
            },
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']]
        });

    window.belzonaInventoryDt = dt;

    // reload on filter change (namespace to prevent duplicates)
    $(document)
        .off('keyup.belzonaDt change.belzonaDt', '.belzona-filter')
        .on('keyup.belzonaDt change.belzonaDt', '.belzona-filter', function() {
            dt.ajax.reload();
        });
    $('#belzona-date-from, #belzona-date-to')
        .off('change.belzonaDt')
        .on('change.belzonaDt', function() {
            dt.ajax.reload();
        });

    // clear filters
    $('#belzona-clear-filters')
        .off('click.belzonaDt')
        .on('click.belzonaDt', function() {
            $('.belzona-filter').val('');
            $('#search_query').val('');
            dt.search('').draw();
        });

    // top search box (uses DataTables search)
    $('#search_query')
        .off('keyup.belzonaDt')
        .on('keyup.belzonaDt', function() {
            dt.search($(this).val()).draw();
        });
}

// export for AJAX-loaded report tabs
window.initBelzonaInventoryDataTable = initBelzonaInventoryDataTable;

$(document).ready(function() {
    if (window.__belzonaInventoryAutoInitDisabled) {
        return;
    }
    initBelzonaInventoryDataTable();
});

