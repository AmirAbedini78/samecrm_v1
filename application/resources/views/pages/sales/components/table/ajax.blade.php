@foreach($sales as $item)
<!--each row-->
<tr id="sales_{{ $item->sales_id  }}" class="{{ $item->pinned_status ?? '' }}">
    @if(config('visibility.sales_col_checkboxes'))
    <td class="sales_col_checkbox checkitem" id="sales_col_checkbox_{{ $item->sales_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-sales-{{ $item->sales_id }}"
                name="ids[{{ $item->sales_id }}]"
                class="listcheckbox listcheckbox-sales filled-in chk-col-light-blue"
                data-actions-container-class="sales-checkbox-actions-container">
            <label for="listcheckbox-sales-{{ $item->sales_id }}"></label>
        </span>
    </td>
    @endif

    <!--tableconfig_column_1 [id]-->
    <td class="sales_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1"
        id="sales_col_id_{{ $item->sales_id }}">
        <a href="/sales/{{ $item->sales_id }}">
            {{ $item->formatted_id }} </a>
    </td>

    <!--tableconfig_column_2 [document number]-->
    <td class="sales_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2"
        id="sales_col_document_{{ $item->sales_id }}">
        <a href="/sales/{{ $item->sales_id }}">{{ str_limit($item->document_number ?? '---', 22) }}</a>
    </td>

    <!--tableconfig_column_3 [customer name]-->
    <td class="sales_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3"
        id="sales_col_customer_{{ $item->sales_id }}">
        {{ str_limit($item->customer_name ?? '---', 22) }}
    </td>

    <!--tableconfig_column_4 [product name]-->
    <td class="sales_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4"
        id="sales_col_product_{{ $item->sales_id }}">
        {{ str_limit($item->product_name ?? '---', 22) }}
    </td>

    <!--tableconfig_column_5 [main quantity]-->
    <td class="sales_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5"
        id="sales_col_quantity_{{ $item->sales_id }}">
        {{ number_format($item->main_quantity, 2) }}
    </td>

    <!--tableconfig_column_6 [base price]-->
    <td class="sales_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6"
        id="sales_col_price_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_price, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_7 [base net amount]-->
    <td class="sales_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7"
        id="sales_col_amount_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_net_amount, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_8 [document type]-->
    <td class="sales_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8"
        id="sales_col_type_{{ $item->sales_id }}">
        <span class="label label-default">{{ runtimeLang($item->document_type ?? '---') }}</span>
    </td>

    <!--tableconfig_column_9 [created by]-->
    <td class="sales_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9"
        id="sales_col_creator_{{ $item->sales_id }}">
        <span class="printing_hidden">
            <img src="{{ getUsersAvatar($item->avatar_directory, $item->avatar_filename, $item->sales_creatorid) }}"
                alt="user" class="img-circle avatar-xsmall printing_hidden">
            <span
                class="user-profile-first-name">{{ checkUsersName($item->first_name, $item->sales_creatorid)  }}</span>
        </span>

        <!--print view-->
        <span class="hidden printing_visible">
            {{ $item->first_name ?? runtimeUnkownUser() }} {{ $item->last_name ?? '' }}
        </span>
    </td>

    <!--tableconfig_column_10 [document date]-->
    <td class="sales_col_tableconfig_column_10 {{ config('table.tableconfig_column_10') }} tableconfig_column_10"
        id="sales_col_date_{{ $item->sales_id }}">
        {{ runtimeDate($item->document_date) }}
    </td>

    <!--tableconfig_column_11 [sales status]-->
    <td class="sales_col_tableconfig_column_11 {{ config('table.tableconfig_column_11') }} tableconfig_column_11"
        id="sales_col_status_{{ $item->sales_id }}">

        <span class="label {{ $item->sales_status == 'completed' ? 'label-success' : ($item->sales_status == 'pending' ? 'label-warning' : 'label-danger') }}">{{
            runtimeLang($item->sales_status) }}</span>

    </td>

    <!-- Additional columns for sales -->
    <!--tableconfig_column_12 [customer_code]-->
    <td class="sales_col_tableconfig_column_12 {{ config('table.tableconfig_column_12') }} tableconfig_column_12"
        id="sales_col_customer_code_{{ $item->sales_id }}">
        {{ $item->customer_code ?? '---' }}
    </td>

    <!--tableconfig_column_13 [customer_full_name]-->
    <td class="sales_col_tableconfig_column_13 {{ config('table.tableconfig_column_13') }} tableconfig_column_13"
        id="sales_col_customer_full_name_{{ $item->sales_id }}">
        {{ $item->customer_full_name ?? '---' }}
    </td>

    <!--tableconfig_column_14 [sales_type]-->
    <td class="sales_col_tableconfig_column_14 {{ config('table.tableconfig_column_14') }} tableconfig_column_14"
        id="sales_col_sales_type_{{ $item->sales_id }}">
        {{ runtimeLang($item->sales_type) }}
    </td>

    <!--tableconfig_column_15 [product_code]-->
    <td class="sales_col_tableconfig_column_15 {{ config('table.tableconfig_column_15') }} tableconfig_column_15"
        id="sales_col_product_code_{{ $item->sales_id }}">
        {{ $item->product_code ?? '---' }}
    </td>

    <!--tableconfig_column_16 [product_barcode]-->
    <td class="sales_col_tableconfig_column_16 {{ config('table.tableconfig_column_16') }} tableconfig_column_16"
        id="sales_col_product_barcode_{{ $item->sales_id }}">
        {{ $item->product_barcode ?? '---' }}
    </td>

    <!--tableconfig_column_17 [tracking_code]-->
    <td class="sales_col_tableconfig_column_17 {{ config('table.tableconfig_column_17') }} tableconfig_column_17"
        id="sales_col_tracking_code_{{ $item->sales_id }}">
        {{ $item->tracking_code ?? '---' }}
    </td>

    <!--tableconfig_column_18 [main_unit]-->
    <td class="sales_col_tableconfig_column_18 {{ config('table.tableconfig_column_18') }} tableconfig_column_18"
        id="sales_col_main_unit_{{ $item->sales_id }}">
        {{ $item->main_unit ?? '---' }}
    </td>

    <!--tableconfig_column_19 [warehouse]-->
    <td class="sales_col_tableconfig_column_19 {{ config('table.tableconfig_column_19') }} tableconfig_column_19"
        id="sales_col_warehouse_{{ $item->sales_id }}">
        {{ $item->warehouse ?? '---' }}
    </td>

    <!--tableconfig_column_20 [base_sales_amount]-->
    <td class="sales_col_tableconfig_column_20 {{ config('table.tableconfig_column_20') }} tableconfig_column_20"
        id="sales_col_base_sales_amount_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_sales_amount, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_21 [base_tax_amount]-->
    <td class="sales_col_tableconfig_column_21 {{ config('table.tableconfig_column_21') }} tableconfig_column_21"
        id="sales_col_base_tax_amount_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_tax_amount, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_22 [base_duty_amount]-->
    <td class="sales_col_tableconfig_column_22 {{ config('table.tableconfig_column_22') }} tableconfig_column_22"
        id="sales_col_base_duty_amount_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_duty_amount, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_23 [base_additional_amount]-->
    <td class="sales_col_tableconfig_column_23 {{ config('table.tableconfig_column_23') }} tableconfig_column_23"
        id="sales_col_base_additional_amount_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_additional_amount, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_24 [base_increasing_factors]-->
    <td class="sales_col_tableconfig_column_24 {{ config('table.tableconfig_column_24') }} tableconfig_column_24"
        id="sales_col_base_increasing_factors_{{ $item->sales_id }}">
        {{ formatCurrency($item->base_increasing_factors, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_25 [month]-->
    <td class="sales_col_tableconfig_column_25 {{ config('table.tableconfig_column_25') }} tableconfig_column_25"
        id="sales_col_month_{{ $item->sales_id }}">
        {{ $item->month ?? '---' }}
    </td>

    <!--tableconfig_column_26 [description]-->
    <td class="sales_col_tableconfig_column_26 {{ config('table.tableconfig_column_26') }} tableconfig_column_26"
        id="sales_col_description_{{ $item->sales_id }}">
        {{ str_limit($item->description ?? '---', 30) }}
    </td>

    <!--tableconfig_column_27 [issued_main_quantity]-->
    <td class="sales_col_tableconfig_column_27 {{ config('table.tableconfig_column_27') }} tableconfig_column_27"
        id="sales_col_issued_main_quantity_{{ $item->sales_id }}">
        {{ number_format($item->issued_main_quantity, 2) }}
    </td>

    <!--tableconfig_column_28 [issued_sub_quantity]-->
    <td class="sales_col_tableconfig_column_28 {{ config('table.tableconfig_column_28') }} tableconfig_column_28"
        id="sales_col_issued_sub_quantity_{{ $item->sales_id }}">
        {{ number_format($item->issued_sub_quantity, 2) }}
    </td>

    <!--tableconfig_column_29 [remaining_main_quantity]-->
    <td class="sales_col_tableconfig_column_29 {{ config('table.tableconfig_column_29') }} tableconfig_column_29"
        id="sales_col_remaining_main_quantity_{{ $item->sales_id }}">
        {{ number_format($item->remaining_main_quantity, 2) }}
    </td>

    <!--tableconfig_column_30 [remaining_sub_quantity]-->
    <td class="sales_col_tableconfig_column_30 {{ config('table.tableconfig_column_30') }} tableconfig_column_30"
        id="sales_col_remaining_sub_quantity_{{ $item->sales_id }}">
        {{ number_format($item->remaining_sub_quantity, 2) }}
    </td>

    <!--tableconfig_column_31 [currency]-->
    <td class="sales_col_tableconfig_column_31 {{ config('table.tableconfig_column_31') }} tableconfig_column_31"
        id="sales_col_currency_{{ $item->sales_id }}">
        {{ $item->currency ?? 'IRR' }}
    </td>

    <!--tableconfig_column_32 [updated_at]-->
    <td class="sales_col_tableconfig_column_32 {{ config('table.tableconfig_column_32') }} tableconfig_column_32"
        id="sales_col_updated_at_{{ $item->sales_id }}">
        {{ runtimeDate($item->updated_at) }}
    </td>

    <!--actions-->
    <td class="sales_col_action actions_column" id="sales_col_action_{{ $item->sales_id }}">
        <!--action button-->
        <span class="list-table-action font-size-inherit">

            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_sales_record')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/sales/{{ $item->sales_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <a href="/sales/{{ $item->sales_id }}/edit" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="sl-icon-note"></i>
            </a>
            @endif
            <a href="/sales/{{ $item->sales_id }}" title="{{ cleanLang(__('lang.view')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>

            <!--more button (team)-->
            @if(auth()->user()->is_team)
            <span class="list-table-action dropdown font-size-inherit">
                <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" title="{{ cleanLang(__('lang.more')) }}"
                    class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
                    <i class="ti-more"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="listTableAction">
                    @if(config('visibility.action_buttons_edit'))
                    <!--quick edit-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form edit-add-modal-button"
                        data-toggle="modal" data-target="#commonModal"
                        data-url="{{ urlResource('/sales/'.$item->sales_id.'/edit') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.edit_sales_record')) }}"
                        data-action-url="{{ urlResource('/sales/'.$item->sales_id.'?ref=list') }}"
                        data-action-method="PUT" data-action-ajax-class=""
                        data-action-ajax-loading-target="sales-td-container">
                        {{ cleanLang(__('lang.quick_edit')) }}
                    </a>
                    @endif
                    <!--change category-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                        data-url="{{ url('/sales/change-category') }}"
                        data-action-url="{{ urlResource('/sales/change-category?id='.$item->sales_id) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.change_category')) }}</a>
                    <!--sales details-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.sales_details')) }}"
                        data-url="{{ urlResource('/sales/'.$item->sales_id.'/sales-details') }}"
                        data-loading-target="actionsModalBody">
                        {{ cleanLang(__('lang.sales_details')) }}</a>
                    <a class="dropdown-item" href="{{ url('/sales/'.$item->sales_id) }}">
                        {{ cleanLang(__('lang.view')) }}</a>
                </div>
            </span>
            @endif
            <!--more button-->

            <!--pin-->
            <span class="list-table-action">
                <a href="javascript:void(0);" title="{{ cleanLang(__('lang.pinning')) }}"
                    data-parent="sales_{{ $item->sales_id }}"
                    data-url="{{ url('/sales/'.$item->sales_id.'/pinning') }}"
                    class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm opacity-4 js-toggle-pinning">
                    <i class="ti-pin2"></i>
                </a>
            </span>
        </span>
        <!--action button-->

    </td>
</tr>
@endforeach
<!--each row-->