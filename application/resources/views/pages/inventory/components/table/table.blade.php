<div class="card count-{{ @count($inventory ?? []) }}" id="inventory-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper min-h-400">
            @if (@count($inventory ?? []) > 0)
            <table id="inventory-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10">
        <thead>
            <!-- Column Headers -->
            <tr>
                        @if(config('visibility.inventory_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-inventory" name="listcheckbox-inventory"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="inventory-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-inventory">
                                <label for="listcheckbox-inventory"></label>
                            </span>
                        </th>
                        @endif

                        <!--tableconfig_column_1 [id]-->
                        <th
                            class="inventory_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_inventory_id"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=inventory_id&sortorder=asc') }}">@lang('lang.id')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="inventory_id" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_2 [inventory name]-->
                        <th
                            class="inventory_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_inventory_name"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=inventory_name&sortorder=asc') }}">@lang('lang.inventory_name')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="inventory_name" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_3 [inventory code]-->
                        <th
                            class="inventory_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_inventory_code"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=inventory_code&sortorder=asc') }}">@lang('lang.inventory_code')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="inventory_code" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_4 [current quantity]-->
                        <th
                            class="inventory_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_current_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=current_quantity&sortorder=asc') }}">@lang('lang.current_quantity')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="current_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_5 [current avg price]-->
                        <th
                            class="inventory_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_current_avg_price"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=current_avg_price&sortorder=asc') }}">@lang('lang.current_avg_price')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="current_avg_price" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_6 [current amount]-->
                        <th
                            class="inventory_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_current_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=current_amount&sortorder=asc') }}">@lang('lang.current_amount')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="current_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_7 [minimum stock]-->
                        <th
                            class="inventory_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_minimum_stock"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=minimum_stock&sortorder=asc') }}">@lang('lang.minimum_stock')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="minimum_stock" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_8 [category]-->
                        <th
                            class="inventory_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_category"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=inventory_categoryid&sortorder=asc') }}">@lang('lang.category')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="category" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_9 [created by]-->
                        <th
                            class="inventory_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_creator"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=inventory_creatorid&sortorder=asc') }}">@lang('lang.created_by')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="creator" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_10 [date created]-->
                        <th
                            class="inventory_col_tableconfig_column_10 {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_created_at"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=created_at&sortorder=asc') }}">@lang('lang.date_created')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="created_at" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_11 [status]-->
                        <th
                            class="inventory_col_tableconfig_column_11 {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_status"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=inventory_status&sortorder=asc') }}">@lang('lang.status')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="inventory_status" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- Additional columns for inventory -->
                        <!--tableconfig_column_12 [first_period_quantity]-->
                        <th
                            class="inventory_col_tableconfig_column_12 {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_first_period_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=first_period_quantity&sortorder=asc') }}">اول دوره - مقدار<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="first_period_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_13 [first_period_amount]-->
                        <th
                            class="inventory_col_tableconfig_column_13 {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_first_period_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=first_period_amount&sortorder=asc') }}">اول دوره - مبلغ<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="first_period_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_14 [input_quantity]-->
                        <th
                            class="inventory_col_tableconfig_column_14 {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_input_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=input_quantity&sortorder=asc') }}">ورودی - مقدار<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="input_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_15 [input_amount]-->
                        <th
                            class="inventory_col_tableconfig_column_15 {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_input_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=input_amount&sortorder=asc') }}">ورودی - مبلغ<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="input_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_16 [output_quantity]-->
                        <th
                            class="inventory_col_tableconfig_column_16 {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_output_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=output_quantity&sortorder=asc') }}">خروجی - مقدار<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="output_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_17 [output_amount]-->
                        <th
                            class="inventory_col_tableconfig_column_17 {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_output_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=output_amount&sortorder=asc') }}">خروجی - مبلغ<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="output_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_18 [current_sub_quantity]-->
                        <th
                            class="inventory_col_tableconfig_column_18 {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                            <a class="js-ajax-ux-request" id="sort_current_sub_quantity"
                                href="javascript:void(0)"
                                data-url="{{ urlResource('/inventory?action=sort&orderby=current_sub_quantity&sortorder=asc') }}">موجودی فرعی<span
                                    class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                        </th>

                        <!--tableconfig_column_19 [weighing_input]-->
                        <th
                            class="inventory_col_tableconfig_column_19 {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_weighing_input"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=weighing_input&sortorder=asc') }}">توزین ورود<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="weighing_input" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_20 [weighing_output]-->
                        <th
                            class="inventory_col_tableconfig_column_20 {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_weighing_output"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=weighing_output&sortorder=asc') }}">توزین خروج<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="weighing_output" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_21 [maximum_stock]-->
                        <th
                            class="inventory_col_tableconfig_column_21 {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_maximum_stock"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=maximum_stock&sortorder=asc') }}">حداکثر موجودی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="maximum_stock" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_22 [discrepancy]-->
                        <th
                            class="inventory_col_tableconfig_column_22 {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_discrepancy"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=discrepancy&sortorder=asc') }}">مغایرت<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="discrepancy" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_23 [main_unit]-->
                        <th
                            class="inventory_col_tableconfig_column_23 {{ config('table.tableconfig_column_23') }} tableconfig_column_23">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_main_unit"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=main_unit&sortorder=asc') }}">واحد اصلی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="main_unit" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_24 [sub_unit]-->
                        <th
                            class="inventory_col_tableconfig_column_24 {{ config('table.tableconfig_column_24') }} tableconfig_column_24">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_sub_unit"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=sub_unit&sortorder=asc') }}">واحد فرعی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="sub_unit" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_25 [updated_at]-->
                        <th
                            class="inventory_col_tableconfig_column_25 {{ config('table.tableconfig_column_25') }} tableconfig_column_25">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_updated_at"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/inventory?action=sort&orderby=updated_at&sortorder=asc') }}">تاریخ بروزرسانی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="updated_at" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--actions-->
                        <th class="inventory_col_action with-table-config-icon actions_column"><a
                                href="javascript:void(0)">@lang('lang.action')</a>

                            <!--[tableconfig]-->
                            <div class="table-config-icon">
                                <span class="text-default js-toggle-table-config-panel"
                                    data-target="table-config-inventory">
                                    <i class="sl-icon-settings">
                                    </i>
                                </span>
                            </div>
                </th>
            </tr>
            
            <!-- Column Search Inputs - ALL COLUMNS -->
            <tr class="column-search-row" style="background-color: #f8f9fa;">
                @if(config('visibility.inventory_col_checkboxes'))
                <th class="list-checkbox-wrapper">
                    <!-- Empty for checkbox column -->
                </th>
                @endif
                
                <!--tableconfig_column_1 [id] search-->
                <th class="inventory_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در ID..." 
                           data-column="inventory_id"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_2 [inventory name] search-->
                <th class="inventory_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در نام..." 
                           data-column="inventory_name"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_3 [inventory code] search-->
                <th class="inventory_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در کد..." 
                           data-column="inventory_code"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_4 [current quantity] search-->
                <th class="inventory_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مقدار..." 
                           data-column="current_quantity"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_5 [current amount] search-->
                <th class="inventory_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ..." 
                           data-column="current_amount"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_6 [minimum stock] search-->
                <th class="inventory_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در حداقل..." 
                           data-column="minimum_stock"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_7 [category] search-->
                <th class="inventory_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در دسته..." 
                           data-column="category"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_8 [creator] search-->
                <th class="inventory_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در سازنده..." 
                           data-column="creator"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_9 [tags] search-->
                <th class="inventory_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در برچسب..." 
                           data-column="tags"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_10 [created] search-->
                <th class="inventory_col_tableconfig_column_10 {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در تاریخ..." 
                           data-column="created_at"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_11 [status] search-->
                <th class="inventory_col_tableconfig_column_11 {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در وضعیت..." 
                           data-column="inventory_status"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!-- Additional columns search inputs -->
                <!--tableconfig_column_12 [first_period_quantity] search-->
                <th class="inventory_col_tableconfig_column_12 {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در اول دوره..." 
                           data-column="first_period_quantity"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_13 [first_period_amount] search-->
                <th class="inventory_col_tableconfig_column_13 {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ اول..." 
                           data-column="first_period_amount"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_14 [input_quantity] search-->
                <th class="inventory_col_tableconfig_column_14 {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در ورودی..." 
                           data-column="input_quantity"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_15 [input_amount] search-->
                <th class="inventory_col_tableconfig_column_15 {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ ورودی..." 
                           data-column="input_amount"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_16 [output_quantity] search-->
                <th class="inventory_col_tableconfig_column_16 {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در خروجی..." 
                           data-column="output_quantity"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_17 [output_amount] search-->
                <th class="inventory_col_tableconfig_column_17 {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ خروجی..." 
                           data-column="output_amount"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_18 [current_sub_quantity] search-->
                <th class="inventory_col_tableconfig_column_18 {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در زیرواحد..." 
                           data-column="current_sub_quantity"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_19 [weighing_input] search-->
                <th class="inventory_col_tableconfig_column_19 {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در وزن ورودی..." 
                           data-column="weighing_input"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_20 [weighing_output] search-->
                <th class="inventory_col_tableconfig_column_20 {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در وزن خروجی..." 
                           data-column="weighing_output"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_21 [maximum_stock] search-->
                <th class="inventory_col_tableconfig_column_21 {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در حداکثر..." 
                           data-column="maximum_stock"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_22 [discrepancy] search-->
                <th class="inventory_col_tableconfig_column_22 {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در اختلاف..." 
                           data-column="discrepancy"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_23 [main_unit] search-->
                <th class="inventory_col_tableconfig_column_23 {{ config('table.tableconfig_column_23') }} tableconfig_column_23">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در واحد اصلی..." 
                           data-column="main_unit"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_24 [sub_unit] search-->
                <th class="inventory_col_tableconfig_column_24 {{ config('table.tableconfig_column_24') }} tableconfig_column_24">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در زیرواحد..." 
                           data-column="sub_unit"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--tableconfig_column_25 [updated_at] search-->
                <th class="inventory_col_tableconfig_column_25 {{ config('table.tableconfig_column_25') }} tableconfig_column_25">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در بروزرسانی..." 
                           data-column="updated_at"
                           data-url="{{ urlResource('/inventory') }}">
                </th>
                
                <!--actions-->
                <th class="inventory_col_action actions_column">
                    <!-- Empty for actions column -->
                </th>
            </tr>
        </thead>
        <tbody id="inventory-td-container">
                    <!--ajax content here-->
                    @include('pages.inventory.components.table.ajax')
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                            @include('misc.load-more-button')
                            <!--load more button-->
                    </td>
                </tr>
                </tfoot>
            </table>
            @endif @if (@count($inventory ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
</div>
</div>

<!-- Pagination -->
@if($inventory && $inventory->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                {{ cleanLang(__('lang.showing')) }} {{ $inventory->firstItem() }} {{ cleanLang(__('lang.to')) }} {{ $inventory->lastItem() }} 
                {{ cleanLang(__('lang.of')) }} {{ $inventory->total() }} {{ cleanLang(__('lang.results')) }}
            </div>
            <div>
                {{ $inventory->links() }}
            </div>
        </div>
    </div>
</div>
@endif
