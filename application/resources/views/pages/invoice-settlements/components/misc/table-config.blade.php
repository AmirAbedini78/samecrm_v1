<div class="right-sidebar" id="table-config-invoice-settlements">
    <form id="table-config-invoice-settlements-form">
        <div class="slimscrollright">
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.table_settings')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="table-config-invoice-settlements"></i>
                </span>
            </div>
            <div class="r-panel-body table-config-ajax"
                data-url="{{ url('preferences/tables') }}"
                data-type="form"
                data-form-id="table-config-invoice-settlements-form"
                data-ajax-type="post"
                data-progress-bar="hidden">

                @php
                    $columns = [
                        1 => __('lang.document_number'),
                        2 => __('lang.document_date'),
                        3 => __('lang.customer_name'),
                        4 => __('lang.base_net_amount'),
                        5 => __('lang.paid_amount'),
                        6 => __('lang.balance_amount'),
                        7 => __('lang.currency'),
                        8 => __('lang.created_by'),
                        9 => __('lang.created_at'),
                    ];
                @endphp

                @foreach($columns as $index => $label)
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_{{ $index }}" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_'.$index)) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">{{ cleanLang($label) }}</span>
                    </label>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="tableconfig_table_name" value="invoice_settlements">
            <div class="buttons-block">
                <button type="button" class="btn btn-rounded-x btn-secondary js-close-side-panels"
                    data-target="table-config-invoice-settlements">{{ cleanLang(__('lang.close')) }}</button>
            </div>
        </div>
    </form>
</div>

