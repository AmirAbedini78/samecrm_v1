/**
 * DataTables Implementation for Belzona Inventory
 */

$(document).ready(function() {
    if ($('#belzona-inventory-table').length) {
        var $table = $('#belzona-inventory-table');
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
                { data: 'invoice_number', name: 'invoice_number', title: 'شماره فاکتور' },
                { data: 'customer_name', name: 'customer_name', title: 'نام مشتری' },
                { data: 'notes', name: 'notes', title: 'توضیحات' },
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

        // reload on filter change
        $(document).on('keyup change', '.belzona-filter', function() {
            dt.ajax.reload();
        });
        $('#belzona-date-from, #belzona-date-to').on('change', function() {
            dt.ajax.reload();
        });

        // clear filters
        $('#belzona-clear-filters').on('click', function() {
            $('.belzona-filter').val('');
            $('#search_query').val('');
            dt.search('').draw();
        });

        // top search box (uses DataTables search)
        $('#search_query').on('keyup', function() {
            dt.search($(this).val()).draw();
        });
    }
});

