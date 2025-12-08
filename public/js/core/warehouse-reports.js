/**
 * Warehouse Reports JavaScript
 * Handles all warehouse reporting functionality
 */

var warehouseFilterState = {
    quick_range: 'all',
    from_date: '',
    to_date: '',
    category_id: '',
    custom_category_id: '',
    search: '',
    status_filter: '',
    flags: [],
    sales_year: ''
};

var monthlySalesChart = null;
var categoryDistributionChart = null;
var customCategoriesCache = [];
var inventoryAlertsCache = [];
var currentStockTableInstance = null;
var warehousePersianPickerState = {
    activeInput: null,
    selectedDate: { year: 1403, month: 1, day: 1 }
};
var warehousePersianMonths = [
    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
];

function initCategoryFormEnhancements() {
    bindCategoryImageUpload();
    initDynamicSelect($('#custom-category-entities'), '#customCategoryModal');
    initDynamicSelect($('#item-inventory-id'), '#customCategoryItemModal');
    initDynamicSelect($('#item-client-id'), '#customCategoryItemModal');
    initEntrySelect($('#item-inventory-entry-id'), '#item-inventory-id', { dropdownParent: '#customCategoryItemModal', namespace: '.categoryItem' });
    initWarehousePersianDatePickers($('#customCategoryModal'));
    initWarehousePersianDatePickers($('#customCategoryItemModal'));
    updateCategoryEntitySelector($('#custom-category-type').val() || 'item');

    $('#custom-category-type').off('change').on('change', function () {
        updateCategoryEntitySelector($(this).val());
    });

    renderCategoryImagePreview($('#custom-category-image-preview'), $('#custom-category-image').val() || null);
}

function bindCategoryImageUpload() {
    var $fileInput = $('#custom-category-image-file');
    var $uploadInput = $('#custom-category-image-upload');
    var $removeInput = $('#custom-category-image-remove');
    var $imagePath = $('#custom-category-image');
    var $preview = $('#custom-category-image-preview');

    $('#btn-upload-category-image').off('click').on('click', function () {
        $fileInput.trigger('click');
    });

    $fileInput.off('change').on('change', function (event) {
        var file = event.target.files[0];
        if (!file) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadInput.val(e.target.result);
            $removeInput.val(0);
            renderCategoryImagePreview($preview, e.target.result);
        };
        reader.readAsDataURL(file);
    });

    $('#btn-remove-category-image').off('click').on('click', function () {
        $uploadInput.val('');
        $imagePath.val('');
        $removeInput.val(1);
        $fileInput.val('');
        renderCategoryImagePreview($preview, null);
    });
}

function renderCategoryImagePreview($preview, src) {
    if (!$preview.length) {
        return;
    }
    if (src) {
        $preview.html('<img src="' + src + '" alt="category preview">');
    } else {
        $preview.html('<span class="text-muted">بدون تصویر</span>');
    }
}

function updateCategoryEntitySelector(type) {
    var $select = $('#custom-category-entities');
    if (!$select.length) {
        return;
    }

    var hint = type === 'customer'
        ? 'لیست مشتریان برای این دسته فعال شد.'
        : 'لیست کالاها برای این دسته فعال شد.';
    $('#custom-category-entities-hint').text(hint);

    var url = type === 'customer' ? '/feed/company_names' : '/feed/inventory-items';
    $select.attr('data-ajax--url', url);
    initDynamicSelect($select, '#customCategoryModal');
    $select.val(null).trigger('change');
}

function initDynamicSelect($element, parentSelector) {
    if (!$element.length || !$.fn.select2) {
        return;
    }

    var $parent = parentSelector ? $(parentSelector) : null;
    if ($element.hasClass('select2-hidden-accessible')) {
        $element.select2('destroy');
    }

    var ajaxUrl = $element.attr('data-ajax--url');
    $element.select2({
        theme: 'bootstrap',
        width: null,
        containerCssClass: ':all:',
        minimumInputLength: 1,
        minimumResultsForSearch: 1,
        placeholder: $element.attr('data-placeholder') || $element.data('placeholder') || '',
        dropdownParent: $parent && $parent.length ? $parent : undefined,
        ajax: {
            url: ajaxUrl,
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return { term: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.value,
                            id: item.id
                        };
                    })
                };
            }
        }
    });
}

function initEntrySelect($select, inventorySelector, options) {
    if (!$select.length || !$.fn.select2) {
        return;
    }

    if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
    }

    options = options || {};
    var $parent = options.dropdownParent ? $(options.dropdownParent) : null;

    $select.select2({
        theme: 'bootstrap',
        width: null,
        containerCssClass: ':all:',
        placeholder: $select.data('placeholder') || '',
        dropdownParent: $parent && $parent.length ? $parent : undefined,
        minimumResultsForSearch: 1,
        ajax: createEntrySelectAjaxConfig(inventorySelector)
    });

    toggleEntrySelectAvailability(inventorySelector, $select);

    $(inventorySelector).off('change.entrySelect' + (options.namespace || '')).on('change.entrySelect' + (options.namespace || ''), function () {
        toggleEntrySelectAvailability(inventorySelector, $select);
    });
}

function initInventoryAlertSelects() {
    initDynamicSelect($('#alert-inventory-id'), '#inventoryAlertModal');
    initEntrySelect($('#alert-inventory-entry-id'), '#alert-inventory-id', { dropdownParent: '#inventoryAlertModal', namespace: '.alertForm' });
}

function setWarehouseSelectValue($select, value, label) {
    if (!$select.length) {
        return;
    }
    if (!value) {
        $select.val(null).trigger('change');
        return;
    }
    var optionExists = $select.find('option[value="' + value + '"]').length > 0;
    if (!optionExists) {
        var option = new Option(label || value, value, true, true);
        $select.append(option);
    }
    $select.val(value).trigger('change');
}

function buildInventoryLabel(data) {
    if (!data || !data.inventory_id) {
        return '';
    }
    var label = data.inventory_name || ('کالا #' + data.inventory_id);
    if (data.inventory_code) {
        label += ' (' + data.inventory_code + ')';
    }
    return label;
}

function buildEntryLabel(data, useFallback) {
    if (!data || (!data.entry_code && !data.lot_number && !data.serial_number && !data.entry_id && !useFallback)) {
        return '';
    }
    var parts = [];
    if (data.entry_code) {
        parts.push('سند ' + data.entry_code);
    }
    if (data.lot_number) {
        parts.push('بچ ' + data.lot_number);
    }
    if (data.serial_number) {
        parts.push('سریال ' + data.serial_number);
    }
    if (data.expiry_date) {
        parts.push('انقضا ' + data.expiry_date);
    }
    if (data.remaining_quantity !== undefined) {
        parts.push('باقیمانده ' + data.remaining_quantity);
    }
    if (!parts.length && useFallback && data.entry_id) {
        parts.push('ورود #' + data.entry_id);
    }
    return parts.join(' | ');
}

function createEntrySelectAjaxConfig(inventorySelector) {
    return {
        url: '/report/warehouse/inventory-entries',
        dataType: 'json',
        delay: 300,
        data: function (params) {
            return {
                inventory_id: $(inventorySelector).val(),
                search: params.term || '',
                status: 'available'
            };
        },
        transport: function (params, success, failure) {
            if (!$(inventorySelector).val()) {
                return null;
            }
            var $request = $.ajax(params);
            $request.then(success);
            $request.fail(failure);
            return $request;
        },
        processResults: function (response) {
            var results = [];
            if (response && response.data) {
                results = response.data.map(function (entry) {
                    return {
                        id: entry.entry_id,
                        text: buildEntryLabel(entry, true)
                    };
                });
            }
            return { results: results };
        }
    };
}

function toggleEntrySelectAvailability(inventorySelector, $select) {
    var hasInventory = !!$(inventorySelector).val();
    $select.prop('disabled', !hasInventory);
    if (!hasInventory) {
        $select.val(null).trigger('change');
    }
}

function configureCustomCategoryEntityModal(type) {
    type = type || 'item';
    $('#item-entity-type').val(type);
    var $inventoryField = $('.entity-field-item');
    var $customerField = $('.entity-field-customer');

    if (type === 'customer') {
        $inventoryField.addClass('d-none');
        $('#item-inventory-id').prop('disabled', true).val(null).trigger('change');
        $('#item-inventory-entry-id').prop('disabled', true).val(null).trigger('change');
        $customerField.removeClass('d-none');
        $('#item-client-id').prop('disabled', false);
        initDynamicSelect($('#item-client-id'), '#customCategoryItemModal');
        $('#custom-category-item-modal-title').text('افزودن مشتری به دسته');
    } else {
        $customerField.addClass('d-none');
        $('#item-client-id').prop('disabled', true).val(null).trigger('change');
        $inventoryField.removeClass('d-none');
        $('#item-inventory-id').prop('disabled', false);
        initDynamicSelect($('#item-inventory-id'), '#customCategoryItemModal');
        initEntrySelect($('#item-inventory-entry-id'), '#item-inventory-id', { dropdownParent: '#customCategoryItemModal', namespace: '.categoryItem' });
        $('#custom-category-item-modal-title').text('افزودن کالا به دسته');
    }

    $('#item-start-date, #item-end-date').val('');
    initWarehousePersianDatePickers($('#customCategoryItemModal'));
}

function initWarehousePersianDatePickers($context) {
    $context = $context && $context.length ? $context : $(document);
    $context.find('.persian-date-input').each(function () {
        var inputId = $(this).attr('id');
        if (!inputId) {
            return;
        }

        $('[data-target="' + inputId + '"]').off('click.warehouseDate').on('click.warehouseDate', function (e) {
            e.preventDefault();
            openWarehousePersianDatePicker(inputId);
        });

        $('#' + inputId).off('focus.warehouseDate').on('focus.warehouseDate', function () {
            openWarehousePersianDatePicker(inputId);
        });
    });
}

function openWarehousePersianDatePicker(inputId) {
    warehousePersianPickerState.activeInput = inputId;
    var currentValue = $('#' + inputId).val();

    if (currentValue && currentValue.trim() !== '') {
        var parts = currentValue.split('/');
        if (parts.length === 3) {
            warehousePersianPickerState.selectedDate = {
                year: parseInt(parts[0]),
                month: parseInt(parts[1]),
                day: parseInt(parts[2])
            };
        }
    }

    $('.persian-datepicker-popup').remove();
    var $popup = buildWarehousePersianPickerPopup();
    $('body').append($popup);

    var $input = $('#' + inputId);
    var offset = $input.offset();
    $popup.css({
        top: offset.top + $input.outerHeight() + 6,
        left: offset.left
    });

    $(document).off('click.warehouse-picker').on('click.warehouse-picker', function (e) {
        if (!$(e.target).closest('.persian-datepicker-popup').length &&
            !$(e.target).closest('#' + inputId).length &&
            !$(e.target).closest('[data-target="' + inputId + '"]').length) {
            closeWarehousePersianDatePicker();
        }
    });
}

function buildWarehousePersianPickerPopup() {
    var current = warehousePersianPickerState.selectedDate;
    var $popup = $('<div class="persian-datepicker-popup" id="warehouse-persian-picker"></div>');

    var header = $(
        '<div class="d-flex justify-content-between align-items-center mb-3">' +
            '<h6 class="mb-0">انتخاب تاریخ</h6>' +
            '<button type="button" class="btn btn-link p-0 text-dark" aria-label="Close">&times;</button>' +
        '</div>'
    );
    header.find('button').on('click', closeWarehousePersianDatePicker);

    var selectors =
        '<div class="row g-2 mb-3">' +
            '<div class="col-6">' +
                '<label class="form-label mb-1">سال</label>' +
                '<select class="form-select form-select-sm" id="warehouse-picker-year">' +
                    Array.from({ length: 40 }, function (_, index) {
                        var year = 1385 + index;
                        var selected = year === current.year ? 'selected' : '';
                        return '<option value="' + year + '" ' + selected + '>' + year + '</option>';
                    }).join('') +
                '</select>' +
            '</div>' +
            '<div class="col-6">' +
                '<label class="form-label mb-1">ماه</label>' +
                '<select class="form-select form-select-sm" id="warehouse-picker-month">' +
                    warehousePersianMonths.map(function (month, index) {
                        var selected = (index + 1) === current.month ? 'selected' : '';
                        return '<option value="' + (index + 1) + '" ' + selected + '>' + month + '</option>';
                    }).join('') +
                '</select>' +
            '</div>' +
        '</div>';

    var calendarContainer = '<div class="warehouse-calendar mb-3"></div>';
    var actions =
        '<div class="d-flex justify-content-end gap-2">' +
            '<button type="button" class="btn btn-sm btn-secondary" id="warehouse-picker-cancel">لغو</button>' +
            '<button type="button" class="btn btn-sm btn-primary" id="warehouse-picker-confirm">تأیید</button>' +
        '</div>';

    $popup.append(header, selectors, calendarContainer, actions);
    renderWarehouseCalendarDays($popup);

    $popup.on('change', '#warehouse-picker-year, #warehouse-picker-month', function () {
        warehousePersianPickerState.selectedDate.year = parseInt($('#warehouse-picker-year').val());
        warehousePersianPickerState.selectedDate.month = parseInt($('#warehouse-picker-month').val());
        renderWarehouseCalendarDays($popup);
    });

    $popup.on('click', '.warehouse-calendar-day', function () {
        warehousePersianPickerState.selectedDate.day = parseInt($(this).data('day'));
        renderWarehouseCalendarDays($popup);
    });

    $popup.on('click', '#warehouse-picker-confirm', function () {
        confirmWarehousePersianDate();
    });

    $popup.on('click', '#warehouse-picker-cancel', function () {
        closeWarehousePersianDatePicker();
    });

    return $popup;
}

function renderWarehouseCalendarDays($popup) {
    var container = $popup.find('.warehouse-calendar');
    var state = warehousePersianPickerState.selectedDate;
    var daysInMonth = state.month <= 6 ? 31 : (state.month <= 11 ? 30 : 29);
    var daysHtml = '<div class="d-grid" style="grid-template-columns: repeat(7, 1fr); gap: 6px;">' +
        ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'].map(function (day) {
            return '<div class="text-center fw-bold text-muted">' + day + '</div>';
        }).join('') +
        Array.from({ length: daysInMonth }, function (_, index) {
            var day = index + 1;
            var isSelected = day === state.day;
            return '<div class="warehouse-calendar-day text-center ' + (isSelected ? 'selected' : '') + '" data-day="' + day + '">' + day + '</div>';
        }).join('') +
        '</div>';
    container.html(daysHtml);
}

function confirmWarehousePersianDate() {
    if (!warehousePersianPickerState.activeInput) {
        return;
    }
    var date = warehousePersianPickerState.selectedDate;
    var value = date.year + '/' + String(date.month).padStart(2, '0') + '/' + String(date.day).padStart(2, '0');
    $('#' + warehousePersianPickerState.activeInput).val(value).trigger('change');
    closeWarehousePersianDatePicker();
}

function closeWarehousePersianDatePicker() {
    $('.persian-datepicker-popup').remove();
    $(document).off('click.warehouse-picker');
    warehousePersianPickerState.activeInput = null;
}

function setupAjaxDefaults() {
    var token = $('meta[name="csrf-token"]').attr('content');
    if (token) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
    }
}

function showWarehouseToast(type, message) {
    if (window.NX && typeof NX.notification === 'function') {
        NX.notification({
            type: type || 'success',
            message: message
        });
    } else {
        if (type === 'error') {
            console.error(message);
        } else {
            console.log(message);
        }
    }
}

// Initialize warehouse reports
function initWarehouseReports() {
    setupAjaxDefaults();
    bindQuickFilters();
    initCustomCategoryPanel();
    initInventoryAlertPanel();
    initOutsideInventoryTabs();
    initCategoryFormEnhancements();

    $(document).off('click', '#warehouse-tabs .nav-link').on('click', '#warehouse-tabs .nav-link', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');
        activateWarehouseTab(target || '#current-stock');
    });

    // Load summary
    if (typeof loadSummary === 'function') {
        loadSummary();
    }
    
    activateWarehouseTab('#current-stock');

    if (!customCategoriesCache.length) {
        fetchCustomCategories();
    }
    if (!inventoryAlertsCache.length) {
        fetchInventoryAlerts();
    }
    
    // Use event delegation for tabs (works with dynamically loaded content)
    $(document).off('shown.bs.tab', '#warehouse-tabs a[data-toggle="tab"]');
    $(document).on('shown.bs.tab', '#warehouse-tabs a[data-toggle="tab"]', function (e) {
        var target = $(e.target).attr("href");
        loadTabData(target);
    });
    
    // Event handlers with delegation
    $(document).off('keyup', '#current-stock-search');
    $(document).on('keyup', '#current-stock-search', function() {
        if ($('#current-stock-table').hasClass('dataTable')) {
            $('#current-stock-table').DataTable().search($(this).val()).draw();
        }
    });
    
    $(document).off('change', '#expiry-status-filter');
    $(document).on('change', '#expiry-status-filter', function() {
        loadExpiryReport($(this).val());
    });
    
    $(document).off('click', '#load-sales-report');
    $(document).on('click', '#load-sales-report', function() {
        var year = $('#sales-year-filter').val();
        loadSalesReport(year);
    });
    
    $(document).off('change', '#transaction-type-filter');
    $(document).on('change', '#transaction-type-filter', function() {
        if ($('#transactions-table').hasClass('dataTable')) {
            $('#transactions-table').DataTable().ajax.reload();
        }
    });
    
    $(document).off('keyup', '#transaction-search');
    $(document).on('keyup', '#transaction-search', function() {
        if ($('#transactions-table').hasClass('dataTable')) {
            $('#transactions-table').DataTable().search($(this).val()).draw();
        }
    });

    // View entries for specific inventory
    $(document).off('click', '.js-view-entries').on('click', '.js-view-entries', function () {
        var inventoryId = $(this).data('inventory-id');
        var inventoryCode = $(this).data('inventory-code');
        var inventoryName = $(this).data('inventory-name');
        showInventoryEntriesModal(inventoryId, inventoryCode, inventoryName);
    });

    // Entries tab filters
    $(document).off('change', '#entries-type-filter').on('change', '#entries-type-filter', function () {
        if ($('#inventory-entries-table').hasClass('dataTable')) {
            $('#inventory-entries-table').DataTable().ajax.reload();
        }
    });

    $(document).off('keyup', '#entries-search').on('keyup', '#entries-search', function () {
        if ($('#inventory-entries-table').hasClass('dataTable')) {
            $('#inventory-entries-table').DataTable().search($(this).val()).draw();
        }
    });
}

function bindQuickFilters() {
    $(document).off('click', '.js-quick-range').on('click', '.js-quick-range', function () {
        setQuickRange($(this).data('range'), true);
    });

    $(document).off('change', '#filter_category').on('change', '#filter_category', function () {
        warehouseFilterState.category_id = $(this).val();
        refreshWarehouseData();
    });

    $(document).off('click', '#btn-apply-filters').on('click', '#btn-apply-filters', function () {
        warehouseFilterState.from_date = $('#filter_from_date').val();
        warehouseFilterState.to_date = $('#filter_to_date').val();
        warehouseFilterState.search = $('#filter_search').val();
        refreshWarehouseData();
    });

    $(document).off('click', '.status-pill').on('click', '.status-pill', function () {
        setStatusFilter($(this).data('filter'));
    });

    $(document).off('click', '.custom-category-badge').on('click', '.custom-category-badge', function () {
        const id = String($(this).data('custom-id'));
        if (warehouseFilterState.custom_category_id === id) {
            warehouseFilterState.custom_category_id = '';
            $('.custom-category-badge').removeClass('active');
        } else {
            warehouseFilterState.custom_category_id = id;
            $('.custom-category-badge').removeClass('active');
            $(this).addClass('active');
        }
        refreshWarehouseData();
    });

    $(document).off('click', '.log-flag').on('click', '.log-flag', function () {
        toggleFlag($(this).data('flag'), $(this));
    });

    setQuickRange(warehouseFilterState.quick_range, false);

    $(document).off('click', '#btn-reset-filters').on('click', '#btn-reset-filters', function () {
        warehouseFilterState.quick_range = 'month';
        warehouseFilterState.from_date = '';
        warehouseFilterState.to_date = '';
        warehouseFilterState.category_id = '';
        warehouseFilterState.custom_category_id = '';
        warehouseFilterState.search = '';
        warehouseFilterState.status_filter = '';
        warehouseFilterState.flags = [];
        $('#filter_from_date, #filter_to_date, #filter_search').val('');
        $('#filter_category').val('');
        $('.custom-category-badge, .log-flag, .quick-chip, .quick-status-chip').removeClass('active');
        $('.quick-status-group .btn[data-status="all"]').addClass('active');
        setQuickRange('month', false);
        refreshWarehouseData();
    });

    $(document).off('click', '.quick-status-chip').on('click', '.quick-status-chip', function () {
        $('.quick-status-chip').removeClass('active');
        $(this).addClass('active');
        var status = $(this).data('status') || 'all';
        setStatusFilter(status);
    });

    $(document).off('click', '.quick-chip').on('click', '.quick-chip', function () {
        $('.quick-chip').removeClass('active');
        $(this).addClass('active');
        var chip = $(this).data('chip');
        var flagMap = {
            highValue: 'high-value',
            fastMove: 'fast-move',
            slowMove: 'slow-move',
            nearExpiry: 'near-expiry'
        };
        var mappedFlag = flagMap[chip];
        if (mappedFlag) {
            warehouseFilterState.flags = [mappedFlag];
            refreshWarehouseData();
        }
    });
}

function setQuickRange(range, triggerReload) {
    var selectedRange = range || 'all';
    warehouseFilterState.quick_range = selectedRange;
    $('.js-quick-range').removeClass('active');
    $(`.js-quick-range[data-range="${selectedRange}"]`).addClass('active');
    if (triggerReload) {
        warehouseFilterState.from_date = '';
        warehouseFilterState.to_date = '';
        refreshWarehouseData();
    }
}

function setStatusFilter(filter) {
    warehouseFilterState.status_filter = filter === 'all' ? '' : filter;
    $('.status-pill').removeClass('active');
    if (filter && filter !== 'all') {
        $(`.status-pill[data-filter="${filter}"]`).addClass('active');
    }
    refreshWarehouseData();
}

function toggleFlag(flag, $el) {
    const idx = warehouseFilterState.flags.indexOf(flag);
    if (idx > -1) {
        warehouseFilterState.flags.splice(idx, 1);
        $el.removeClass('active');
    } else {
        warehouseFilterState.flags.push(flag);
        $el.addClass('active');
    }
    refreshWarehouseData();
}

function refreshWarehouseData() {
    if (typeof loadSummary === 'function') {
        loadSummary();
    }
    reloadActiveTab();
}

function reloadActiveTab() {
    var activeTab = $('#warehouse-tabs .nav-link.active').attr('href');
    loadTabData(activeTab);
}

function getFilterPayload(extra) {
    var payload = Object.assign({}, warehouseFilterState, extra || {});
    payload.flags = warehouseFilterState.flags.slice();
    if (payload.quick_range === 'all') {
        payload.quick_range = '';
    }
    return payload;
}

function destroyDataTable(selector) {
    if (!$.fn.DataTable) {
        return;
    }
    var table = $(selector).get(0);
    if (!table) {
        return;
    }
    if (!table.parentNode) {
        // Table has been detached; nothing to destroy
        return;
    }
    if ($.fn.DataTable.isDataTable(table)) {
        try {
            $(table).DataTable().clear().destroy();
        } catch (error) {
            console.warn('DataTable destroy skipped for selector', selector, error);
        }
    }
}

function activateWarehouseTab(target) {
    var tabTarget = target || '#current-stock';
    var $link = $('#warehouse-tabs .nav-link[href="' + tabTarget + '"]');
    if ($link.length) {
        $('#warehouse-tabs .nav-link').removeClass('active');
        $link.addClass('active');
        $('#warehouse-tabs .nav-link').attr('aria-selected', 'false');
        $link.attr('aria-selected', 'true');
    }
    $('#warehouse-tab-content .tab-pane').removeClass('show active');
    if ($(tabTarget).length) {
        $(tabTarget).addClass('show active');
    }
    loadTabData(tabTarget);
}

function loadTabData(target) {
    if (!target) {
        return;
    }
    switch (target) {
        case '#current-stock':
            if (typeof loadCurrentStock === 'function') loadCurrentStock();
            break;
        case '#expiry':
            if (typeof loadExpiryReport === 'function') loadExpiryReport($('#expiry-status-filter').val());
            break;
        case '#sales':
            if (typeof loadSalesReport === 'function') loadSalesReport();
            break;
        case '#outside':
            if (typeof loadOutsideInventory === 'function') loadOutsideInventory();
            break;
        case '#analytics':
            if (typeof loadAnalytics === 'function') loadAnalytics();
            break;
        case '#transactions':
            if (typeof loadTransactions === 'function') loadTransactions();
            break;
        case '#entries':
            if (typeof loadInventoryEntries === 'function') loadInventoryEntries();
            break;
    }
}

function renderCustomActions(summary) {
    var $container = $('#custom-actions-container');
    if (!$container.length) {
        return;
    }

    if (!summary) {
        $container.find('.action-card').addClass('skeleton');
        return;
    }

    var actions = [];

    if ((summary.low_stock_count || 0) > 0) {
        actions.push({
            tone: 'warning',
            title: 'کمبود موجودی',
            description: summary.low_stock_count + ' کالا زیر حداقل موجودی هستند.',
            tab: '#current-stock',
            action: 'low-stock',
            cta: 'مشاهده کالاها'
        });
    }

    if ((summary.approaching_expiry_count || 0) > 0) {
        actions.push({
            tone: 'danger',
            title: 'نزدیک به انقضا',
            description: summary.approaching_expiry_count + ' کالا باید سریع‌تر بررسی شوند.',
            tab: '#expiry',
            action: 'near-expiry',
            cta: 'بررسی فوریت'
        });
    }

    if ((summary.expired_count || 0) > 0) {
        actions.push({
            tone: 'danger',
            title: 'کالاهای منقضی شده',
            description: summary.expired_count + ' کالا از تاریخ انقضا عبور کرده‌اند.',
            tab: '#expiry',
            action: 'expired',
            cta: 'اقدام فوری'
        });
    }

    if (!inventoryAlertsCache.length) {
        actions.push({
            tone: 'info',
            title: 'تعریف هشدار',
            description: 'هیچ هشدار فعالی ثبت نشده است. برای جلوگیری از خطا، هشدار ثبت کنید.',
            action: 'open-alert-panel',
            cta: 'افزودن هشدار'
        });
    }

    if (!customCategoriesCache.length) {
        actions.push({
            tone: 'info',
            title: 'دسته‌بندی سفارشی',
            description: 'برای مدیریت سریع‌تر، دسته‌بندی‌های دلخواه ایجاد کنید.',
            action: 'open-category-panel',
            cta: 'ساخت دسته جدید'
        });
    }

    if (!actions.length) {
        actions.push({
            tone: 'success',
            title: 'وضعیت پایدار',
            description: 'هیچ اقدام فوری موردنیاز نیست. پایش منظم را ادامه دهید.',
            cta: 'بازبینی گزارش‌ها',
            tab: '#analytics'
        });
    }

    var cards = actions.slice(0, 3).map(function (action) {
        return (
            '<div class="col-md-4 mb-3">' +
                '<div class="action-card ' + (action.tone || 'info') + '">' +
                    '<div>' +
                        '<h6>' + action.title + '</h6>' +
                        '<p>' + action.description + '</p>' +
                    '</div>' +
                '<button type="button" class="btn btn-sm btn-outline-dark js-action-cta" data-tab="' + (action.tab || '') + '" data-action="' + (action.action || '') + '" onclick="return window.handleWarehouseActionCTA(this);">' +
                        (action.cta || 'مشاهده') +
                    '</button>' +
                '</div>' +
            '</div>'
        );
    }).join('');

    $container.html(cards);
}

$(document).off('click', '#btn-refresh-actions').on('click', function () {
    loadSummary();
});

function processWarehouseActionCTA(tab, action) {
    if (tab) {
        $('#warehouse-tabs a[href="' + tab + '"]').trigger('click');
    }

    switch (action) {
        case 'low-stock':
            setStatusFilter('low');
            break;
        case 'near-expiry':
            $('#expiry-status-filter').val('approaching').trigger('change');
            break;
        case 'expired':
            $('#expiry-status-filter').val('expired').trigger('change');
            break;
        case 'open-alert-panel':
            if (!inventoryAlertsCache.length) {
                fetchInventoryAlerts();
            }
            resetInventoryAlertForm();
            toggleInventoryAlertPanel(false);
            showModal('#inventoryAlertModal');
            break;
        case 'open-category-panel':
            if (!customCategoriesCache.length) {
                fetchCustomCategories();
            }
            resetCustomCategoryForm();
            openCustomCategoryModal();
            break;
    }
}

$(document).off('click', '.js-action-cta').on('click', function () {
    processWarehouseActionCTA($(this).data('tab'), $(this).data('action'));
});

window.handleWarehouseActionCTA = function(element) {
    if (!element) {
        return false;
    }
    var $el = $(element);
    processWarehouseActionCTA($el.data('tab'), $el.data('action'));
    return false;
};

function initCustomCategoryPanel() {
    $(document).off('click', '#btn-open-custom-panel').on('click', '#btn-open-custom-panel', function () {
        toggleCustomCategoryPanel(true);
        if (!customCategoriesCache.length) {
            fetchCustomCategories();
        }
    });

    $(document).off('click', '#custom-category-panel .btn-close-panel').on('click', '#custom-category-panel .btn-close-panel', function () {
        toggleCustomCategoryPanel(false);
    });

    $(document).off('click', '#btn-new-custom-category').on('click', '#btn-new-custom-category', function () {
        resetCustomCategoryForm();
        openCustomCategoryModal();
    });

    $('#custom-category-form').off('submit').on('submit', submitCustomCategoryForm);
    $('#custom-category-item-form').off('submit').on('submit', submitCustomCategoryItemForm);

    $(document).off('click', '.js-edit-custom-category').on('click', '.js-edit-custom-category', function () {
        var categoryId = $(this).data('id');
        var category = customCategoriesCache.find(function (cat) {
            return String(cat.category_id) === String(categoryId);
        });
        if (category) {
            populateCustomCategoryForm(category);
            openCustomCategoryModal();
        }
    });

    $(document).off('click', '.js-delete-custom-category').on('click', '.js-delete-custom-category', function () {
        var categoryId = $(this).data('id');
        if (confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟')) {
            deleteCustomCategory(categoryId);
        }
    });

    $(document).off('click', '.js-attach-entity').on('click', '.js-attach-entity', function () {
        var categoryId = $(this).data('id');
        var categoryType = $(this).data('type') || 'item';
        $('#item-category-id').val(categoryId);
        configureCustomCategoryEntityModal(categoryType);
        toggleCustomCategoryPanel(false);
        showModal('#customCategoryItemModal');
    });
}

function openCustomCategoryModal() {
    toggleCustomCategoryPanel(false);
    showModal('#customCategoryModal');
    initWarehousePersianDatePickers($('#customCategoryModal'));
}

function toggleCustomCategoryPanel(open) {
    var $panel = $('#custom-category-panel');
    if (!$panel.length) {
        return;
    }
    $panel.toggleClass('open', !!open);
}

function fetchCustomCategories() {
    var $list = $('#custom-category-list');
    if (!$list.length) {
        return;
    }

    $list.html('<div class="text-center text-muted py-4"><div class="spinner-border text-primary"></div><p class="mt-2 mb-0">در حال بارگذاری دسته‌بندی‌ها...</p></div>');

    $.ajax({
        url: '/inventory/custom-categories',
        type: 'GET',
        success: function (response) {
            if (response.success) {
                customCategoriesCache = response.data || [];
                renderCustomCategoryList(customCategoriesCache);
                refreshCustomCategoryBadges(customCategoriesCache);
            } else {
                $list.html('<div class="text-danger text-center py-4">' + (response.error || 'خطا در بارگذاری داده‌ها') + '</div>');
            }
        },
        error: function () {
            $list.html('<div class="text-danger text-center py-4">خطا در ارتباط با سرور</div>');
        }
    });
}

function renderCustomCategoryList(categories) {
    var $list = $('#custom-category-list');
    if (!$list.length) {
        return;
    }

    if (!categories.length) {
        $list.html('<div class="text-center text-muted py-4">دسته‌بندی فعالی ثبت نشده است.</div>');
        return;
    }

    var html = categories.map(function (category) {
        var color = category.category_color || '#e5e7eb';
        var typeLabel = category.category_type === 'customer' ? 'مشتری' : 'کالا';
        var activeBadge = category.is_active ? '<span class="badge badge-success ms-2">فعال</span>' : '<span class="badge badge-secondary ms-2">غیرفعال</span>';
        var entitiesCount = category.entities_count || category.items_count || 0;
        var iconHtml = '<span class="category-avatar fallback"><i class="ti-tag"></i></span>';

        if (category.category_image_url) {
            iconHtml = '<span class="category-avatar"><img src="' + category.category_image_url + '" alt=""></span>';
        } else if (category.category_icon) {
            iconHtml = '<span class="category-avatar has-icon"><i class="' + category.category_icon + '"></i></span>';
        }

        return (
            '<div class="category-card mb-3" style="border-color:' + color + '" data-id="' + category.category_id + '">' +
                '<div class="d-flex justify-content-between align-items-start gap-3">' +
                    '<div class="d-flex align-items-start gap-3">' +
                        iconHtml +
                        '<div>' +
                            '<div class="d-flex align-items-center gap-2 mb-1">' +
                                '<span class="badge" style="background-color:' + color + ';color:#1f2933;">' + category.category_name + '</span>' +
                                '<small class="text-muted">' + typeLabel + '</small>' +
                                activeBadge +
                            '</div>' +
                            '<small class="text-muted">' + entitiesCount + ' عضو ثبت شده</small>' +
                        '</div>' +
                    '</div>' +
                    '<div class="btn-group btn-group-sm category-actions" role="group">' +
                        '<button type="button" class="btn btn-light js-attach-entity" data-id="' + category.category_id + '" data-type="' + category.category_type + '"><i class="ti-plus"></i></button>' +
                        '<button type="button" class="btn btn-light js-edit-custom-category" data-id="' + category.category_id + '"><i class="ti-pencil"></i></button>' +
                        '<button type="button" class="btn btn-light js-delete-custom-category" data-id="' + category.category_id + '"><i class="ti-trash"></i></button>' +
                    '</div>' +
                '</div>' +
                (category.description ? '<p class="mt-2 mb-0 small text-muted">' + category.description + '</p>' : '') +
            '</div>'
        );
    }).join('');

    $list.html(html);
}

function refreshCustomCategoryBadges(categories) {
    var $badgeContainer = $('#active-custom-categories');
    if (!$badgeContainer.length) {
        return;
    }

    if (!categories.length) {
        $badgeContainer.html('<span class="text-muted small">دسته‌بندی سفارشی ثبت نشده است.</span>');
        return;
    }

    var badges = categories.map(function (category) {
        var color = category.category_color || '#e2e8f0';
        return '<span class="badge custom-category-badge" data-custom-id="' + category.category_id + '" style="background-color:' + color + '; color:#1f2933;"><i class="ti-tag mr-1"></i> ' + category.category_name + '</span>';
    }).join('');

    $badgeContainer.html(badges);
}

function resetCustomCategoryForm() {
    var $form = $('#custom-category-form');
    if ($form.length) {
        $form[0].reset();
    }
    $('#custom-category-id').val('');
    $('#custom-category-image-upload').val('');
    $('#custom-category-image-remove').val(0);
    $('#custom-category-image').val('');
    renderCategoryImagePreview($('#custom-category-image-preview'), null);
    $('#custom-category-type').val('item');
    updateCategoryEntitySelector('item');
    var $entities = $('#custom-category-entities');
    if ($entities.length) {
        $entities.val(null).trigger('change');
    }
    initWarehousePersianDatePickers($('#customCategoryModal'));
}

function populateCustomCategoryForm(category) {
    $('#custom-category-id').val(category.category_id);
    $('#custom-category-name').val(category.category_name);
    $('#custom-category-type').val(category.category_type || 'item');
    updateCategoryEntitySelector(category.category_type || 'item');
    $('#custom-category-color').val(category.category_color || '#5a9ba5');
    $('#custom-category-icon').val(category.category_icon || '');
    $('#custom-category-image').val(category.category_image || '');
    $('#custom-category-image-upload').val('');
    $('#custom-category-image-remove').val(0);
    renderCategoryImagePreview($('#custom-category-image-preview'), category.category_image_url || null);
    $('#custom-category-description').val(category.description || '');
    $('#custom-category-start-date').val(category.start_date_persian || category.start_date || '');
    $('#custom-category-end-date').val(category.end_date_persian || category.end_date || '');

    var $entities = $('#custom-category-entities');
    if ($entities.length) {
        $entities.empty();
        var collection = category.category_type === 'customer' ? (category.clients || []) : (category.items || []);
        collection.forEach(function (entity) {
            var value = category.category_type === 'customer' ? entity.client_id : entity.inventory_id;
            var label = category.category_type === 'customer'
                ? (entity.client_name || ('مشتری #' + entity.client_id))
                : ((entity.inventory_name || 'کالا') + (entity.inventory_code ? ' (' + entity.inventory_code + ')' : ''));
            var option = new Option(label, value, true, true);
            $entities.append(option);
        });
        $entities.trigger('change');
    }

    initWarehousePersianDatePickers($('#customCategoryModal'));
}

function submitCustomCategoryForm(event) {
    event.preventDefault();
    var $form = $(this);
    var id = $('#custom-category-id').val();
    var method = id ? 'PUT' : 'POST';
    var url = id ? '/inventory/custom-categories/' + id : '/inventory/custom-categories';
    var $submitBtn = $form.find('button[type="submit"]');

    $submitBtn.prop('disabled', true).addClass('button-loading');

    $.ajax({
        url: url,
        type: method,
        data: $form.serialize(),
        success: function (response) {
            if (response.success) {
                hideModal('#customCategoryModal');
                showWarehouseToast('success', response.message || 'دسته‌بندی ذخیره شد');
                fetchCustomCategories();
            } else {
                showWarehouseToast('error', response.error || 'ذخیره دسته‌بندی با خطا مواجه شد');
            }
        },
        error: function (xhr) {
            var errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'خطا در ارتباط با سرور';
            showWarehouseToast('error', errorMessage);
        },
        complete: function () {
            $submitBtn.prop('disabled', false).removeClass('button-loading');
        }
    });
}

function submitCustomCategoryItemForm(event) {
    event.preventDefault();
    var $form = $(this);
    var $submitBtn = $form.find('button[type="submit"]');

    $submitBtn.prop('disabled', true).addClass('button-loading');

    $.ajax({
        url: '/inventory/custom-categories/add-inventory',
        type: 'POST',
        data: $form.serialize(),
        success: function (response) {
            if (response.success) {
                hideModal('#customCategoryItemModal');
                $form[0].reset();
                $('#item-inventory-id, #item-client-id').val(null).trigger('change');
                configureCustomCategoryEntityModal('item');
                showWarehouseToast('success', response.message || 'کالا ثبت شد');
                fetchCustomCategories();
            } else {
                showWarehouseToast('error', response.error || 'ثبت کالا امکان‌پذیر نبود');
            }
        },
        error: function (xhr) {
            var errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'خطا در ارتباط با سرور';
            showWarehouseToast('error', errorMessage);
        },
        complete: function () {
            $submitBtn.prop('disabled', false).removeClass('button-loading');
        }
    });
}

function deleteCustomCategory(categoryId) {
    $.ajax({
        url: '/inventory/custom-categories/' + categoryId,
        type: 'DELETE',
        success: function (response) {
            if (response.success) {
                showWarehouseToast('success', response.message || 'دسته‌بندی حذف شد');
                fetchCustomCategories();
            } else {
                showWarehouseToast('error', response.error || 'حذف دسته‌بندی امکان‌پذیر نبود');
            }
        },
        error: function (xhr) {
            var errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'خطا در ارتباط با سرور';
            showWarehouseToast('error', errorMessage);
        }
    });
}

function initInventoryAlertPanel() {
    $(document).off('click', '#btn-open-alert-panel').on('click', '#btn-open-alert-panel', function () {
        toggleInventoryAlertPanel(true);
        fetchInventoryAlerts();
    });

    $(document).off('click', '#inventory-alert-panel .btn-close-panel').on('click', '#inventory-alert-panel .btn-close-panel', function () {
        toggleInventoryAlertPanel(false);
    });

    $(document).off('click', '#btn-create-alert').on('click', '#btn-create-alert', function () {
        resetInventoryAlertForm();
        toggleInventoryAlertPanel(false);
        showModal('#inventoryAlertModal');
    });

    $('#inventory-alert-form').off('submit').on('submit', submitInventoryAlertForm);
    initInventoryAlertSelects();

    $(document).off('click', '.js-alert-edit').on('click', '.js-alert-edit', function () {
        var alertId = $(this).data('id');
        var alert = inventoryAlertsCache.find(function (item) {
            return String(item.alert_id) === String(alertId);
        });
        if (alert) {
            populateInventoryAlertForm(alert);
            toggleInventoryAlertPanel(false);
            showModal('#inventoryAlertModal');
        }
    });

    $(document).off('click', '.js-alert-delete').on('click', '.js-alert-delete', function () {
        var alertId = $(this).data('id');
        if (confirm('آیا از حذف این هشدار مطمئن هستید؟')) {
            deleteInventoryAlert(alertId);
        }
    });

    $(document).off('click', '.js-alert-toggle').on('click', '.js-alert-toggle', function () {
        var alertId = $(this).data('id');
        toggleInventoryAlert(alertId);
    });
}

function initOutsideInventoryTabs() {
    $(document).off('click', '#outside-tabs .nav-link').on('click', '#outside-tabs .nav-link', function (e) {
        e.preventDefault();
        var $link = $(this);
        var target = $link.attr('href');

        $('#outside-tabs .nav-link').removeClass('active').attr('aria-selected', 'false');
        $link.addClass('active').attr('aria-selected', 'true');

        $('#outside .tab-pane').removeClass('show active');
        if (target && $(target).length) {
            $(target).addClass('show active');
        }
    });
}

function fetchInventoryAlerts() {
    var $list = $('#inventory-alerts-list');
    if (!$list.length) {
        return;
    }

    $list.html('<div class="text-center text-muted py-4"><div class="spinner-border text-primary"></div><p class="mt-2 mb-0">در حال بارگذاری هشدارها...</p></div>');

    $.ajax({
        url: '/inventory/alerts',
        type: 'GET',
        success: function (response) {
            if (response.success) {
                inventoryAlertsCache = response.data || [];
                renderInventoryAlertList(inventoryAlertsCache);
                updateAlertSummary(inventoryAlertsCache);
            } else {
                $list.html('<div class="text-danger text-center py-4">' + (response.error || 'خطا در بارگذاری هشدارها') + '</div>');
            }
        },
        error: function () {
            $list.html('<div class="text-danger text-center py-4">خطا در ارتباط با سرور</div>');
        }
    });
}

function renderInventoryAlertList(alerts) {
    var $list = $('#inventory-alerts-list');
    if (!alerts.length) {
        $list.html('<div class="text-center text-muted py-4">هشداری ثبت نشده است.</div>');
        return;
    }

    var html = alerts.map(function (alert) {
        var statusClass = alert.is_active ? 'active' : 'inactive';
        var inventoryLabel = alert.inventory_id ? buildInventoryLabel(alert) : 'هشدار کلی';
        var entryLabel = buildEntryLabel(alert);
        var typeMap = {
            expiry: 'انقضا',
            minimum: 'کمبود موجودی',
            maximum: 'مازاد موجودی',
            quantity: 'سقف موجودی'
        };

        var channels = '';
        if (alert.alert_email) {
            channels += '<span class="alert-channel-badge"><i class="ti-email"></i> ایمیل</span>';
        }
        if (alert.alert_sms) {
            channels += '<span class="alert-channel-badge"><i class="ti-comments-smiley"></i> پیامک</span>';
        }

        return (
            '<div class="inventory-alert-card" data-id="' + alert.alert_id + '">' +
                '<div class="d-flex justify-content-between align-items-start gap-3">' +
                    '<div>' +
                        '<div class="d-flex align-items-center gap-2 mb-1">' +
                            '<strong>' + (typeMap[alert.alert_type] || alert.alert_type) + '</strong>' +
                            '<span class="alert-status-pill ' + statusClass + '">' + (alert.is_active ? 'فعال' : 'غیرفعال') + '</span>' +
                        '</div>' +
                        '<div class="text-muted small mb-1">' + inventoryLabel + (entryLabel ? '<br><span class="text-info">' + entryLabel + '</span>' : '') + '</div>' +
                        '<div class="small mb-1">' +
                            (alert.threshold_days ? ('آستانه روز: ' + alert.threshold_days) : '') +
                            (alert.threshold_value ? (' | آستانه مقدار: ' + alert.threshold_value) : '') +
                        '</div>' +
                        '<div>' + channels + '</div>' +
                    '</div>' +
                    '<div class="btn-group btn-group-sm" role="group">' +
                        '<button type="button" class="btn btn-light js-alert-toggle" data-id="' + alert.alert_id + '">' +
                            (alert.is_active ? '<i class="ti-control-pause"></i>' : '<i class="ti-control-play"></i>') +
                        '</button>' +
                        '<button type="button" class="btn btn-light js-alert-edit" data-id="' + alert.alert_id + '"><i class="ti-pencil"></i></button>' +
                        '<button type="button" class="btn btn-light js-alert-delete" data-id="' + alert.alert_id + '"><i class="ti-trash"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );
    }).join('');

    $list.html(html);
}

function updateAlertSummary(alerts) {
    $('#alert-total-count').text(alerts.length);
    var activeCount = alerts.filter(function (alert) { return alert.is_active; }).length;
    $('#alert-active-count').text(activeCount);
    var criticalCount = alerts.filter(function (alert) {
        return alert.alert_type === 'expiry' && (alert.threshold_days || 0) <= 7;
    }).length;
    $('#alert-critical-count').text(criticalCount);
}

function resetInventoryAlertForm() {
    var $form = $('#inventory-alert-form');
    if ($form.length) {
        $form[0].reset();
    }
    $('#alert-id').val('');
    $('#alert-email').prop('checked', true);
    $('#alert-status').prop('checked', true);
    $('#alert-inventory-id').val(null).trigger('change');
    $('#alert-inventory-entry-id').val(null).trigger('change').prop('disabled', true);
}

function populateInventoryAlertForm(alert) {
    $('#alert-id').val(alert.alert_id);
    var inventoryLabel = buildInventoryLabel(alert);
    setWarehouseSelectValue($('#alert-inventory-id'), alert.inventory_id || '', inventoryLabel);
    setWarehouseSelectValue($('#alert-inventory-entry-id'), alert.inventory_entry_id || '', buildEntryLabel(alert));
    $('#alert-type').val(alert.alert_type);
    $('#alert-threshold-days').val(alert.threshold_days || '');
    $('#alert-threshold-value').val(alert.threshold_value || '');
    $('#alert-email').prop('checked', !!alert.alert_email);
    $('#alert-sms').prop('checked', !!alert.alert_sms);
    $('#alert-email-addresses').val(alert.alert_email_addresses || '');
    $('#alert-phone-numbers').val(alert.alert_phone_numbers || '');
    $('#alert-status').prop('checked', !!alert.is_active);
    toggleEntrySelectAvailability('#alert-inventory-id', $('#alert-inventory-entry-id'));
}

function submitInventoryAlertForm(event) {
    event.preventDefault();
    var $form = $(this);
    var alertId = $('#alert-id').val();
    var method = alertId ? 'PUT' : 'POST';
    var url = alertId ? '/inventory/alerts/' + alertId : '/inventory/alerts';
    var $submitBtn = $form.find('button[type="submit"]');

    $submitBtn.prop('disabled', true).addClass('button-loading');

    $.ajax({
        url: url,
        type: method,
        data: $form.serialize(),
        success: function (response) {
            if (response.success) {
                hideModal('#inventoryAlertModal');
                showWarehouseToast('success', response.message || 'هشدار ذخیره شد');
                fetchInventoryAlerts();
            } else {
                showWarehouseToast('error', response.error || 'ذخیره هشدار با خطا مواجه شد');
            }
        },
        error: function (xhr) {
            var errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'خطا در ارتباط با سرور';
            showWarehouseToast('error', errorMessage);
        },
        complete: function () {
            $submitBtn.prop('disabled', false).removeClass('button-loading');
        }
    });
}

function toggleInventoryAlert(alertId) {
    $.ajax({
        url: '/inventory/alerts/' + alertId + '/toggle',
        type: 'POST',
        success: function (response) {
            if (response.success) {
                showWarehouseToast('success', response.message || 'وضعیت هشدار بروزرسانی شد');
                fetchInventoryAlerts();
            } else {
                showWarehouseToast('error', response.error || 'تغییر وضعیت امکان‌پذیر نبود');
            }
        },
        error: function (xhr) {
            var errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'خطا در ارتباط با سرور';
            showWarehouseToast('error', errorMessage);
        }
    });
}

function toggleInventoryAlertPanel(open) {
    var $panel = $('#inventory-alert-panel');
    if (!$panel.length) {
        return;
    }
    if (open) {
        toggleCustomCategoryPanel(false);
    }
    $panel.toggleClass('open', !!open);
}

function showModal(selector) {
    var $modal = $(selector);
    if (!$modal.length) {
        return;
    }
    if (!$modal.parent().is('body')) {
        $modal.appendTo('body');
    }
    if ($.fn.modal) {
        $modal.off('shown.warehouseModal hidden.warehouseModal');

        $modal.on('shown.warehouseModal', function () {
            $('body').addClass('modal-open');
            $('.modal-backdrop').addClass('warehouse-modal-backdrop');
        });

        $modal.on('hidden.warehouseModal', function () {
            var fallbackBackdrop = $('.modal-backdrop.warehouse-modal-backdrop');
            if (!$('.modal.show').length) {
                fallbackBackdrop.remove();
                $('body').removeClass('modal-open');
            }
        });

        $modal.modal('show');

        setTimeout(function () {
            if (!$modal.hasClass('show')) {
                fallbackDisplayModal($modal);
            }
        }, 300);
    } else {
        fallbackDisplayModal($modal);
    }
}

function fallbackDisplayModal($modal) {
    $modal.addClass('show').css('display', 'block').attr('aria-modal', 'true');
    if (!$modal.parent().is('body')) {
        $modal.appendTo('body');
    }
    $('body').addClass('modal-open');
    if (!$('.modal-backdrop.warehouse-modal-backdrop').length) {
        $('<div class="modal-backdrop fade show warehouse-modal-backdrop"></div>').appendTo('body');
    }
}

function fallbackHideModal($modal) {
    $modal.removeClass('show').css('display', 'none').removeAttr('aria-modal');
    cleanupWarehouseModalState();
}

$(document).off('click.warehouseFallback', '[data-dismiss="modal"]').on('click.warehouseFallback', '[data-dismiss="modal"]', function (e) {
    if (!$.fn.modal) {
        e.preventDefault();
        var $modal = $(this).closest('.modal');
        fallbackHideModal($modal);
    }
});

function hideModal(selector) {
    var $modal = typeof selector === 'string' ? $(selector) : selector;
    if (!$modal || !$modal.length) {
        return;
    }
    if ($.fn.modal) {
        $modal.off('hidden.warehouseManual').on('hidden.warehouseManual', function () {
            cleanupWarehouseModalState();
            $modal.off('hidden.warehouseManual');
        });
        $modal.modal('hide');
        setTimeout(function () {
            if ($modal.hasClass('show')) {
                fallbackHideModal($modal);
            }
        }, 300);
    } else {
        fallbackHideModal($modal);
    }
}

$(document).off('click.warehouseModalClose').on('click.warehouseModalClose', '.modal [data-dismiss="modal"], .modal .close', function (e) {
    e.preventDefault();
    var $modal = $(this).closest('.modal');
    hideModal($modal);
});

function cleanupWarehouseModalState() {
    if ($('.modal.show').length) {
        return;
    }
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function deleteInventoryAlert(alertId) {
    $.ajax({
        url: '/inventory/alerts/' + alertId,
        type: 'DELETE',
        success: function (response) {
            if (response.success) {
                showWarehouseToast('success', response.message || 'هشدار حذف شد');
                fetchInventoryAlerts();
            } else {
                showWarehouseToast('error', response.error || 'حذف هشدار امکان‌پذیر نبود');
            }
        },
        error: function (xhr) {
            var errorMessage = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'خطا در ارتباط با سرور';
            showWarehouseToast('error', errorMessage);
        }
    });
}

// Initialize when document is ready
$(document).ready(function() {
    initWarehouseReports();
});

// Also initialize when content is loaded via AJAX
$(document).on('DOMContentLoaded', function() {
    initWarehouseReports();
});

/**
 * Load summary statistics
 */
function loadSummary() {
    $.ajax({
        url: '/report/warehouse/summary',
        type: 'GET',
        data: getFilterPayload(),
        success: function(response) {
            if (response.success && response.data) {
                $('#summary-total-items').text(response.data.total_items || 0);
                $('#summary-total-value').text(formatCurrency(response.data.total_value || 0));
                $('#summary-expiring').text(response.data.approaching_expiry_count || 0);
                $('#summary-expired').text(response.data.expired_count || 0);
                renderCustomActions(response.data);
            }
        },
        error: function() {
            console.error('Failed to load summary');
        }
    });
}

/**
 * Initialize tabs - removed, tabs are handled by Bootstrap
 */

/**
 * Load current stock
 */
function loadCurrentStock() {
    var $table = $('#current-stock-table');
    if ($table.length === 0) {
        console.error('Current stock table not found');
        return;
    }

    if (currentStockTableInstance) {
        currentStockTableInstance.ajax.reload(null, false);
        return;
    }

    // Show loading message
    $table.find('tbody').html('<tr><td colspan="8" class="text-center">در حال بارگذاری...</td></tr>');
    
    currentStockTableInstance = $table.DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/report/warehouse/current-stock',
            type: 'POST',
            data: function(d) {
                return Object.assign(d, getFilterPayload());
            },
            dataSrc: function(json) {
                if (!json.success) {
                    console.error('Failed to load current stock:', json.error || 'Unknown error');
                    $table.find('tbody').html('<tr><td colspan="8" class="text-center text-danger">خطا در بارگذاری داده‌ها</td></tr>');
                    return [];
                }
                return json.data || [];
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX error loading current stock:', error, thrown);
                var message = 'خطا در ارتباط با سرور';
                if (xhr && xhr.status === 401) {
                    message = 'نشست شما منقضی شده است. لطفاً دوباره وارد شوید.';
                } else if (xhr && typeof xhr.responseText === 'string' && xhr.responseText.trim().indexOf('<!DOCTYPE') === 0) {
                    message = 'پاسخ نامعتبر از سرور دریافت شد.';
                }
                $table.find('tbody').html('<tr><td colspan="8" class="text-center text-danger">' + message + '</td></tr>');
            }
        },
        columns: [
            { data: 'inventory_code', title: 'کد کالا' },
            { data: 'inventory_name', title: 'نام کالا' },
            { 
                data: 'current_quantity', 
                title: 'موجودی',
                render: function(data, type, row) {
                    return formatNumber(data) + ' ' + (row.main_unit || '');
                }
            },
            { data: 'main_unit', title: 'واحد' },
            { 
                data: 'current_amount', 
                title: 'ارزش',
                render: function(data) {
                    return formatCurrency(data);
                }
            },
            { 
                data: 'entry_date', 
                title: 'تاریخ ورود',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: null,
                title: 'وضعیت',
                render: function(data, type, row) {
                    var badges = '';
                    if (row.current_quantity <= row.minimum_stock) {
                        badges += '<span class="badge badge-warning">کم</span> ';
                    }
                    if (row.expiry_date && row.expiry_date.is_expired) {
                        badges += '<span class="badge badge-danger">منقضی</span> ';
                    }
                    return badges || '<span class="badge badge-success">عادی</span>';
                }
            },
            {
                data: null,
                title: 'عملیات',
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" class="btn btn-sm btn-outline-info js-view-entries" data-inventory-id="' + row.inventory_id + '" data-inventory-code="' + (row.inventory_code || '') + '" data-inventory-name="' + (row.inventory_name || '') + '">' +
                        '<i class="ti-layers"></i> ورودها</button> ' +
                        '<a href="/inventory/' + row.inventory_id + '" class="btn btn-sm btn-outline-primary">جزئیات</a>';
                }
            }
        ],
        language: {
            url: '/public/js/datatables-persian.json'
        },
        order: [[1, 'asc']]
    });
}

/**
 * Load expiry report
 */
function loadExpiryReport(status) {
    destroyDataTable('#expiry-table');
    
    // Show loading message
    if ($('#expiry-table tbody').length) {
        $('#expiry-table tbody').html('<tr><td colspan="6" class="text-center">در حال بارگذاری...</td></tr>');
    }
    
    $.ajax({
        url: '/report/warehouse/expiry',
        type: 'POST',
        data: getFilterPayload({ status: status || '' }),
        success: function(response) {
            if (response.success && response.data) {
                var data = [];
                var summary = response.data.summary || {};
                
                $('#expired-count').text(summary.expired_count || 0);
                $('#approaching-count').text(summary.approaching_count || 0);
                $('#normal-count').text(summary.normal_count || 0);
                
                // Combine all items
                if (response.data.expired) {
                    data = data.concat(response.data.expired.map(function(item) {
                        return {
                            inventory_code: item.inventory.inventory_code,
                            inventory_name: item.inventory.inventory_name,
                            expiry_date: item.expiry_date,
                            days_until: item.days_until_expiry,
                            current_quantity: item.inventory.current_quantity,
                            status: 'expired'
                        };
                    }));
                }
                
                if (response.data.approaching) {
                    data = data.concat(response.data.approaching.map(function(item) {
                        return {
                            inventory_code: item.inventory.inventory_code,
                            inventory_name: item.inventory.inventory_name,
                            expiry_date: item.expiry_date,
                            days_until: item.days_until_expiry,
                            current_quantity: item.inventory.current_quantity,
                            status: 'approaching'
                        };
                    }));
                }
                
                $('#expiry-table').DataTable({
                    data: data,
                    columns: [
                        { data: 'inventory_code', title: 'کد کالا' },
                        { data: 'inventory_name', title: 'نام کالا' },
                        { data: 'expiry_date', title: 'تاریخ انقضا' },
                        { 
                            data: 'days_until', 
                            title: 'روزهای باقی‌مانده',
                            render: function(data) {
                                if (data < 0) {
                                    return '<span class="text-danger">' + Math.abs(data) + ' روز گذشته</span>';
                                }
                                return data + ' روز';
                            }
                        },
                        { data: 'current_quantity', title: 'موجودی' },
                        {
                            data: 'status',
                            title: 'وضعیت',
                            render: function(data) {
                                if (data === 'expired') {
                                    return '<span class="badge badge-danger">منقضی شده</span>';
                                } else if (data === 'approaching') {
                                    return '<span class="badge badge-warning">نزدیک به انقضا</span>';
                                }
                                return '<span class="badge badge-success">عادی</span>';
                            }
                        }
                    ],
                    language: {
                        url: '/public/js/datatables-persian.json'
                    }
                });
            } else {
                console.error('Failed to load expiry report:', response.error || 'Unknown error');
                if ($('#expiry-table tbody').length) {
                    $('#expiry-table tbody').html('<tr><td colspan="6" class="text-center text-danger">خطا در بارگذاری داده‌ها</td></tr>');
                }
            }
        },
        error: function(xhr, error, thrown) {
            console.error('AJAX error loading expiry report:', error, thrown);
            if ($('#expiry-table tbody').length) {
                $('#expiry-table tbody').html('<tr><td colspan="6" class="text-center text-danger">خطا در ارتباط با سرور</td></tr>');
            }
        }
    });
}

/**
 * Load sales report
 */
function loadSalesReport(year) {
    if (typeof year !== 'undefined') {
        warehouseFilterState.sales_year = year;
    }
    destroyDataTable('#sales-report-table');
    
    $.ajax({
        url: '/report/warehouse/sales',
        type: 'POST',
        data: getFilterPayload({ year: warehouseFilterState.sales_year || year || '' }),
        success: function(response) {
            if (response.success && response.data) {
                $('#sales-report-table').DataTable({
                    data: response.data,
                    columns: [
                        { data: 'product_code', title: 'کد کالا' },
                        { data: 'product_name', title: 'نام کالا' },
                        { data: 'sales_count', title: 'تعداد فروش' },
                        { 
                            data: 'total_quantity', 
                            title: 'مقدار کل',
                            render: function(data, type, row) {
                                return formatNumber(data) + ' ' + (row.unit || '');
                            }
                        },
                        { 
                            data: 'total_amount', 
                            title: 'مبلغ کل',
                            render: function(data) {
                                return formatCurrency(data);
                            }
                        },
                        { 
                            data: 'avg_price', 
                            title: 'میانگین قیمت',
                            render: function(data) {
                                return formatCurrency(data);
                            }
                        }
                    ],
                    language: {
                        url: '/public/js/datatables-persian.json'
                    },
                    order: [[4, 'desc']]
                });
            }
        }
    });
}

/**
 * Load outside inventory
 */
function loadOutsideInventory() {
    $.ajax({
        url: '/report/warehouse/outside-inventory',
        type: 'POST',
        data: getFilterPayload(),
        success: function(response) {
            if (response.success && response.data) {
                var summary = response.data.summary || {};
                $('#negative-stock-count').text(summary.negative_count || 0);
                $('#not-physical-count').text(summary.not_physical_count || 0);
                $('#discrepancy-count').text(summary.discrepancy_count || 0);
                
                // Load tables
                loadTable('#negative-stock-table', response.data.negative_stock || []);
                loadTable('#not-physical-table', response.data.not_physical || []);
                loadTable('#discrepancy-table', response.data.discrepancy || []);
            }
        }
    });
}

/**
 * Load analytics
 */
function loadAnalytics() {
    $.ajax({
        url: '/report/warehouse/analytics',
        type: 'POST',
        data: getFilterPayload(),
        success: function(response) {
            if (response.success && response.data) {
                var analyticsData = response.data;

                // Load top products
                var products = analyticsData.top_products || analyticsData.top_products_by_sales || [];
                if (products && products.length) {
                    loadTopProductsTable(products);
                } else {
                    showEmptyTopProductsTable();
                }
                
                // Load charts if Chart.js is available
                if (typeof Chart !== 'undefined') {
                    renderAnalyticsCharts(analyticsData);
                }
            } else {
                showEmptyTopProductsTable();
            }
        },
        error: function() {
            showEmptyTopProductsTable('خطا در دریافت داده‌های تحلیل');
        }
    });
}

/**
 * Load transactions
 */
function loadTransactions() {
    destroyDataTable('#transactions-table');
    
    $('#transactions-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/report/warehouse/transactions',
            type: 'POST',
            data: function(d) {
                return Object.assign(d, getFilterPayload({
                    transaction_type: $('#transaction-type-filter').val()
                }));
            },
            dataSrc: function(json) {
                return json.success ? json.data : [];
            }
        },
        columns: [
            { data: 'transaction_date', title: 'تاریخ' },
            { 
                data: 'transaction_type', 
                title: 'نوع',
                render: function(data) {
                    return data === 'input' ? '<span class="badge badge-success">ورود</span>' : '<span class="badge badge-danger">خروج</span>';
                }
            },
            { data: 'inventory.inventory_name', title: 'کالا' },
            { data: 'quantity', title: 'مقدار' },
            { 
                data: 'amount', 
                title: 'مبلغ',
                render: function(data) {
                    return formatCurrency(data);
                }
            },
            { data: 'warehouse', title: 'انبار' },
            { data: 'document_number', title: 'شماره سند' },
            { data: 'user.first_name', title: 'کاربر' }
        ],
        language: {
            url: '/public/js/datatables-persian.json'
        },
        order: [[0, 'desc']]
    });
}

/**
 * Helper function to load simple table
 */
function loadTable(selector, data) {
    destroyDataTable(selector);
    
    $(selector).DataTable({
        data: data,
        columns: [
            { data: 'inventory_code', title: 'کد کالا' },
            { data: 'inventory_name', title: 'نام کالا' },
            { data: 'current_quantity', title: 'موجودی' },
            { data: 'main_unit', title: 'واحد' }
        ],
        language: {
            url: '/public/js/datatables-persian.json'
        }
    });
}

/**
 * Load top products table
 */
function loadTopProductsTable(data) {
    var $table = $('#top-products-table');
    if (!$table.length) {
        return;
    }

    if (!data || !data.length) {
        showEmptyTopProductsTable();
        return;
    }

    destroyDataTable('#top-products-table');
    
    $('#top-products-table').DataTable({
        data: data.map(function(item, index) {
            return {
                rank: index + 1,
                name: item.product_name,
                quantity: item.total_quantity,
                amount: item.total_amount
            };
        }),
        columns: [
            { data: 'rank', title: 'رتبه' },
            { data: 'name', title: 'نام کالا' },
            { data: 'quantity', title: 'مقدار فروش' },
            { 
                data: 'amount', 
                title: 'مبلغ کل',
                render: function(data) {
                    return formatCurrency(data);
                }
            }
        ],
        language: {
            url: '/public/js/datatables-persian.json'
        }
    });
}

function showEmptyTopProductsTable(message) {
    var $table = $('#top-products-table');
    if (!$table.length) {
        return;
    }
    destroyDataTable('#top-products-table');
    $table.find('tbody').html('<tr><td colspan="4" class="text-center text-muted">' + (message || 'داده‌ای برای نمایش وجود ندارد') + '</td></tr>');
}

function renderAnalyticsCharts(data) {
    renderMonthlySalesChart(data.monthly_trend || []);
    renderCategoryDistributionChart(data.category_distribution || []);
}

function renderMonthlySalesChart(trend) {
    if (typeof Chart === 'undefined') {
        return;
    }
    var canvas = document.getElementById('monthly-sales-chart');
    if (!canvas) {
        return;
    }

    var labels = trend.map(function(item) {
        return formatMonthLabel(item.year, item.month);
    });
    var quantities = trend.map(function(item) {
        return Number(item.total_quantity) || 0;
    });
    var amounts = trend.map(function(item) {
        return Number(item.total_amount) || 0;
    });

    if (!labels.length) {
        labels = ['بدون داده'];
        quantities = [0];
        amounts = [0];
    }

    if (monthlySalesChart) {
        monthlySalesChart.destroy();
    }

    monthlySalesChart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'مقدار فروش',
                    data: quantities,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    tension: 0.3,
                    yAxisID: 'yQuantity',
                    fill: true
                },
                {
                    label: 'مبلغ فروش',
                    data: amounts,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.15)',
                    tension: 0.3,
                    yAxisID: 'yAmount',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            stacked: false,
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.dataset.label || '';
                            var value = context.parsed.y || 0;
                            if (context.dataset.yAxisID === 'yAmount') {
                                return label + ': ' + formatCurrency(value);
                            }
                            return label + ': ' + formatNumber(value);
                        }
                    }
                }
            },
            scales: {
                yQuantity: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                yAmount: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
}

function renderCategoryDistributionChart(distribution) {
    if (typeof Chart === 'undefined') {
        return;
    }
    var canvas = document.getElementById('category-distribution-chart');
    if (!canvas) {
        return;
    }

    var labels = distribution.map(function(item) {
        return item.category_name || ('دسته #' + (item.inventory_categoryid || 'نامشخص'));
    });
    var values = distribution.map(function(item) {
        return Number(item.total_value) || 0;
    });

    if (!labels.length) {
        labels = ['داده‌ای وجود ندارد'];
        values = [1];
    }

    var colors = generateColorPalette(labels.length);

    if (categoryDistributionChart) {
        categoryDistributionChart.destroy();
    }

    categoryDistributionChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.parsed || 0;
                            return label + ': ' + formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
}

function formatMonthLabel(year, month) {
    var m = ('0' + (month || 0)).slice(-2);
    return year ? (year + '/' + m) : m;
}

function generateColorPalette(count) {
    var baseColors = [
        '#3b82f6', '#10b981', '#f97316', '#ef4444',
        '#8b5cf6', '#ec4899', '#14b8a6', '#facc15',
        '#0ea5e9', '#6366f1'
    ];
    var colors = [];
    for (var i = 0; i < count; i++) {
        colors.push(baseColors[i % baseColors.length]);
    }
    return colors;
}

/**
 * Format currency
 */
function formatCurrency(value) {
    if (!value) return '0';
    return new Intl.NumberFormat('fa-IR').format(value) + ' ریال';
}

/**
 * Format number
 */
function formatNumber(value) {
    if (!value) return '0';
    return new Intl.NumberFormat('fa-IR').format(value);
}

/**
 * Load inventory entries table
 */
var inventoryEntriesTableInstance = null;
function loadInventoryEntries() {
    var $table = $('#inventory-entries-table');
    if ($table.length === 0) {
        return;
    }

    if (inventoryEntriesTableInstance) {
        inventoryEntriesTableInstance.ajax.reload(null, false);
        return;
    }

    $table.find('tbody').html('<tr><td colspan="10" class="text-center">در حال بارگذاری...</td></tr>');

    inventoryEntriesTableInstance = $table.DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/report/warehouse/list-entries',
            type: 'POST',
            data: function(d) {
                var payload = getFilterPayload();
                var entryType = $('#entries-type-filter').val();
                if (entryType) {
                    payload.entry_type = entryType;
                }
                return payload;
            },
            dataSrc: function(json) {
                if (!json.success) {
                    console.error('Failed to load entries:', json.error || 'Unknown error');
                    $table.find('tbody').html('<tr><td colspan="10" class="text-center text-danger">خطا در بارگذاری داده‌ها</td></tr>');
                    return [];
                }
                return json.data || [];
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX error loading entries:', error, thrown);
                $table.find('tbody').html('<tr><td colspan="10" class="text-center text-danger">خطا در ارتباط با سرور</td></tr>');
            }
        },
        columns: [
            { data: 'inventory_code', title: 'کد کالا' },
            { data: 'inventory_name', title: 'نام کالا' },
            { 
                data: 'entry_date', 
                title: 'تاریخ',
                render: function(data) {
                    return data || '-';
                }
            },
            { data: 'entry_code', title: 'سند' },
            { 
                data: 'entry_type', 
                title: 'نوع',
                render: function(data) {
                    if (data === 'ورودی' || data === 'input' || data === 'IN') {
                        return '<span class="badge badge-success">ورودی</span>';
                    } else if (data === 'خروجی' || data === 'output' || data === 'OUT') {
                        return '<span class="badge badge-danger">خروجی</span>';
                    }
                    return data || '-';
                }
            },
            { data: 'document_number', title: 'شماره سند مبنا' },
            { 
                data: 'quantity', 
                title: 'مقدار',
                render: function(data) {
                    return formatNumber(data);
                }
            },
            { 
                data: 'unit_price', 
                title: 'فی',
                render: function(data) {
                    return formatCurrency(data);
                }
            },
            { 
                data: 'total_amount', 
                title: 'مبلغ تمام شده',
                render: function(data) {
                    return formatCurrency(data);
                }
            },
            {
                data: null,
                title: 'عملیات',
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" class="btn btn-sm btn-outline-info js-view-item-entries" data-inventory-id="' + row.inventory_id + '" data-inventory-code="' + (row.inventory_code || '') + '" data-inventory-name="' + (row.inventory_name || '') + '">' +
                        '<i class="ti-eye"></i> جزئیات</button>';
                }
            }
        ],
        language: {
            url: '/public/js/datatables-persian.json'
        },
        order: [[2, 'desc']]
    });

    // Handle view item entries button
    $(document).off('click', '.js-view-item-entries').on('click', '.js-view-item-entries', function () {
        var inventoryId = $(this).data('inventory-id');
        var inventoryCode = $(this).data('inventory-code');
        var inventoryName = $(this).data('inventory-name');
        showInventoryEntriesModal(inventoryId, inventoryCode, inventoryName);
    });
}

/**
 * Show inventory entries modal for a specific item
 */
function showInventoryEntriesModal(inventoryId, inventoryCode, inventoryName) {
    $('#entries-modal-code').text(inventoryCode || '-');
    $('#entries-modal-name').text(inventoryName || '-');
    $('#entries-modal-title').text('ورودهای کالا: ' + (inventoryName || inventoryCode || ''));
    
    var $modalTable = $('#inventory-item-entries-table');
    $modalTable.find('tbody').html('<tr><td colspan="7" class="text-center">در حال بارگذاری...</td></tr>');

    $.ajax({
        url: '/report/warehouse/inventory-entries',
        type: 'GET',
        data: { inventory_id: inventoryId },
        success: function(response) {
            if (response.success && response.data) {
                var entries = response.data;
                if (entries.length === 0) {
                    $modalTable.find('tbody').html('<tr><td colspan="7" class="text-center text-muted">هیچ ورودی ثبت نشده است</td></tr>');
                    return;
                }

                var rows = entries.map(function(entry) {
                    var typeBadge = (entry.entry_type === 'ورودی' || entry.entry_type === 'input' || entry.entry_type === 'IN') 
                        ? '<span class="badge badge-success">ورودی</span>' 
                        : '<span class="badge badge-danger">خروجی</span>';
                    
                    return '<tr>' +
                        '<td>' + (entry.entry_date || '-') + '</td>' +
                        '<td>' + (entry.entry_code || '-') + '</td>' +
                        '<td>' + typeBadge + '</td>' +
                        '<td>' + (entry.document_number || '-') + '</td>' +
                        '<td>' + formatNumber(entry.quantity) + '</td>' +
                        '<td>' + formatCurrency(entry.unit_price) + '</td>' +
                        '<td>' + formatCurrency(entry.total_amount) + '</td>' +
                        '</tr>';
                }).join('');

                $modalTable.find('tbody').html(rows);
            } else {
                $modalTable.find('tbody').html('<tr><td colspan="7" class="text-center text-danger">خطا در بارگذاری داده‌ها</td></tr>');
            }
        },
        error: function() {
            $modalTable.find('tbody').html('<tr><td colspan="7" class="text-center text-danger">خطا در ارتباط با سرور</td></tr>');
        }
    });

    showModal('#inventoryEntriesModal');
}

