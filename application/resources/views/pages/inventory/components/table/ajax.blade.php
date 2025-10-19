@foreach($inventory as $item)
<!--each row-->
<tr id="inventory_{{ $item->inventory_id  }}" class="{{ $item->pinned_status ?? '' }}">
    @if(config('visibility.inventory_col_checkboxes'))
    <td class="inventory_col_checkbox checkitem" id="inventory_col_checkbox_{{ $item->inventory_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-inventory-{{ $item->inventory_id }}"
                name="ids[{{ $item->inventory_id }}]"
                class="listcheckbox listcheckbox-inventory filled-in chk-col-light-blue"
                data-actions-container-class="inventory-checkbox-actions-container">
            <label for="listcheckbox-inventory-{{ $item->inventory_id }}"></label>
        </span>
    </td>
    @endif

    <!--tableconfig_column_1 [id]-->
    <td class="inventory_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1"
        id="inventory_col_id_{{ $item->inventory_id }}">
        <a href="/inventory/{{ $item->inventory_id }}">
            {{ $item->formatted_id }} </a>
    </td>

    <!--tableconfig_column_2 [inventory name]-->
    <td class="inventory_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2"
        id="inventory_col_name_{{ $item->inventory_id }}">
        <a href="/inventory/{{ $item->inventory_id }}">{{ str_limit($item->inventory_name ?? '---', 22) }}</a>
    </td>

    <!--tableconfig_column_3 [inventory code]-->
    <td class="inventory_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3"
        id="inventory_col_code_{{ $item->inventory_id }}">
        {{ $item->inventory_code ?? '---' }}
    </td>

    <!--tableconfig_column_4 [current quantity]-->
    <td class="inventory_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4"
        id="inventory_col_quantity_{{ $item->inventory_id }}">
        {{ number_format($item->current_quantity, 2) }}
    </td>

    <!--tableconfig_column_5 [current avg price]-->
    <td class="inventory_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5"
        id="inventory_col_price_{{ $item->inventory_id }}">
        {{ formatCurrency($item->current_avg_price, 'IRR') }}
    </td>

    <!--tableconfig_column_6 [current amount]-->
    <td class="inventory_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6"
        id="inventory_col_amount_{{ $item->inventory_id }}">
        {{ formatCurrency($item->current_amount, 'IRR') }}
    </td>

    <!--tableconfig_column_7 [minimum stock]-->
    <td class="inventory_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7"
        id="inventory_col_minimum_{{ $item->inventory_id }}">
        {{ number_format($item->minimum_stock, 2) }}
    </td>

    <!--tableconfig_column_8 [category]-->
    <td class="inventory_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8"
        id="inventory_col_category_{{ $item->inventory_id }}">
        @if(isset($item->category) && $item->category)
        <span class="label label-default">{{ $item->category->category_name }}</span>
        @else
        <span>---</span>
        @endif
    </td>

    <!--tableconfig_column_9 [created by]-->
    <td class="inventory_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9"
        id="inventory_col_creator_{{ $item->inventory_id }}">
        <span class="printing_hidden">
            <img src="{{ getUsersAvatar($item->avatar_directory, $item->avatar_filename, $item->inventory_creatorid) }}"
                alt="user" class="img-circle avatar-xsmall printing_hidden">
            <span
                class="user-profile-first-name">{{ checkUsersName($item->first_name, $item->inventory_creatorid)  }}</span>
        </span>

        <!--print view-->
        <span class="hidden printing_visible">
            {{ $item->first_name ?? runtimeUnkownUser() }} {{ $item->last_name ?? '' }}
        </span>
    </td>

    <!--tableconfig_column_10 [date created]-->
    <td class="inventory_col_tableconfig_column_10 {{ config('table.tableconfig_column_10') }} tableconfig_column_10"
        id="inventory_col_created_{{ $item->inventory_id }}">
        {{ runtimeDate($item->created_at) }}
    </td>

    <!--tableconfig_column_11 [status]-->
    <td class="inventory_col_tableconfig_column_11 {{ config('table.tableconfig_column_11') }} tableconfig_column_11"
        id="inventory_col_status_{{ $item->inventory_id }}">

        <span class="label {{ $item->inventory_status == 'active' ? 'label-success' : 'label-default' }}">{{
            runtimeLang($item->inventory_status) }}</span>

        <!--low stock warning-->
        @if($item->current_quantity <= $item->minimum_stock)
        <span class="label label-icons label-icons-warning" data-toggle="tooltip" data-placement="top"
            title="@lang('lang.low_stock_warning')"><i
                class="sl-icon-warning"></i></span>
        @endif

    </td>

    <!-- Additional columns for inventory -->
    <!--tableconfig_column_12 [first_period_quantity]-->
    <td class="inventory_col_tableconfig_column_12 {{ config('table.tableconfig_column_12') }} tableconfig_column_12"
        id="inventory_col_first_period_quantity_{{ $item->inventory_id }}">
        {{ number_format($item->first_period_quantity, 2) }}
    </td>

    <!--tableconfig_column_13 [first_period_amount]-->
    <td class="inventory_col_tableconfig_column_13 {{ config('table.tableconfig_column_13') }} tableconfig_column_13"
        id="inventory_col_first_period_amount_{{ $item->inventory_id }}">
        {{ formatCurrency($item->first_period_amount, 'IRR') }}
    </td>

    <!--tableconfig_column_14 [input_quantity]-->
    <td class="inventory_col_tableconfig_column_14 {{ config('table.tableconfig_column_14') }} tableconfig_column_14"
        id="inventory_col_input_quantity_{{ $item->inventory_id }}">
        {{ number_format($item->input_quantity, 2) }}
    </td>

    <!--tableconfig_column_15 [input_amount]-->
    <td class="inventory_col_tableconfig_column_15 {{ config('table.tableconfig_column_15') }} tableconfig_column_15"
        id="inventory_col_input_amount_{{ $item->inventory_id }}">
        {{ formatCurrency($item->input_amount, 'IRR') }}
    </td>

    <!--tableconfig_column_16 [output_quantity]-->
    <td class="inventory_col_tableconfig_column_16 {{ config('table.tableconfig_column_16') }} tableconfig_column_16"
        id="inventory_col_output_quantity_{{ $item->inventory_id }}">
        {{ number_format($item->output_quantity, 2) }}
    </td>

    <!--tableconfig_column_17 [output_amount]-->
    <td class="inventory_col_tableconfig_column_17 {{ config('table.tableconfig_column_17') }} tableconfig_column_17"
        id="inventory_col_output_amount_{{ $item->inventory_id }}">
        {{ formatCurrency($item->output_amount, 'IRR') }}
    </td>

    <!--tableconfig_column_18 [current_sub_quantity]-->
    <td class="inventory_col_tableconfig_column_18 {{ config('table.tableconfig_column_18') }} tableconfig_column_18"
        id="inventory_col_current_sub_quantity_{{ $item->inventory_id }}">
        {{ number_format($item->current_sub_quantity, 2) }}
    </td>

    <!--tableconfig_column_19 [weighing_input]-->
    <td class="inventory_col_tableconfig_column_19 {{ config('table.tableconfig_column_19') }} tableconfig_column_19"
        id="inventory_col_weighing_input_{{ $item->inventory_id }}">
        {{ number_format($item->weighing_input, 2) }}
    </td>

    <!--tableconfig_column_20 [weighing_output]-->
    <td class="inventory_col_tableconfig_column_20 {{ config('table.tableconfig_column_20') }} tableconfig_column_20"
        id="inventory_col_weighing_output_{{ $item->inventory_id }}">
        {{ number_format($item->weighing_output, 2) }}
    </td>

    <!--tableconfig_column_21 [maximum_stock]-->
    <td class="inventory_col_tableconfig_column_21 {{ config('table.tableconfig_column_21') }} tableconfig_column_21"
        id="inventory_col_maximum_stock_{{ $item->inventory_id }}">
        {{ $item->maximum_stock ? number_format($item->maximum_stock, 2) : '---' }}
    </td>

    <!--tableconfig_column_22 [discrepancy]-->
    <td class="inventory_col_tableconfig_column_22 {{ config('table.tableconfig_column_22') }} tableconfig_column_22"
        id="inventory_col_discrepancy_{{ $item->inventory_id }}">
        {{ number_format($item->discrepancy, 2) }}
    </td>

    <!--tableconfig_column_23 [main_unit]-->
    <td class="inventory_col_tableconfig_column_23 {{ config('table.tableconfig_column_23') }} tableconfig_column_23"
        id="inventory_col_main_unit_{{ $item->inventory_id }}">
        {{ $item->main_unit ?? '---' }}
    </td>

    <!--tableconfig_column_24 [sub_unit]-->
    <td class="inventory_col_tableconfig_column_24 {{ config('table.tableconfig_column_24') }} tableconfig_column_24"
        id="inventory_col_sub_unit_{{ $item->inventory_id }}">
        {{ $item->sub_unit ?? '---' }}
    </td>

    <!--tableconfig_column_25 [updated_at]-->
    <td class="inventory_col_tableconfig_column_25 {{ config('table.tableconfig_column_25') }} tableconfig_column_25"
        id="inventory_col_updated_at_{{ $item->inventory_id }}">
        {{ runtimeDate($item->updated_at) }}
    </td>

    <!--actions-->
    <td class="inventory_col_action actions_column" id="inventory_col_action_{{ $item->inventory_id }}">
        <!--action button-->
        <span class="list-table-action font-size-inherit">

            <!--delete-->
            @if(config('visibility.action_buttons_delete'))
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_inventory_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/inventory/{{ $item->inventory_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            @if(config('visibility.action_buttons_edit'))
            <a href="/inventory/{{ $item->inventory_id }}/edit" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="sl-icon-note"></i>
            </a>
            @endif
            <a href="/inventory/{{ $item->inventory_id }}" title="{{ cleanLang(__('lang.view')) }}"
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
                        data-url="{{ urlResource('/inventory/'.$item->inventory_id.'/edit') }}"
                        data-loading-target="commonModalBody"
                        data-modal-title="{{ cleanLang(__('lang.edit_inventory_item')) }}"
                        data-action-url="{{ urlResource('/inventory/'.$item->inventory_id.'?ref=list') }}"
                        data-action-method="PUT" data-action-ajax-class=""
                        data-action-ajax-loading-target="inventory-td-container">
                        {{ cleanLang(__('lang.quick_edit')) }}
                    </a>
                    @endif
                    <!--change category-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                        data-url="{{ url('/inventory/change-category') }}"
                        data-action-url="{{ urlResource('/inventory/change-category?id='.$item->inventory_id) }}"
                        data-loading-target="actionsModalBody" data-action-method="POST">
                        {{ cleanLang(__('lang.change_category')) }}</a>
                    <!--inventory details-->
                    <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.inventory_details')) }}"
                        data-url="{{ urlResource('/inventory/'.$item->inventory_id.'/inventory-details') }}"
                        data-loading-target="actionsModalBody">
                        {{ cleanLang(__('lang.inventory_details')) }}</a>
                    <a class="dropdown-item" href="{{ url('/inventory/'.$item->inventory_id) }}">
                        {{ cleanLang(__('lang.view')) }}</a>
                </div>
            </span>
            @endif
            <!--more button-->

            <!--pin-->
            <span class="list-table-action">
                <a href="javascript:void(0);" title="{{ cleanLang(__('lang.pinning')) }}"
                    data-parent="inventory_{{ $item->inventory_id }}"
                    data-url="{{ url('/inventory/'.$item->inventory_id.'/pinning') }}"
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