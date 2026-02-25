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
    var belzonaAjaxUrl = $('#belzona-inventory-page').attr('data-belzona-ajax-url') ||
        (baseUrl ? (baseUrl + '/belzona-inventory') : '/belzona-inventory');
    function nxUrl(path) {
        if (path === 'belzona-inventory') return belzonaAjaxUrl;
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

            // Settlement (Accounting - invoice settlements)
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

            // Invoice (system invoices)
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
            // 404/422 from server are "fail" in jQuery, but are not network errors
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

    // رندر اسلایدر COC (لیست urlها) — مشترک بین openCocModal و openCocModalForTitle
    function renderCocSlider(urls) {
        var idx = 0;
        var zoomLevel = 1;
        var isFullscreen = false;
        var html = '<div class="belzona-coc-slider position-relative" style="min-height:400px;">' +
            '<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">' +
            '<div class="d-flex gap-1 align-items-center">' +
            '<button type="button" class="btn btn-sm btn-outline-secondary belzona-coc-prev"><i class="ti-angle-right"></i> قبلی</button>' +
            '<span class="badge bg-secondary belzona-coc-counter">1 / ' + urls.length + '</span>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary belzona-coc-next">بعدی <i class="ti-angle-left"></i></button>' +
            '</div>' +
            '<div class="d-flex gap-1">' +
            '<button type="button" class="btn btn-sm btn-outline-secondary belzona-coc-zoom-out" title="کوچک‌نمایی"><i class="ti-zoom-out"></i></button>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary belzona-coc-zoom-reset" title="حجم عادی">100%</button>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary belzona-coc-zoom-in" title="بزرگ‌نمایی"><i class="ti-zoom-in"></i></button>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary belzona-coc-fullscreen" title="تمام صفحه"><i class="ti-fullscreen"></i></button>' +
            '</div></div>' +
            '<div class="belzona-coc-viewport overflow-auto border rounded bg-dark d-flex align-items-center justify-content-center p-2" style="height:65vh;min-height:350px;">' +
            '<img src="' + escapeHtml(urls[0]) + '" class="belzona-coc-img img-fluid" style="transform:scale(1);transform-origin:center center;transition:transform 0.2s;" alt="COC">' +
            '</div></div>';
        $('#commonModalBody').html(html);

        function goTo(i) {
            idx = Math.max(0, Math.min(i, urls.length - 1));
            zoomLevel = 1;
            var $img = $('.belzona-coc-img');
            $img.attr('src', urls[idx]).css('transform', 'scale(1)');
            $('.belzona-coc-counter').text((idx + 1) + ' / ' + urls.length);
        }
        function applyZoom() {
            $('.belzona-coc-img').css('transform', 'scale(' + zoomLevel + ')');
        }
        $('.belzona-coc-prev').on('click', function () { goTo(idx - 1); });
        $('.belzona-coc-next').on('click', function () { goTo(idx + 1); });
        $('.belzona-coc-zoom-in').on('click', function () { zoomLevel = Math.min(3, zoomLevel + 0.25); applyZoom(); });
        $('.belzona-coc-zoom-out').on('click', function () { zoomLevel = Math.max(0.25, zoomLevel - 0.25); applyZoom(); });
        $('.belzona-coc-zoom-reset').on('click', function () { zoomLevel = 1; applyZoom(); });
        $('.belzona-coc-fullscreen').on('click', function () {
            var $vp = $('.belzona-coc-viewport');
            if (!isFullscreen) {
                $vp.css({ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, width: '100vw', height: '100vh', zIndex: 9999, margin: 0, borderRadius: 0 });
                $('.belzona-coc-fullscreen i').removeClass('ti-fullscreen').addClass('ti-fullscreen-alt');
                isFullscreen = true;
            } else {
                $vp.css({ position: '', top: '', left: '', right: '', bottom: '', width: '', height: '', zIndex: '', margin: '', borderRadius: '' });
                $('.belzona-coc-fullscreen i').removeClass('ti-fullscreen-alt').addClass('ti-fullscreen');
                isFullscreen = false;
            }
        });
        $(document).on('keydown.belzonaCoc', function (e) {
            if ($('#commonModal').hasClass('show') && $('.belzona-coc-slider').length) {
                if (e.key === 'ArrowRight') { goTo(idx - 1); e.preventDefault(); }
                if (e.key === 'ArrowLeft') { goTo(idx + 1); e.preventDefault(); }
                if (e.key === 'Escape' && isFullscreen) { $('.belzona-coc-fullscreen').click(); }
            }
        });
    }

    // COC modal — نمایش اسکرین‌شات‌های سند COC برای یک رکورد (بر اساس inbound_id)
    function openCocModal(inboundId, sheetName, dateRaw) {
        if (!inboundId) return;
        $('#datePickerDialog, #datePickerOverlay').remove();
        $('#commonModalContainer').addClass('modal-xl');
        $('#commonModalTitle').html('سند COC — ' + escapeHtml(sheetName || '') + (dateRaw ? ' | ' + escapeHtml(dateRaw) : ''));
        $('#commonModalBody').html('<div class="alert alert-light border text-muted">در حال بارگذاری...</div>');
        $('#commonModalFooter').hide();
        $('#commonModal').modal('show');
        setTimeout(nxFixModalBackdrops, 0);

        $.get(nxUrl('belzona-inventory'), {
            action: 'get_coc_documents',
            inbound_id: inboundId
        }, function (res) {
            if (!res || !res.success || !res.data || !res.data.urls || !res.data.urls.length) {
                $('#commonModalBody').html('<div class="alert alert-warning mb-0">سند COC برای این رکورد یافت نشد.</div>');
                return;
            }
            renderCocSlider(res.data.urls);
        }).fail(function () {
            $('#commonModalBody').html('<div class="alert alert-danger mb-0">خطا در دریافت اسناد COC.</div>');
        });
    }

    // COC modal بر اساس عنوان — نمایش تمام COCهای مربوط به آن عنوان (همهٔ رکوردهای هم‌عنوان)
    function openCocModalForTitle(title) {
        if (!title || !String(title).trim()) return;
        $('#datePickerDialog, #datePickerOverlay').remove();
        $('#commonModalContainer').addClass('modal-xl');
        $('#commonModalTitle').html('سند COC — ' + escapeHtml(String(title).trim()));
        $('#commonModalBody').html('<div class="alert alert-light border text-muted">در حال بارگذاری...</div>');
        $('#commonModalFooter').hide();
        $('#commonModal').modal('show');
        setTimeout(nxFixModalBackdrops, 0);

        $.get(belzonaAjaxUrl, {
            action: 'get_coc_documents_by_title',
            title: title
        }, function (res) {
            if (!res || !res.success || !res.data || !res.data.urls || !res.data.urls.length) {
                $('#commonModalBody').html('<div class="alert alert-warning mb-0">سند COC برای این عنوان یافت نشد.</div>');
                return;
            }
            renderCocSlider(res.data.urls);
        }).fail(function () {
            $('#commonModalBody').html('<div class="alert alert-danger mb-0">خطا در دریافت اسناد COC.</div>');
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
            '<th>تاریخ</th><th>خروجی</th><th>مانده</th><th>شلف لایف / انقضا</th><th>فاکتور</th><th>مشتری</th><th>توضیحات</th>' +
            '</tr></thead>' +
            '<tbody id="belzona-commonmodal-tbody">' +
            '<tr><td colspan="7" class="text-muted">در حال بارگذاری...</td></tr>' +
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
                (inbound.date_raw ? (' | تاریخ ورود: ' + inbound.date_raw) : '') +
                (inbound.shelf_life_years != null ? (' | شلف لایف: ' + inbound.shelf_life_years + ' سال') : '') +
                (inbound.remaining_shelf_life ? (' | ' + inbound.remaining_shelf_life) : '');

            $('#commonModalBody .alert').html(
                '<strong>' + (inbound.label ? inbound.label : 'پارت ورود') + '</strong>' +
                '<div class="text-muted" style="margin-top:6px;">' + meta + '</div>'
            );

            var rows = d.outbounds || [];
            if (!rows.length) {
                $('#belzona-commonmodal-tbody').html('<tr><td colspan="7" class="text-muted">خروجی‌ای یافت نشد.</td></tr>');
                return;
            }

            var html = '';
            rows.forEach(function (r) {
                var shelfCell = (r.remaining_shelf_life || r.expiry_date) ? ((r.expiry_date || '') + (r.remaining_shelf_life ? ' <small class="text-muted">(' + r.remaining_shelf_life + ')</small>' : '')) : '—';
                html += '<tr>' +
                    '<td>' + (r.date_raw || '') + '</td>' +
                    '<td>' + fmtNumber(r.output || 0) + '</td>' +
                    '<td>' + fmtNumber(r.balance || 0) + '</td>' +
                    '<td>' + shelfCell + '</td>' +
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
            $(document).off('keydown.belzonaCoc');
            nxFixModalBackdrops();
        });
    $(document)
        .off('shown.bs.modal.belzonaInbounds', '#commonModal')
        .on('shown.bs.modal.belzonaInbounds', '#commonModal', function () {
            nxFixModalBackdrops();
        });

    // Inbounds table — فقط ۴ ستون: تاریخ ورود، محصول، عنوان، تعداد ورود
    var inboundsTable = null;
    function initInboundsTable() {
        if (inboundsTable) {
            try { inboundsTable.destroy(); } catch (e) {}
            inboundsTable = null;
        }
        var $tbl = $('#belzona-inbounds-table');
        if (!$tbl.length) return;
        $tbl.find('tbody').empty();

        inboundsTable = $tbl.DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            order: [[0, 'desc']],
            ajax: {
                url: belzonaAjaxUrl,
                type: 'GET',
                dataType: 'json',
                data: function (d) {
                    d.action = 'inbound_datatables';
                    d.sheet_name = $('#belzona-inbounds-filter-sheet').val();
                    d.filter_date_from = $('#belzona-inbounds-date-from').val();
                    d.filter_date_to = $('#belzona-inbounds-date-to').val();
                },
                error: function (xhr) {
                    console.error('Belzona inbounds datatable ajax error', xhr.status, xhr.responseText ? xhr.responseText.substring(0, 200) : '');
                }
            },
            columns: [
                { data: 'date_raw' },
                { data: 'sheet_name' },
                { data: 'inbound_label' },
                { data: 'input', render: function (d) { return fmtNumber(d); } },
                {
                    data: 'inbound_id',
                    orderable: false,
                    searchable: false,
                    render: function (id, type, row) {
                        return '<a href="javascript:void(0)" class="belzona-open-coc belzona-buttons btn btn-sm btn-outline-info" ' +
                            'data-inbound-id="' + (id || '') + '" data-sheet="' + escapeHtml(row.sheet_name || '') + '" data-date-raw="' + escapeHtml(row.date_raw || '') + '">' +
                            '<i class="ti-file"></i> COC</a>';
                    }
                }
            ],
            language: { url: nxUrl('public/js/datatables-persian.json') }
        });
    }

    function refreshInboundSummary() {
        $.get(belzonaAjaxUrl, {
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
                var lastBalance = latest.last_outbound_balance;
                $('#belzona-last-outbound-balance').text(lastBalance != null ? fmtNumber(lastBalance) : '—');
            } else {
                $('#belzona-latest-inbound-title').text('-');
                $('#belzona-latest-inbound-meta').text('-');
                $('#belzona-latest-inbound-open').prop('disabled', true);
                $('#belzona-last-outbound-balance').text('—');
            }
        });
    }

    function loadInboundProductsCombo() {
        $.get(belzonaAjaxUrl, { action: 'unique_values', column: 'sheet_name' }, function (res) {
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

    // init on load — فقط ورودها
    initInboundsTable();
    refreshInboundSummary();
    loadInboundProductsCombo();

    // actions (namespace to prevent duplicates)
    $('#belzona-inbounds-refresh')
        .off('click.belzonaInbounds')
        .on('click.belzonaInbounds', function () {
            refreshInboundSummary();
            if (inboundsTable) inboundsTable.ajax.reload();
            if (outputsTable) outputsTable.ajax.reload();
            if (expiryTable) expiryTable.ajax.reload();
        });
    $('#belzona-inbounds-clear')
        .off('click.belzonaInbounds')
        .on('click.belzonaInbounds', function () {
            $('#belzona-inbounds-filter-sheet').val('').trigger('change');
            $('#belzona-inbounds-date-from').val('');
            $('#belzona-inbounds-date-to').val('');
            refreshInboundSummary();
            if (inboundsTable) inboundsTable.ajax.reload();
            if (outputsTable) outputsTable.ajax.reload();
            if (expiryTable) expiryTable.ajax.reload();
        });
    $('#belzona-inbounds-filter-sheet, #belzona-inbounds-date-from, #belzona-inbounds-date-to')
        .off('change.belzonaInbounds keyup.belzonaInbounds')
        .on('change.belzonaInbounds keyup.belzonaInbounds', function () {
            refreshInboundSummary();
            if (inboundsTable) inboundsTable.ajax.reload();
            if (outputsTable) outputsTable.ajax.reload();
            if (expiryTable) expiryTable.ajax.reload();
        });

    $(document)
        .off('click.belzonaInbounds', '.belzona-open-outbounds')
        .on('click.belzonaInbounds', '.belzona-open-outbounds', function () {
            openOutboundsModal($(this).data('sheet'), $(this).data('row'));
        });
    $(document)
        .off('click.belzonaInbounds', '.belzona-open-coc')
        .on('click.belzonaInbounds', '.belzona-open-coc', function (e) {
            e.preventDefault();
            openCocModal($(this).data('inbound-id'), $(this).data('sheet'), $(this).data('dateRaw'));
        });

    // مودال افزودن COC — انتخاب رکورد از دیتاتیبل و آپلود فایل
    var addCocTable = null;
    var addCocSelected = { id: null, sheet: '', dateRaw: '' };

    function openAddCocModal() {
        $('#datePickerDialog, #datePickerOverlay').remove();
        $('#commonModalContainer').addClass('modal-xl');
        $('#commonModalTitle').text('افزودن COC');
        addCocSelected = { id: null, sheet: '', dateRaw: '' };
        var bodyHtml = '<div class="belzona-add-coc-modal">' +
            '<p class="text-muted small mb-2">یک رکورد از جدول زیر انتخاب کنید، سپس فایل‌های COC (تصویر) را انتخاب و ذخیره کنید.</p>' +
            '<div class="table-responsive mb-3" style="max-height:40vh;">' +
            '<table id="belzona-add-coc-table" class="table table-sm table-striped table-bordered" style="width:100%">' +
            '<thead class="table-light"><tr>' +
            '<th>تاریخ ورود</th><th>محصول</th><th>عنوان</th><th>تعداد ورود</th><th>انتخاب</th>' +
            '</tr></thead><tbody></tbody></table></div>' +
            '<div id="belzona-add-coc-selection" class="alert alert-secondary mb-2" style="display:none;">' +
            '<strong>رکورد انتخاب‌شده:</strong> <span id="belzona-add-coc-selection-text"></span>' +
            '</div>' +
            '<div class="mb-2">' +
            '<label class="form-label">فایل‌های COC (png, jpg, ...)</label>' +
            '<input type="file" id="belzona-add-coc-files" class="form-control" accept=".png,.jpg,.jpeg,.webp,.gif" multiple>' +
            '</div>' +
            '<button type="button" id="belzona-add-coc-save" class="btn btn-primary" disabled>ذخیره COC</button>' +
            '</div>';
        $('#commonModalBody').html(bodyHtml);
        $('#commonModalFooter').show();
        $('#commonModal').modal('show');
        setTimeout(nxFixModalBackdrops, 0);

        if (addCocTable) {
            try { addCocTable.destroy(); } catch (e) {}
            addCocTable = null;
        }
        var $tbl = $('#belzona-add-coc-table');
        if ($tbl.length && $.fn.DataTable) {
            addCocTable = $tbl.DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                order: [[0, 'desc']],
                ajax: {
                    url: belzonaAjaxUrl,
                    type: 'GET',
                    dataType: 'json',
                    data: function (d) {
                        d.action = 'inbound_datatables';
                        d.sheet_name = $('#belzona-inbounds-filter-sheet').val();
                        d.filter_date_from = $('#belzona-inbounds-date-from').val();
                        d.filter_date_to = $('#belzona-inbounds-date-to').val();
                    }
                },
                columns: [
                    { data: 'date_raw' },
                    { data: 'sheet_name' },
                    { data: 'inbound_label' },
                    { data: 'input', render: function (d) { return fmtNumber(d); } },
                    {
                        data: 'inbound_id',
                        orderable: false,
                        searchable: false,
                        render: function (id, type, row) {
                            return '<button type="button" class="btn btn-sm btn-success belzona-select-inbound" ' +
                                'data-inbound-id="' + (id || '') + '" data-sheet="' + escapeHtml(row.sheet_name || '') + '" data-date-raw="' + escapeHtml(row.date_raw || '') + '">' +
                                'انتخاب این رکورد</button>';
                        }
                    }
                ],
                language: { url: nxUrl('public/js/datatables-persian.json') }
            });
        }

        $('#belzona-add-coc-selection').hide();
        $('#belzona-add-coc-files').val('');
        $('#belzona-add-coc-save').prop('disabled', true);

        $(document).off('click.belzonaAddCoc', '.belzona-select-inbound').on('click.belzonaAddCoc', '.belzona-select-inbound', function () {
            addCocSelected.id = $(this).data('inbound-id');
            addCocSelected.sheet = $(this).data('sheet') || '';
            addCocSelected.dateRaw = $(this).data('dateRaw') || '';
            $('#belzona-add-coc-selection-text').text(addCocSelected.sheet + (addCocSelected.dateRaw ? ' | ' + addCocSelected.dateRaw : ''));
            $('#belzona-add-coc-selection').show();
            $('#belzona-add-coc-save').prop('disabled', false);
        });

        $('#belzona-add-coc-save').off('click.belzonaAddCoc').on('click.belzonaAddCoc', function () {
            if (!addCocSelected.id) return;
            var $files = $('#belzona-add-coc-files')[0];
            if (!$files || !$files.files || !$files.files.length) {
                alert('حداقل یک فایل انتخاب کنید.');
                return;
            }
            var fd = new FormData();
            fd.append('inbound_id', addCocSelected.id);
            for (var i = 0; i < $files.files.length; i++) {
                fd.append('coc_files[]', $files.files[i]);
            }
            var token = $('meta[name="csrf-token"]').attr('content');
            if (token) fd.append('_token', token);

            var $btn = $('#belzona-add-coc-save').prop('disabled', true).text('در حال ذخیره...');
            $.ajax({
                url: nxUrl('belzona-inventory/upload-coc'),
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res && res.success) {
                        alert(res.message || 'ذخیره شد.');
                        $('#commonModal').modal('hide');
                        if (inboundsTable) inboundsTable.ajax.reload();
                    } else {
                        alert(res.message || 'خطا در ذخیره.');
                    }
                },
                error: function (xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'خطا در ارسال.';
                    alert(msg);
                },
                complete: function () {
                    $btn.prop('disabled', false).text('ذخیره COC');
                }
            });
        });
    }

    $('#belzona-add-coc-btn').off('click.belzonaInbounds').on('click.belzonaInbounds', function () {
        openAddCocModal();
    });

    // COC های آخرین پارت‌ها — انتخاب در دیتابیس (per-user)
    var lastBatchCocTable = null;
    var currentLastBatchRows = [];

    function getLastBatchSelection(done) {
        $.get(belzonaAjaxUrl, { action: 'get_last_batch_selection' }, function (res) {
            var rows = (res && Array.isArray(res.rows)) ? res.rows : [];
            if (typeof done === 'function') done(rows);
        }).fail(function () {
            if (typeof done === 'function') done([]);
        });
    }

    function setLastBatchSelection(rows, done) {
        var token = $('meta[name="csrf-token"]').attr('content');
        var payload = { action: 'save_last_batch_selection', rows: rows || [] };
        if (token) payload._token = token;
        $.ajax({
            url: belzonaAjaxUrl,
            type: 'POST',
            dataType: 'json',
            data: payload,
            success: function (res) {
                if (typeof done === 'function') done(res && res.success ? null : 'error');
            },
            error: function () {
                if (typeof done === 'function') done('error');
            }
        });
    }

    function ensureDefaultLastBatch(done) {
        getLastBatchSelection(function (curRows) {
            if (curRows && curRows.length > 0) {
                currentLastBatchRows = curRows;
                done(curRows);
                return;
            }
            $.get(belzonaAjaxUrl, {
                action: 'inbound_unique_titles',
                start: 0,
                length: 3,
                sheet_name: $('#belzona-inbounds-filter-sheet').val(),
                filter_date_from: $('#belzona-inbounds-date-from').val(),
                filter_date_to: $('#belzona-inbounds-date-to').val()
            }, function (res) {
                if (res && res.data && res.data.length) {
                    var rows = res.data.map(function (r) {
                        return { title: r.title || '', inbound_id: r.inbound_id, sheet_name: r.sheet_name || '', date_raw: r.date_raw || '' };
                    });
                    setLastBatchSelection(rows, function () {
                        currentLastBatchRows = rows;
                        done(rows);
                    });
                } else {
                    done([]);
                }
            }).fail(function () { done([]); });
        });
    }

    function renderLastBatchViewMode(rows) {
        if (!rows || !rows.length) {
            return '<p class="text-muted">عنوانی انتخاب نشده. روی دکمه «تنظیم آخرین پارت‌ها» کلیک کنید.</p>' +
                '<p class="mt-2"><button type="button" class="btn btn-sm btn-outline-secondary" id="belzona-lb-settings-btn-body"><i class="ti-settings"></i> تنظیم آخرین پارت‌ها</button></p>';
        }
        var html = '<p class="mb-2"><button type="button" class="btn btn-sm btn-outline-secondary" id="belzona-lb-settings-btn-body"><i class="ti-settings"></i> تنظیم آخرین پارت‌ها</button></p><ul class="list-group list-group-flush">';
        rows.forEach(function (r) {
            var titleDisplay = (r.title && String(r.title).trim()) ? r.title : 'بدون عنوان';
            html += '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                '<span>' + escapeHtml(titleDisplay) + '</span>' +
                '<button type="button" class="btn btn-sm btn-info belzona-open-coc-lb" data-title="' + escapeHtml(r.title || '') + '">مشاهده COC</button>' +
                '</li>';
        });
        html += '</ul>';
        return html;
    }

    function openLastBatchCocModal() {
        $('#datePickerDialog, #datePickerOverlay').remove();
        $('#commonModalContainer').addClass('modal-xl');
        $('#commonModalTitle').html('COC های آخرین پارت‌ها <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="belzona-lb-settings-btn"><i class="ti-settings"></i> تنظیم آخرین پارت‌ها</button>');
        $('#commonModalBody').html('<div class="text-muted">در حال بارگذاری...</div>');
        $('#commonModalFooter').hide();
        $('#commonModal').modal('show');
        setTimeout(nxFixModalBackdrops, 0);

        function showViewMode() {
            ensureDefaultLastBatch(function (rows) {
                $('#commonModalBody').html('<div id="belzona-lb-view">' + renderLastBatchViewMode(rows) + '</div>');
                $(document).off('click.belzonaLB', '.belzona-open-coc-lb').on('click.belzonaLB', '.belzona-open-coc-lb', function (e) {
                    e.preventDefault();
                    openCocModalForTitle($(this).data('title'));
                });
                $(document).off('click.belzonaLB', '#belzona-lb-settings-btn-body').on('click.belzonaLB', '#belzona-lb-settings-btn-body', function () {
                    $('#belzona-lb-settings-btn').trigger('click');
                });
            });
        }

        showViewMode();

        $('#belzona-lb-settings-btn').off('click.belzonaLB').on('click.belzonaLB', function () {
            var stored = currentLastBatchRows || [];
            var selectedRowsMap = {};
            stored.forEach(function (r) {
                var key = (r.title && String(r.title).trim()) ? r.title : (r.inbound_id || '');
                selectedRowsMap[key] = { title: r.title || '', inbound_id: r.inbound_id, sheet_name: r.sheet_name || '', date_raw: r.date_raw || '' };
            });

            var bodyHtml = '<div id="belzona-lb-settings">' +
                '<p class="text-muted small">بر اساس <strong>عنوان</strong> (یونیک) انتخاب کنید: عناوین آخرین ورودها به‌صورت پیش‌فرض سه عنوان آخر هستند. می‌توانید عناوین دیگر را اضافه یا حذف کنید و ذخیره کنید.</p>' +
                '<div class="table-responsive mb-2" style="max-height:50vh;">' +
                '<table id="belzona-lb-settings-table" class="table table-sm table-striped table-bordered" style="width:100%">' +
                '<thead class="table-light"><tr><th>تاریخ ورود</th><th>محصول</th><th>عنوان</th><th>تعداد ورود</th><th>انتخاب</th></tr></thead><tbody></tbody></table></div>' +
                '<button type="button" class="btn btn-primary" id="belzona-lb-save">ذخیره و بازگشت</button></div>';
            $('#commonModalBody').html(bodyHtml);

            if (lastBatchCocTable) {
                try { lastBatchCocTable.destroy(); } catch (e) {}
                lastBatchCocTable = null;
            }
            var $tbl = $('#belzona-lb-settings-table');
            if ($tbl.length && $.fn.DataTable) {
                lastBatchCocTable = $tbl.DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 15,
                    order: [[0, 'desc']],
                    ajax: {
                        url: belzonaAjaxUrl,
                        type: 'GET',
                        dataType: 'json',
                        data: function (d) {
                            d.action = 'inbound_unique_titles';
                            d.sheet_name = $('#belzona-inbounds-filter-sheet').val();
                            d.filter_date_from = $('#belzona-inbounds-date-from').val();
                            d.filter_date_to = $('#belzona-inbounds-date-to').val();
                        }
                    },
                    columns: [
                        { data: 'date_raw' },
                        { data: 'sheet_name' },
                        { data: 'title' },
                        { data: 'input', render: function (d) { return fmtNumber(d); } },
                        {
                            data: 'title',
                            orderable: false,
                            searchable: false,
                            render: function (titleVal, type, row) {
                                if (type !== 'display') return titleVal;
                                var key = (titleVal && String(titleVal).trim()) ? titleVal : (row.inbound_id || '');
                                var inList = selectedRowsMap[key];
                                if (inList) {
                                    return '<button type="button" class="btn btn-sm btn-outline-danger belzona-lb-remove" data-title="' + escapeHtml(key) + '" data-inbound-id="' + (row.inbound_id || '') + '" data-sheet="' + escapeHtml(row.sheet_name || '') + '" data-date-raw="' + escapeHtml(row.date_raw || '') + '">حذف از لیست</button>';
                                }
                                return '<button type="button" class="btn btn-sm btn-success belzona-lb-add" data-title="' + escapeHtml(key) + '" data-inbound-id="' + (row.inbound_id || '') + '" data-sheet="' + escapeHtml(row.sheet_name || '') + '" data-date-raw="' + escapeHtml(row.date_raw || '') + '">اضافه به لیست</button>';
                            }
                        }
                    ],
                    language: { url: nxUrl('public/js/datatables-persian.json') }
                });
            }

            $('#commonModalBody').off('click.belzonaLB');
            $('#commonModalBody').on('click.belzonaLB', '.belzona-lb-add', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var titleKey = $btn.data('title') || '';
                var id = $btn.data('inbound-id');
                var sheet = $btn.data('sheet') || '';
                var dateRaw = $btn.data('dateRaw') || '';
                selectedRowsMap[titleKey] = { title: titleKey, inbound_id: id, sheet_name: sheet, date_raw: dateRaw };
                $btn.closest('td').html('<button type="button" class="btn btn-sm btn-outline-danger belzona-lb-remove" data-title="' + escapeHtml(titleKey) + '" data-inbound-id="' + id + '" data-sheet="' + escapeHtml(sheet) + '" data-date-raw="' + escapeHtml(dateRaw) + '">حذف از لیست</button>');
            });
            $('#commonModalBody').on('click.belzonaLB', '.belzona-lb-remove', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var titleKey = $btn.data('title') || '';
                var id = $btn.data('inbound-id');
                var sheet = $btn.data('sheet') || '';
                var dateRaw = $btn.data('dateRaw') || '';
                delete selectedRowsMap[titleKey];
                $btn.closest('td').html('<button type="button" class="btn btn-sm btn-success belzona-lb-add" data-title="' + escapeHtml(titleKey) + '" data-inbound-id="' + id + '" data-sheet="' + escapeHtml(sheet) + '" data-date-raw="' + escapeHtml(dateRaw) + '">اضافه به لیست</button>');
            });

            $('#belzona-lb-save').off('click.belzonaLB').on('click.belzonaLB', function () {
                var rows = [];
                for (var k in selectedRowsMap) {
                    if (selectedRowsMap.hasOwnProperty(k) && selectedRowsMap[k]) {
                        rows.push(selectedRowsMap[k]);
                    }
                }
                var $btn = $('#belzona-lb-save');
                $btn.prop('disabled', true).text('در حال ذخیره...');
                setLastBatchSelection(rows, function (err) {
                    $btn.prop('disabled', false).text('ذخیره و بازگشت');
                    if (err) {
                        alert('خطا در ذخیرهٔ تنظیمات در سرور.');
                        return;
                    }
                    currentLastBatchRows = rows;
                    $('#commonModalBody').html('<div id="belzona-lb-view">' + renderLastBatchViewMode(rows) + '</div>');
                    $(document).off('click.belzonaLB', '.belzona-open-coc-lb').on('click.belzonaLB', '.belzona-open-coc-lb', function (e) {
                        e.preventDefault();
                        openCocModalForTitle($(this).data('title'));
                    });
                    $(document).off('click.belzonaLB', '#belzona-lb-settings-btn-body').on('click.belzonaLB', '#belzona-lb-settings-btn-body', function () {
                        $('#belzona-lb-settings-btn').trigger('click');
                    });
                });
            });
        });
    }

    $('#belzona-last-batch-coc-open').off('click.belzonaInbounds').on('click.belzonaInbounds', function () {
        openLastBatchCocModal();
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

    // ---- تب خروجی‌ها و تب تاریخ انقضا ----
    var outputsTable = null;
    var expiryTable = null;
    var outputsTableInited = false;
    var expiryTableInited = false;

    function getSharedFiltersPayload() {
        var payload = {};
        var sheet = $('#belzona-inbounds-filter-sheet').val();
        if (sheet && String(sheet).trim() !== '') {
            payload.sheet_name = sheet;
        }
        return payload;
    }

    function initOutputsTable() {
        if (!$('#belzona-inventory-outputs-table').length || outputsTableInited) return;
        outputsTableInited = true;
        var $t = $('#belzona-inventory-outputs-table');
        if ($.fn.DataTable && $.fn.DataTable.isDataTable($t)) {
            $t.DataTable().destroy();
            $t.find('tbody').empty();
        }
        outputsTable = $t.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: belzonaAjaxUrl,
                type: 'GET',
                dataType: 'json',
                data: function (d) {
                    d.action = 'datatables';
                    d.column_search = getSharedFiltersPayload();
                    d.filter_date_from = $('#belzona-inbounds-date-from').val();
                    d.filter_date_to = $('#belzona-inbounds-date-to').val();
                }
            },
            columns: [
                { data: 'belzona_inventory_id', title: 'ID' },
                { data: 'sheet_name', title: 'محصول' },
                { data: 'product_weight_raw', title: 'وزن' },
                { data: 'date_raw', title: 'تاریخ' },
                { data: 'input', title: 'ورودی', render: function (d) { return fmtNumber(d); } },
                { data: 'output', title: 'خروجی', render: function (d) { return fmtNumber(d); } },
                { data: 'balance', title: 'مانده', render: function (d) { return fmtNumber(d); } },
                { data: 'invoice_number', title: 'فاکتور', render: function (d) { return renderInvoiceLink(d); } },
                { data: 'customer_name', title: 'مشتری' },
                { data: 'notes', title: 'توضیحات' },
                { data: 'shelf_life_years', title: 'شلف لایف', defaultContent: '—' },
                { data: 'expiry_date', title: 'تاریخ انقضا', defaultContent: '—', render: function (d, type, row) {
                    if (type !== 'display') return d || '—';
                    var exp = d ? String(d) : '';
                    var rem = row.remaining_shelf_life ? String(row.remaining_shelf_life) : '';
                    return exp + (rem ? ' <small class="text-muted">(' + rem + ')</small>' : '');
                }},
                { data: 'actions', title: 'عملیات', orderable: false, searchable: false, render: function (_, type, row) {
                    var id = row.belzona_inventory_id;
                    return '<a href="' + nxUrl('belzona-inventory/' + id) + '" class="btn btn-sm btn-primary"><i class="ti-eye"></i></a> ' +
                        '<a href="' + nxUrl('belzona-inventory/' + id + '/edit') + '" class="btn btn-sm btn-warning"><i class="ti-pencil"></i></a>';
                }}
            ],
            language: { url: nxUrl('public/js/datatables-persian.json') },
            pageLength: 25,
            order: [[0, 'desc']]
        });
        // خروجی‌ها و انقضا از فیلترهای بالای صفحه استفاده می‌کنند — در change ورودها reload می‌شوند
    }

    function initExpiryTable() {
        if (!$('#belzona-expiry-table').length || expiryTableInited) return;
        expiryTableInited = true;
        var $t = $('#belzona-expiry-table');
        if ($.fn.DataTable && $.fn.DataTable.isDataTable($t)) {
            $t.DataTable().destroy();
            $t.find('tbody').empty();
        }
        expiryTable = $t.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: belzonaAjaxUrl,
                type: 'GET',
                dataType: 'json',
                data: function (d) {
                    d.action = 'datatables_expiry';
                    d.sheet_name = $('#belzona-inbounds-filter-sheet').val();
                    d.filter_date_from = $('#belzona-inbounds-date-from').val();
                    d.filter_date_to = $('#belzona-inbounds-date-to').val();
                }
            },
            columns: [
                { data: 'sheet_name', title: 'نام محصول' },
                { data: 'date_raw', title: 'تاریخ ورود' },
                { data: 'shelf_life_years', title: 'شلف لایف (سال)', defaultContent: '—' },
                { data: 'expiry_date', title: 'تاریخ انقضا', defaultContent: '—' }
            ],
            language: { url: nxUrl('public/js/datatables-persian.json') },
            pageLength: 25,
            order: [[3, 'asc']]
        });
    }

    // هنگام نمایش تب: lazy init خروجی/انقضا + تنظیم layout برای جلوگیری از ناپدید شدن
    $(document).on('shown.bs.tab', '#belzona-main-tabs a[data-toggle="tab"]', function (e) {
        var target = $(e.target).attr('href');
        setTimeout(function () {
            if (target === '#belzona-pane-inbounds' && inboundsTable) {
                try {
                    inboundsTable.columns().adjust();
                    inboundsTable.draw(false);
                } catch (err) {}
            } else if (target === '#belzona-pane-outputs') {
                initOutputsTable();
                if (outputsTable) {
                    try {
                        outputsTable.columns().adjust();
                        outputsTable.draw(false);
                    } catch (err) {}
                }
            } else if (target === '#belzona-pane-expiry') {
                initExpiryTable();
                if (expiryTable) {
                    try {
                        expiryTable.columns().adjust();
                        expiryTable.draw(false);
                    } catch (err) {}
                }
            }
        }, 150);
    });
});

