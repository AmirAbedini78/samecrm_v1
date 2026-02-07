/**
 * Belzona Inventory - Inbounds (main page)
 * Keeps the main "Inbound parts list" + outbounds modal (commonModal).
 */

// Persian date picker (simple dialog, same UX as sales reports)
function showPersianDatePicker(inputId) {
    var currentValue = $('#' + inputId).val();
    var year = 1403, month = 1, day = 1;

    if (currentValue && /^\d{4}\/\d{1,2}\/\d{1,2}$/.test(currentValue)) {
        var parts = currentValue.split('/');
        year = parseInt(parts[0], 10);
        month = parseInt(parts[1], 10);
        day = parseInt(parts[2], 10);
    }

    var persianMonths = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر',
        'مرداد', 'شهریور', 'مهر', 'آبان',
        'آذر', 'دی', 'بهمن', 'اسفند'
    ];

    var yearOptions = '';
    for (var i = 1398; i <= 1410; i++) {
        yearOptions += '<option value="' + i + '"' + (i === year ? ' selected' : '') + '>' + i + '</option>';
    }

    var monthOptions = '';
    for (var m = 1; m <= 12; m++) {
        monthOptions += '<option value="' + m + '"' + (m === month ? ' selected' : '') + '>' + persianMonths[m - 1] + '</option>';
    }

    var dayOptions = '';
    var daysInMonth = month <= 6 ? 31 : 30;
    for (var d = 1; d <= daysInMonth; d++) {
        dayOptions += '<option value="' + d + '"' + (d === day ? ' selected' : '') + '>' + d + '</option>';
    }

    var dialog = '' +
        '<div id="datePickerDialog" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);' +
        ' background: white; border: 2px solid #ccc; border-radius: 8px; padding: 20px; z-index: 1049;' +
        ' box-shadow: 0 4px 8px rgba(0,0,0,0.3); min-width: 320px;">' +
        '  <h5 style="margin-bottom: 15px;">انتخاب تاریخ شمسی</h5>' +
        '  <div style="display: flex; gap: 10px; margin-bottom: 15px;">' +
        '    <div><label style="display:block;margin-bottom:5px;">سال:</label>' +
        '      <select id="picker-year" style="padding:5px;border:1px solid #ccc;border-radius:4px;">' + yearOptions + '</select>' +
        '    </div>' +
        '    <div><label style="display:block;margin-bottom:5px;">ماه:</label>' +
        '      <select id="picker-month" style="padding:5px;border:1px solid #ccc;border-radius:4px;">' + monthOptions + '</select>' +
        '    </div>' +
        '    <div><label style="display:block;margin-bottom:5px;">روز:</label>' +
        '      <select id="picker-day" style="padding:5px;border:1px solid #ccc;border-radius:4px;">' + dayOptions + '</select>' +
        '    </div>' +
        '  </div>' +
        '  <div style="text-align:center;">' +
        '    <button type="button" onclick="confirmDate(\'' + inputId + '\')" style="background:#007bff;color:white;border:none;padding:8px 16px;border-radius:4px;margin-right:10px;cursor:pointer;">تأیید</button>' +
        '    <button type="button" onclick="cancelDate()" style="background:#6c757d;color:white;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;">لغو</button>' +
        '  </div>' +
        '</div>' +
        '<div id="datePickerOverlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1048;"></div>';

    $('#datePickerDialog, #datePickerOverlay').remove();
    $('body').append(dialog);

    $('#picker-month').off('change.belzonaPicker').on('change.belzonaPicker', function () {
        var selectedMonth = parseInt($(this).val(), 10);
        var dim = selectedMonth <= 6 ? 31 : 30;
        var daySelect = $('#picker-day');
        var currentDay = parseInt(daySelect.val(), 10);

        daySelect.empty();
        for (var j = 1; j <= dim; j++) {
            daySelect.append('<option value="' + j + '"' + (j === Math.min(currentDay, dim) ? ' selected' : '') + '>' + j + '</option>');
        }
    });
}

function confirmDate(inputId) {
    var year = $('#picker-year').val();
    var month = $('#picker-month').val();
    var day = $('#picker-day').val();
    $('#' + inputId).val(year + '/' + month + '/' + day).trigger('change');
    $('#datePickerDialog, #datePickerOverlay').remove();
}

function cancelDate() {
    $('#datePickerDialog, #datePickerOverlay').remove();
}

$(document).ready(function () {
    if (!$('#belzona-inbounds-table').length) {
        return;
    }

    var baseUrl = (window.NX && NX.site_url) ? String(NX.site_url).replace(/\/$/, '') : '';
    function nxUrl(path) {
        path = String(path || '').replace(/^\//, '');
        return baseUrl ? (baseUrl + '/' + path) : ('/' + path);
    }

    function fmtNumber(v) {
        try {
            return new Intl.NumberFormat('fa-IR').format(parseFloat(v || 0));
        } catch (e) {
            return v;
        }
    }

    function escapeHtml(s) {
        return String(s || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderInvoiceLink(invoiceNumber) {
        if (!invoiceNumber) return '';
        var safe = escapeHtml(invoiceNumber);
        return '<a href="javascript:void(0)" class="belzona-invoice-link" data-invoice-number="' + safe + '">' + safe + '</a>';
    }

    function openInvoiceModalByNumber(invoiceNumber) {
        if (!invoiceNumber) return;

        $.get(nxUrl('belzona-inventory'), {
            action: 'resolve_invoice',
            invoice_number: invoiceNumber
        }, function (res) {
            if (!res || !res.success || !res.data || !res.data.bill_invoiceid) {
                $('#commonModalContainer').removeClass('modal-xl');
                $('#commonModalTitle').text('فاکتور');
                $('#commonModalBody').html('<div class="alert alert-warning mb-0">فاکتور یافت نشد.</div>');
                $('#commonModalFooter').hide();
                $('#commonModal').modal('show');
                return;
            }

            var d = res.data;
            // Use the system ajax modal loader to open invoice edit modal
            var $a = $('<a href="javascript:void(0)"></a>');
            $a.addClass('actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button');
            $a.attr('data-toggle', 'modal');
            $a.attr('data-target', '#commonModal');
            $a.attr('data-url', d.edit_url);
            $a.attr('data-loading-target', 'commonModalBody');
            $a.attr('data-modal-title', 'فاکتور ' + (d.formatted_bill_invoiceid || invoiceNumber));
            $a.attr('data-action-url', nxUrl('invoices/' + d.bill_invoiceid + '?ref=belzona'));
            $a.attr('data-action-method', 'PUT');
            $('body').append($a);
            $a.trigger('click');
            setTimeout(function () { $a.remove(); }, 0);
        }).fail(function () {
            $('#commonModalContainer').removeClass('modal-xl');
            $('#commonModalTitle').text('فاکتور');
            $('#commonModalBody').html('<div class="alert alert-danger mb-0">خطا در دریافت اطلاعات فاکتور.</div>');
            $('#commonModalFooter').hide();
            $('#commonModal').modal('show');
        });
    }

    // Fix stuck/double Bootstrap backdrops (prevents "page stays dark" bugs)
    function nxFixModalBackdrops() {
        if ($('.modal.show').length === 0) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            return;
        }
        var $backs = $('.modal-backdrop');
        if ($backs.length > 1) {
            $backs.slice(0, -1).remove();
        }
    }

    // Modal helpers (outbounds of a specific inbound)
    // Use the system built-in common modal to avoid theme/z-index conflicts.
    function openOutboundsModal(sheetName, inboundRowNumber) {
        if (!sheetName || !inboundRowNumber) return;

        // ensure any persian datepicker overlay is closed (it can block clicks)
        $('#datePickerDialog, #datePickerOverlay').remove();

        $('#commonModalContainer').addClass('modal-xl');
        $('#commonModalTitle').html(
            'خروجی‌های پارت' +
            '<div class="text-muted" style="font-size:12px;margin-top:6px;">' +
            'محصول: <strong>' + sheetName + '</strong>' +
            ' | ردیف ورود: <strong>' + inboundRowNumber + '</strong>' +
            '</div>'
        );
        $('#commonModalBody').html(
            '<div class="alert alert-light border mb-3 text-muted">در حال بارگذاری...</div>' +
            '<div class="table-responsive">' +
            '<table class="table table-sm table-striped table-bordered mb-0">' +
            '<thead class="table-light"><tr>' +
            '<th>تاریخ</th><th>خروجی</th><th>مانده</th><th>فاکتور</th><th>مشتری</th><th>توضیحات</th>' +
            '</tr></thead>' +
            '<tbody id="belzona-commonmodal-tbody">' +
            '<tr><td colspan="6" class="text-muted">در حال بارگذاری...</td></tr>' +
            '</tbody></table></div>'
        );
        $('#commonModalFooter').hide();
        $('#commonModal').modal('show');
        setTimeout(nxFixModalBackdrops, 0);

        $.get(nxUrl('belzona-inventory'), {
            action: 'batch_outbounds',
            sheet_name: sheetName,
            inbound_row_number: inboundRowNumber,
            filter_date_from: $('#belzona-inbounds-date-from').val(),
            filter_date_to: $('#belzona-inbounds-date-to').val()
        }, function (res) {
            if (!res || !res.success) return;
            var d = res.data || {};
            var inbound = d.inbound || {};

            var meta =
                'ورود: ' + fmtNumber(inbound.input || 0) +
                ' | خروجی: ' + fmtNumber(inbound.out_total || 0) +
                ' | مانده: ' + fmtNumber(inbound.remaining || 0) +
                (inbound.date_raw ? (' | تاریخ ورود: ' + inbound.date_raw) : '');

            $('#commonModalBody .alert').html(
                '<strong>' + (inbound.label ? inbound.label : 'پارت ورود') + '</strong>' +
                '<div class="text-muted" style="margin-top:6px;">' + meta + '</div>'
            );

            var rows = d.outbounds || [];
            if (!rows.length) {
                $('#belzona-commonmodal-tbody').html('<tr><td colspan="6" class="text-muted">خروجی‌ای یافت نشد.</td></tr>');
                return;
            }

            var html = '';
            rows.forEach(function (r) {
                html += '<tr>' +
                    '<td>' + (r.date_raw || '') + '</td>' +
                    '<td>' + fmtNumber(r.output || 0) + '</td>' +
                    '<td>' + fmtNumber(r.balance || 0) + '</td>' +
                    '<td>' + renderInvoiceLink(r.invoice_number) + '</td>' +
                    '<td>' + (r.customer_name || '') + '</td>' +
                    '<td>' + (r.notes || '') + '</td>' +
                    '</tr>';
            });
            $('#belzona-commonmodal-tbody').html(html);
        });
    }

    // cleanup common modal sizing after close (only if we opened it as xl)
    $(document)
        .off('hidden.bs.modal.belzonaInbounds', '#commonModal')
        .on('hidden.bs.modal.belzonaInbounds', '#commonModal', function () {
            $('#commonModalContainer').removeClass('modal-xl');
            $('#commonModalFooter').show();
            nxFixModalBackdrops();
        });
    $(document)
        .off('shown.bs.modal.belzonaInbounds', '#commonModal')
        .on('shown.bs.modal.belzonaInbounds', '#commonModal', function () {
            nxFixModalBackdrops();
        });

    // Inbounds table (all products)
    var inboundsTable = null;
    function initInboundsTable() {
        if (inboundsTable) {
            inboundsTable.destroy();
            $('#belzona-inbounds-table tbody').empty();
        }

        inboundsTable = $('#belzona-inbounds-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            order: [[0, 'desc']],
            ajax: {
                url: nxUrl('belzona-inventory'),
                type: 'GET',
                data: function (d) {
                    d.action = 'inbound_datatables';
                    d.sheet_name = $('#belzona-inbounds-filter-sheet').val();
                    d.filter_date_from = $('#belzona-inbounds-date-from').val();
                    d.filter_date_to = $('#belzona-inbounds-date-to').val();
                },
                error: function (xhr) {
                    console.error('Belzona inbounds datatable ajax error', xhr.status, xhr.responseText);
                }
            },
            columns: [
                { data: 'date_raw', name: 'date_raw' },
                { data: 'sheet_name', name: 'sheet_name' },
                { data: 'inbound_label', name: 'inbound_label' },
                { data: 'input', name: 'input', render: function (d) { return fmtNumber(d); } },
                { data: 'out_total', name: 'out_total', orderable: false, render: function (d) { return fmtNumber(d); } },
                { data: 'remaining', name: 'remaining', orderable: false, render: function (d) { return fmtNumber(d); } },
                { data: 'out_count', name: 'out_count', orderable: false, render: function (d) { return fmtNumber(d); } },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (_, __, row) {
                        return '<button type="button" class="btn btn-sm btn-outline-primary belzona-open-outbounds" ' +
                            'data-sheet="' + (row.sheet_name || '') + '" data-row="' + (row.inbound_row_number || '') + '">' +
                            '<i class="ti-eye"></i> مشاهده خروجی‌ها</button>';
                    }
                }
            ],
            language: { url: nxUrl('public/js/datatables-persian.json') },
            responsive: true
        });
    }

    function refreshInboundSummary() {
        $.get(nxUrl('belzona-inventory'), {
            action: 'inbound_summary',
            sheet_name: $('#belzona-inbounds-filter-sheet').val(),
            filter_date_from: $('#belzona-inbounds-date-from').val(),
            filter_date_to: $('#belzona-inbounds-date-to').val()
        }, function (res) {
            if (!res || !res.success) return;
            var d = res.data || {};
            $('#belzona-inbounds-sum').text(fmtNumber(d.inbound_sum || 0));
            $('#belzona-inbounds-count').text((d.inbound_count || 0) + ' پارت');

            var latest = d.latest;
            if (latest) {
                $('#belzona-latest-inbound-title').text(latest.sheet_name + ' - ' + (latest.label || 'پارت ورود'));
                $('#belzona-latest-inbound-meta').text('تاریخ: ' + (latest.date_raw || '-') + ' | ورود: ' + fmtNumber(latest.input || 0));
                $('#belzona-latest-inbound-open')
                    .prop('disabled', false)
                    .data('sheet', latest.sheet_name)
                    .data('row', latest.inbound_row_number);
            } else {
                $('#belzona-latest-inbound-title').text('-');
                $('#belzona-latest-inbound-meta').text('-');
                $('#belzona-latest-inbound-open').prop('disabled', true);
            }
        });
    }

    function loadInboundProductsCombo() {
        $.get(nxUrl('belzona-inventory'), { action: 'unique_values', column: 'sheet_name' }, function (res) {
            if (!res || !res.success) return;
            var $sel = $('#belzona-inbounds-filter-sheet');
            var prev = $sel.val() || '';
            $sel.empty();
            $sel.append('<option value="">همه محصولات</option>');
            (res.data || []).forEach(function (v) {
                if (!v) return;
                var safe = String(v).replace(/\"/g, '&quot;');
                $sel.append('<option value="' + safe + '">' + safe + '</option>');
            });
            if (prev) $sel.val(prev);

            // init select2 once
            if ($.fn.select2 && !$sel.hasClass('select2-hidden-accessible')) {
                $sel.select2({
                    width: '100%',
                    dir: 'rtl',
                    placeholder: 'انتخاب محصول...',
                    allowClear: true
                });
            }
        });
    }

    // init on load
    initInboundsTable();
    refreshInboundSummary();
    loadInboundProductsCombo();

    // actions (namespace to prevent duplicates)
    $('#belzona-inbounds-refresh')
        .off('click.belzonaInbounds')
        .on('click.belzonaInbounds', function () {
            refreshInboundSummary();
            if (inboundsTable) inboundsTable.ajax.reload();
        });
    $('#belzona-inbounds-clear')
        .off('click.belzonaInbounds')
        .on('click.belzonaInbounds', function () {
            $('#belzona-inbounds-filter-sheet').val('').trigger('change');
            $('#belzona-inbounds-date-from').val('');
            $('#belzona-inbounds-date-to').val('');
            refreshInboundSummary();
            if (inboundsTable) inboundsTable.ajax.reload();
        });
    $('#belzona-inbounds-filter-sheet, #belzona-inbounds-date-from, #belzona-inbounds-date-to')
        .off('change.belzonaInbounds keyup.belzonaInbounds')
        .on('change.belzonaInbounds keyup.belzonaInbounds', function () {
            refreshInboundSummary();
            if (inboundsTable) inboundsTable.ajax.reload();
        });

    $(document)
        .off('click.belzonaInbounds', '.belzona-open-outbounds')
        .on('click.belzonaInbounds', '.belzona-open-outbounds', function () {
            openOutboundsModal($(this).data('sheet'), $(this).data('row'));
        });
    $('#belzona-latest-inbound-open')
        .off('click.belzonaInbounds')
        .on('click.belzonaInbounds', function () {
            openOutboundsModal($(this).data('sheet'), $(this).data('row'));
        });

    // invoice links inside outbounds modal body
    $(document)
        .off('click.belzonaInbounds', '.belzona-invoice-link')
        .on('click.belzonaInbounds', '.belzona-invoice-link', function (e) {
            e.preventDefault();
            openInvoiceModalByNumber($(this).data('invoice-number'));
        });
});

