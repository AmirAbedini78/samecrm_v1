@foreach($guarantees as $item)
<!--each row-->
<tr id="guarantee_{{ $item->guarantee_id  }}" class="{{ $item->pinned_status ?? '' }}">
    @if(config('visibility.guarantee_letters_col_checkboxes'))
    <td class="guarantee_letters_col_checkbox checkitem" id="guarantee_letters_col_checkbox_{{ $item->guarantee_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-guarantees-{{ $item->guarantee_id }}"
                name="ids[{{ $item->guarantee_id }}]"
                class="listcheckbox listcheckbox-guarantees filled-in chk-col-light-blue"
                data-actions-container-class="guarantees-checkbox-actions-container">
            <label for="listcheckbox-guarantees-{{ $item->guarantee_id }}"></label>
        </span>
    </td>
    @endif

    <!--tableconfig_column_1 [id]-->
    <td class="guarantee_letters_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1"
        id="guarantee_letters_col_id_{{ $item->guarantee_id }}">
        <a href="/guarantee-letters/{{ $item->guarantee_id }}">
            {{ $item->formatted_id }} </a>
    </td>

    <!--tableconfig_column_2 [guarantee number]-->
    <td class="guarantee_letters_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2"
        id="guarantee_letters_col_number_{{ $item->guarantee_id }}">
        <a href="/guarantee-letters/{{ $item->guarantee_id }}">{{ str_limit($item->guarantee_number ?? '---', 22) }}</a>
    </td>

    <!--tableconfig_column_3 [guarantee type]-->
    <td class="guarantee_letters_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3"
        id="guarantee_letters_col_type_{{ $item->guarantee_id }}">
        <span class="badge bg-info">{{ $item->guarantee_type ?? '---' }}</span>
    </td>

    <!--tableconfig_column_4 [industrial type]-->
    <td class="guarantee_letters_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4"
        id="guarantee_letters_col_industrial_{{ $item->guarantee_id }}">
        <span class="badge bg-secondary">{{ $item->industrial_type ?? '---' }}</span>
    </td>

    <!--tableconfig_column_5 [beneficiary]-->
    <td class="guarantee_letters_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5"
        id="guarantee_letters_col_beneficiary_{{ $item->guarantee_id }}">
        {{ str_limit($item->beneficiary ?? '---', 22) }}
    </td>

    <!--tableconfig_column_6 [amount]-->
    <td class="guarantee_letters_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6"
        id="guarantee_letters_col_amount_{{ $item->guarantee_id }}">
        {{ formatCurrency($item->amount ?? 0, $item->currency ?? 'IRR') }}
    </td>

    <!--tableconfig_column_7 [issue date]-->
    <td class="guarantee_letters_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7"
        id="guarantee_letters_col_issue_date_{{ $item->guarantee_id }}">
        {{ $item->issue_date ? runtimeDate($item->issue_date) : '---' }}
    </td>

    <!--tableconfig_column_8 [expiry date]-->
    <td class="guarantee_letters_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8"
        id="guarantee_letters_col_expiry_date_{{ $item->guarantee_id }}">
        @if($item->expiry_date)
            @php
                $expiryDate = \Carbon\Carbon::parse($item->expiry_date);
                $daysUntil = now()->diffInDays($expiryDate, false);
            @endphp
            <span class="{{ $daysUntil < 30 ? 'text-danger' : ($daysUntil < 90 ? 'text-warning' : '') }}">
                {{ runtimeDate($item->expiry_date) }}
            </span>
        @else
            ---
        @endif
    </td>

    <!--tableconfig_column_9 [status]-->
    <td class="guarantee_letters_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9"
        id="guarantee_letters_col_status_{{ $item->guarantee_id }}">
        @if($item->guarantee_status == 'active')
            <span class="badge bg-success">{{ cleanLang(__('lang.active')) }}</span>
        @elseif($item->guarantee_status == 'expired')
            <span class="badge bg-danger">{{ cleanLang(__('lang.expired')) }}</span>
        @elseif($item->guarantee_status == 'claimed')
            <span class="badge bg-warning">{{ cleanLang(__('lang.claimed')) }}</span>
        @elseif($item->guarantee_status == 'returned')
            <span class="badge bg-info">{{ cleanLang(__('lang.returned')) }}</span>
        @else
            <span class="badge bg-secondary">{{ $item->guarantee_status ?? '---' }}</span>
        @endif
    </td>

    <!--actions-->
    @if(config('visibility.guarantee_letters_col_actions'))
    <td class="guarantee_letters_col_actions actions_column" id="guarantee_letters_col_actions_{{ $item->guarantee_id }}">
        <span class="list-table-action dropdown">
            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="ti-more"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if(config('visibility.guarantee_letters_action_view'))
                <li>
                    <a class="dropdown-item" href="/guarantee-letters/{{ $item->guarantee_id }}">
                        <i class="ti-eye"></i> {{ cleanLang(__('lang.view')) }}
                    </a>
                </li>
                @endif
                @if(config('visibility.guarantee_letters_action_edit'))
                <li>
                    <a class="dropdown-item" href="/guarantee-letters/{{ $item->guarantee_id }}/edit">
                        <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                    </a>
                </li>
                @endif
                @if(config('visibility.guarantee_letters_action_delete'))
                <li>
                    <a class="dropdown-item confirm-action-danger" 
                       data-confirm-type="delete"
                       data-confirm-title="{{ cleanLang(__('lang.delete_guarantee_letter')) }}"
                       data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                       data-confirm-button-text="{{ cleanLang(__('lang.yes_delete_it')) }}"
                       data-url="/guarantee-letters/{{ $item->guarantee_id }}">
                        <i class="ti-trash"></i> {{ cleanLang(__('lang.delete')) }}
                    </a>
                </li>
                @endif
            </ul>
        </span>
    </td>
    @endif
</tr>
@endforeach

