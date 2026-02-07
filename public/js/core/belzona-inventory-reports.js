/**
 * Belzona Inventory - Reporting tab (Reports area)
 * Initializes:
 * - KPI + quick product lookup (batches + outbounds drilldown)
 * - Main inventory DataTable (server-side)
 *
 * This file is designed to be executed after AJAX-inserting the report wrapper HTML.
 */

// keep date picker global because the HTML uses inline onclick handlers
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

(function () {
    function initBelzonaInventoryReports() {
        // don't run if wrapper isn't present
        if (!$('#belzona-reporting-root').length) {
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

            function showInfoModal(title, html, level) {
                level = level || 'info';
                $('#commonModalContainer').removeClass('modal-xl');
                $('#commonModalTitle').text(title || 'فاکتور');
                $('#commonModalBody').html('<div class="alert alert-' + level + ' mb-0">' + html + '</div>');
                $('#commonModalFooter').hide();
                $('#commonModal').modal('show');
            }

            $.get(nxUrl('belzona-inventory'), {
                action: 'resolve_invoice',
                invoice_number: invoiceNumber
            }, function (res) {
                if (!res || !res.success || !res.data) {
                    showInfoModal('فاکتور', 'فاکتور/تسویه موجود نیست.', 'warning');
                    return;
                }

                var d = res.data;

                if (d.type === 'settlement') {
                    var html = '' +
                        '<div class="mb-2"><strong>سند تسویه</strong></div>' +
                        '<div class="table-responsive"><table class="table table-sm table-bordered mb-0">' +
                        '<tr><th style="width:180px">شماره سند</th><td>' + escapeHtml(d.document_number) + '</td></tr>' +
                        '<tr><th>تاریخ</th><td>' + escapeHtml(d.document_date || '---') + '</td></tr>' +
                        '<tr><th>مشتری</th><td>' + escapeHtml(d.customer_name || '---') + '</td></tr>' +
                        '<tr><th>مبلغ خالص</th><td>' + fmtNumber(d.base_net_amount || 0) + ' ' + escapeHtml(d.currency || '') + '</td></tr>' +
                        '<tr><th>پرداختی</th><td>' + fmtNumber(d.paid_amount || 0) + ' ' + escapeHtml(d.currency || '') + '</td></tr>' +
                        '<tr><th>مانده</th><td>' + fmtNumber(d.balance_amount || 0) + ' ' + escapeHtml(d.currency || '') + '</td></tr>' +
                        '</table></div>';
                    $('#commonModalContainer').removeClass('modal-xl');
                    $('#commonModalTitle').text('فاکتور و تسویه - ' + (d.document_number || invoiceNumber));
                    $('#commonModalBody').html(html);
                    $('#commonModalFooter').hide();
                    $('#commonModal').modal('show');
                    return;
                }

                if (d.type === 'invoice' && d.bill_invoiceid && d.edit_url) {
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
                    return;
                }

                showInfoModal('فاکتور', 'فاکتور/تسویه موجود نیست.', 'warning');
            }).fail(function (xhr) {
                var msg = 'خطا در دریافت اطلاعات فاکتور.';
                var level = 'danger';

                if (xhr && xhr.status === 404) {
                    msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'فاکتور/تسویه موجود نیست.';
                    level = 'warning';
                } else if (xhr && xhr.status === 422) {
                    msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'شماره فاکتور نامعتبر است.';
                    level = 'warning';
                } else if (xhr && (xhr.status === 401 || xhr.status === 419)) {
                    msg = 'دسترسی شما منقضی شده یا لاگین نیستید. صفحه را رفرش کنید.';
                    level = 'warning';
                } else if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                showInfoModal('فاکتور', msg, level);
            });
        }

        // init main DataTable (server-side)
        if (window.initBelzonaInventoryDataTable) {
            window.initBelzonaInventoryDataTable();
        }

        // fill product dropdown from distinct sheet_name values
        function loadProducts() {
            $.get(nxUrl('belzona-inventory'), { action: 'unique_values', column: 'sheet_name' }, function (res) {
                if (!res || !res.success) return;
                var $sel = $('#belzona-product-select');
                $sel.empty();
                $sel.append('<option value="">انتخاب محصول...</option>');
                (res.data || []).forEach(function (v) {
                    if (!v) return;
                    var safe = String(v).replace(/\"/g, '&quot;');
                    $sel.append('<option value="' + safe + '">' + safe + '</option>');
                });

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

        function renderOutbounds(rows) {
            var $tbody = $('#belzona-summary-transactions');
            $tbody.empty();
            if (!rows || rows.length === 0) {
                $tbody.append('<tr><td colspan="6" class="text-muted">خروجی‌ای یافت نشد.</td></tr>');
                return;
            }
            rows.forEach(function (r) {
                $tbody.append(
                    '<tr>' +
                    '<td>' + (r.date_raw || '') + '</td>' +
                    '<td>' + fmtNumber(r.output || 0) + '</td>' +
                    '<td>' + fmtNumber(r.balance || 0) + '</td>' +
                    '<td>' + renderInvoiceLink(r.invoice_number) + '</td>' +
                    '<td>' + (r.customer_name || '') + '</td>' +
                    '<td>' + (r.notes || '') + '</td>' +
                    '</tr>'
                );
            });
        }

        function renderBatches(batches) {
            var $tbody = $('#belzona-inbound-batches');
            $tbody.empty();

            var count = (batches && batches.length) ? batches.length : 0;
            $('#belzona-batches-count').text(count ? ('(' + count + ' پارت)') : '(0 پارت)');

            if (!batches || batches.length === 0) {
                $tbody.append('<tr><td colspan="7" class="text-muted">پارتی یافت نشد.</td></tr>');
                return;
            }

            batches.forEach(function (b, idx) {
                var rowNum = b.inbound_row_number || '';
                $tbody.append(
                    '<tr class="belzona-batch-row" data-row-number="' + rowNum + '">' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (b.label || 'ورود') + '</td>' +
                    '<td>' + (b.date_raw || '') + '</td>' +
                    '<td>' + fmtNumber(b.input || 0) + '</td>' +
                    '<td>' + fmtNumber(b.out_total || 0) + '</td>' +
                    '<td>' + fmtNumber(b.remaining || 0) + '</td>' +
                    '<td>' + fmtNumber(b.out_count || 0) + '</td>' +
                    '</tr>'
                );
            });
        }

        function loadBatchOutbounds(sheetName, inboundRowNumber) {
            var dateFrom = $('#belzona-date-from').val();
            var dateTo = $('#belzona-date-to').val();

            $.get(nxUrl('belzona-inventory'), {
                action: 'batch_outbounds',
                sheet_name: sheetName,
                inbound_row_number: inboundRowNumber,
                filter_date_from: dateFrom,
                filter_date_to: dateTo
            }, function (res) {
                if (!res || !res.success) return;
                var d = res.data || {};
                var inbound = d.inbound || {};

                $('#belzona-selected-batch-label').text(inbound.label ? ('- ' + inbound.label) : '');
                $('#belzona-selected-batch-meta').text(
                    'ورود: ' + fmtNumber(inbound.input || 0) +
                    ' | خروجی: ' + fmtNumber(inbound.out_total || 0) +
                    ' | مانده: ' + fmtNumber(inbound.remaining || 0) +
                    (inbound.date_raw ? (' | تاریخ ورود: ' + inbound.date_raw) : '')
                );

                renderOutbounds(d.outbounds || []);
            });
        }

        function loadSummary() {
            var sheetName = $('#belzona-product-select').val();
            if (!sheetName) return;

            var dateFrom = $('#belzona-date-from').val();
            var dateTo = $('#belzona-date-to').val();

            $.get(nxUrl('belzona-inventory'), {
                action: 'product_batches',
                sheet_name: sheetName,
                filter_date_from: dateFrom,
                filter_date_to: dateTo
            }, function (res) {
                if (!res || !res.success) return;
                var d = res.data || {};
                var totals = (d.totals || {});
                var batches = (d.batches || []);

                $('#belzona-summary-input').text(fmtNumber(totals.input_total || 0));
                $('#belzona-summary-output').text(fmtNumber(totals.out_total || 0));
                $('#belzona-summary-net').text(fmtNumber((totals.input_total || 0) - (totals.out_total || 0)));

                if (batches.length) {
                    var last = batches[batches.length - 1];
                    $('#belzona-summary-balance').text(fmtNumber(last.input || 0));
                    $('#belzona-summary-lastdate').text(last.date_raw || '-');
                } else {
                    $('#belzona-summary-balance').text('-');
                    $('#belzona-summary-lastdate').text('-');
                }

                renderBatches(batches);

                if (batches.length) {
                    var firstRowNum = batches[0].inbound_row_number;
                    $('.belzona-batch-row').removeClass('table-primary');
                    $('.belzona-batch-row[data-row-number="' + firstRowNum + '"]').addClass('table-primary');
                    loadBatchOutbounds(sheetName, firstRowNum);
                } else {
                    $('#belzona-selected-batch-label').text('');
                    $('#belzona-selected-batch-meta').text('');
                    renderOutbounds([]);
                }
            });
        }

        // bind events (namespace to avoid duplicates on re-open)
        $('#belzona-refresh-summary')
            .off('click.belzonaReports')
            .on('click.belzonaReports', loadSummary);

        $(document)
            .off('click.belzonaReports', '.belzona-batch-row')
            .on('click.belzonaReports', '.belzona-batch-row', function () {
                var sheetName = $('#belzona-product-select').val();
                var rowNumber = $(this).data('row-number');
                if (!sheetName || !rowNumber) return;
                $('.belzona-batch-row').removeClass('table-primary');
                $(this).addClass('table-primary');
                loadBatchOutbounds(sheetName, rowNumber);
            });

        // invoice links (outbounds tables + main datatable)
        $(document)
            .off('click.belzonaReports', '.belzona-invoice-link')
            .on('click.belzonaReports', '.belzona-invoice-link', function (e) {
                e.preventDefault();
                openInvoiceModalByNumber($(this).data('invoice-number'));
            });

        loadProducts();
    }

    window.initBelzonaInventoryReports = initBelzonaInventoryReports;
})();

