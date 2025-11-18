<div class="card count-{{ @count($settlements ?? []) }}" id="invoice-settlements-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper min-h-400">
            @if(@count($settlements ?? []) > 0)
            <table class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        <!-- document number -->
                        <th class="invoice_settlements_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_number"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=document_number&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.document_number')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="document_number">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- document date -->
                        <th class="invoice_settlements_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_date"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=document_date&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.document_date')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="document_date">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- customer -->
                        <th class="invoice_settlements_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_customer_name"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=customer_name&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.customer_name')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="customer_name">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- base net -->
                        <th class="invoice_settlements_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_net_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=base_net_amount&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.base_net_amount')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- paid -->
                        <th class="invoice_settlements_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_paid_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=paid_amount&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.paid_amount')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- balance -->
                        <th class="invoice_settlements_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_balance_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=balance_amount&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.balance_amount')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- currency -->
                        <th class="invoice_settlements_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_currency"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=currency&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.currency')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- creator -->
                        <th class="invoice_settlements_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_creator"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=creator_id&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.created_by')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="creator">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- created at -->
                        <th class="invoice_settlements_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_created_at"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=created_at&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.created_at')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <th class="invoice_settlements_col_action with-table-config-icon actions_column">
                            <span>{{ cleanLang(__('lang.columns')) }}</span>
                            <div class="table-config-icon">
                                <span class="text-default js-toggle-table-config-panel"
                                    data-target="table-config-invoice-settlements">
                                    <i class="sl-icon-settings"></i>
                                </span>
                            </div>
                        </th>
                    </tr>

                    <tr class="column-search-row" style="background-color:#f8f9fa;">
                        <th class="invoice_settlements_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="document_number"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="document_date"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="customer_name"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4"></th>
                        <th class="invoice_settlements_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5"></th>
                        <th class="invoice_settlements_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6"></th>
                        <th class="invoice_settlements_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="currency"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="creator"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9"></th>
                        <th class="invoice_settlements_col_action actions_column"></th>
                    </tr>
                </thead>
                <tbody id="invoice-settlements-td-container">
                    @include('pages.invoice-settlements.components.table.ajax')
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('misc.load-more-button')
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif

            @if(@count($settlements ?? []) == 0)
                @include('notifications.no-results-found')
            @endif
        </div>
    </div>
</div>

@if($settlements && $settlements->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                {{ cleanLang(__('lang.showing')) }} {{ $settlements->firstItem() }}
                {{ cleanLang(__('lang.to')) }} {{ $settlements->lastItem() }}
                {{ cleanLang(__('lang.of')) }} {{ $settlements->total() }} {{ cleanLang(__('lang.results')) }}
            </div>
            <div>
                {{ $settlements->links() }}
            </div>
        </div>
    </div>
</div>
@endif

