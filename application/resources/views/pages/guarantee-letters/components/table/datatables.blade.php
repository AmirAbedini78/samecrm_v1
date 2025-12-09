<div class="card count-{{ @count($guarantees ?? []) }}" id="guarantees-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper min-h-400">
            @if (@count($guarantees ?? []) > 0)
            <table id="guarantees-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10">
                <thead>
                    <tr>
                        @if(config('visibility.guarantee_letters_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-guarantees" name="listcheckbox-guarantees"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="guarantees-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-guarantees">
                                <label for="listcheckbox-guarantees"></label>
                            </span>
                        </th>
                        @endif

                        <!--tableconfig_column_1 [id]-->
                        <th class="guarantee_letters_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_guarantee_id"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=guarantee_id&sortorder=asc') }}">@lang('lang.id')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="guarantee_id" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_2 [guarantee number]-->
                        <th class="guarantee_letters_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_guarantee_number"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=guarantee_number&sortorder=asc') }}">@lang('lang.guarantee_number')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="guarantee_number" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_3 [guarantee type]-->
                        <th class="guarantee_letters_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_guarantee_type"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=guarantee_type&sortorder=asc') }}">@lang('lang.guarantee_type')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="guarantee_type" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_4 [industrial type]-->
                        <th class="guarantee_letters_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_industrial_type"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=industrial_type&sortorder=asc') }}">@lang('lang.industrial_type')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="industrial_type" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_5 [beneficiary]-->
                        <th class="guarantee_letters_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_beneficiary"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=beneficiary&sortorder=asc') }}">@lang('lang.beneficiary')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="beneficiary" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_6 [amount]-->
                        <th class="guarantee_letters_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=amount&sortorder=asc') }}">@lang('lang.amount')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_7 [issue date]-->
                        <th class="guarantee_letters_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_issue_date"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=issue_date&sortorder=asc') }}">@lang('lang.issue_date')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="issue_date" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_8 [expiry date]-->
                        <th class="guarantee_letters_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_expiry_date"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=expiry_date&sortorder=asc') }}">@lang('lang.expiry_date')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="expiry_date" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_9 [status]-->
                        <th class="guarantee_letters_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_guarantee_status"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/guarantee-letters?action=sort&orderby=guarantee_status&sortorder=asc') }}">@lang('lang.status')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="guarantee_status" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--actions-->
                        @if(config('visibility.guarantee_letters_col_actions'))
                        <th class="guarantee_letters_col_actions actions_column">
                            {{ cleanLang(__('lang.actions')) }}
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody id="guarantees-td-container">
                    @include('pages.guarantee-letters.components.table.ajax')
                </tbody>
            </table>
            @else
            <!--nothing found-->
            <div class="no-results-wrapper">
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="ti-receipt"></i>
                    </div>
                    <div class="no-results-text">
                        <h4>{{ cleanLang(__('lang.no_guarantee_letters_found')) }}</h4>
                        <p>{{ cleanLang(__('lang.no_guarantee_letters_found_text')) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

