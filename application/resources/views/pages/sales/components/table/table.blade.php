<div class="card count-{{ @count($sales ?? []) }}" id="sales-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper min-h-400">
            @if (@count($sales ?? []) > 0)
            <table id="sales-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list"
                data-page-size="10">
        <thead>
            <tr>
                        @if(config('visibility.sales_col_checkboxes'))
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-sales" name="listcheckbox-sales"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="sales-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-sales">
                                <label for="listcheckbox-sales"></label>
                            </span>
                        </th>
                        @endif

                        <!--tableconfig_column_1 [id]-->
                        <th
                            class="sales_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_sales_id"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=sales_id&sortorder=asc') }}">@lang('lang.id')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="sales_id" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_2 [document number]-->
                        <th
                            class="sales_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_number"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=document_number&sortorder=asc') }}">@lang('lang.document_number')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="document_number" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_3 [customer name]-->
                        <th
                            class="sales_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_customer_name"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=customer_name&sortorder=asc') }}">@lang('lang.customer_name')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="customer_name" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_4 [product name]-->
                        <th
                            class="sales_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_product_name"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=product_name&sortorder=asc') }}">@lang('lang.product_name')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="product_name" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_5 [main quantity]-->
                        <th
                            class="sales_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_main_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=main_quantity&sortorder=asc') }}">@lang('lang.main_quantity')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="main_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_6 [base price]-->
                        <th
                            class="sales_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_price"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_price&sortorder=asc') }}">@lang('lang.base_price')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_price" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_7 [base net amount]-->
                        <th
                            class="sales_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_net_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_net_amount&sortorder=asc') }}">@lang('lang.base_net_amount')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_net_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_8 [document type]-->
                        <th
                            class="sales_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_type"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=document_type&sortorder=asc') }}">@lang('lang.document_type')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="document_type" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_9 [created by]-->
                        <th
                            class="sales_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_creator"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=sales_creatorid&sortorder=asc') }}">@lang('lang.created_by')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="creator" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_10 [document date]-->
                        <th
                            class="sales_col_tableconfig_column_10 {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_date"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=document_date&sortorder=asc') }}">@lang('lang.document_date')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="document_date" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_11 [sales status]-->
                        <th
                            class="sales_col_tableconfig_column_11 {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_sales_status"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=sales_status&sortorder=asc') }}">@lang('lang.sales_status')<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="sales_status" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- Additional columns for sales -->
                        <!--tableconfig_column_12 [customer_code]-->
                        <th
                            class="sales_col_tableconfig_column_12 {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_customer_code"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=customer_code&sortorder=asc') }}">کد مشتری<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="customer_code" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_13 [customer_full_name]-->
                        <th
                            class="sales_col_tableconfig_column_13 {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_customer_full_name"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=customer_full_name&sortorder=asc') }}">نام کامل مشتری<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="customer_full_name" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_14 [sales_type]-->
                        <th
                            class="sales_col_tableconfig_column_14 {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_sales_type"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=sales_type&sortorder=asc') }}">نوع فروش<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="sales_type" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_15 [product_code]-->
                        <th
                            class="sales_col_tableconfig_column_15 {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_product_code"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=product_code&sortorder=asc') }}">کد محصول<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="product_code" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_16 [product_barcode]-->
                        <th
                            class="sales_col_tableconfig_column_16 {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_product_barcode"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=product_barcode&sortorder=asc') }}">بارکد محصول<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="product_barcode" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_17 [tracking_code]-->
                        <th
                            class="sales_col_tableconfig_column_17 {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_tracking_code"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=tracking_code&sortorder=asc') }}">کد ردیابی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="tracking_code" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_18 [main_unit]-->
                        <th
                            class="sales_col_tableconfig_column_18 {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_main_unit"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=main_unit&sortorder=asc') }}">واحد اصلی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="main_unit" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_19 [warehouse]-->
                        <th
                            class="sales_col_tableconfig_column_19 {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_warehouse"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=warehouse&sortorder=asc') }}">انبار<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="warehouse" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_20 [base_sales_amount]-->
                        <th
                            class="sales_col_tableconfig_column_20 {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_sales_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_sales_amount&sortorder=asc') }}">مبلغ فروش<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_sales_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_21 [base_tax_amount]-->
                        <th
                            class="sales_col_tableconfig_column_21 {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_tax_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_tax_amount&sortorder=asc') }}">مبلغ مالیات<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_tax_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_22 [base_duty_amount]-->
                        <th
                            class="sales_col_tableconfig_column_22 {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_duty_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_duty_amount&sortorder=asc') }}">مبلغ عوارض<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_duty_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_23 [base_additional_amount]-->
                        <th
                            class="sales_col_tableconfig_column_23 {{ config('table.tableconfig_column_23') }} tableconfig_column_23">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_additional_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_additional_amount&sortorder=asc') }}">مبلغ اضافات<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_additional_amount" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_24 [base_increasing_factors]-->
                        <th
                            class="sales_col_tableconfig_column_24 {{ config('table.tableconfig_column_24') }} tableconfig_column_24">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_increasing_factors"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=base_increasing_factors&sortorder=asc') }}">عوامل افزاینده<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="base_increasing_factors" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_25 [month]-->
                        <th
                            class="sales_col_tableconfig_column_25 {{ config('table.tableconfig_column_25') }} tableconfig_column_25">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_month"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=month&sortorder=asc') }}">ماه<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="month" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_26 [description]-->
                        <th
                            class="sales_col_tableconfig_column_26 {{ config('table.tableconfig_column_26') }} tableconfig_column_26">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_description"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=description&sortorder=asc') }}">توضیحات<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="description" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_27 [issued_main_quantity]-->
                        <th
                            class="sales_col_tableconfig_column_27 {{ config('table.tableconfig_column_27') }} tableconfig_column_27">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_issued_main_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=issued_main_quantity&sortorder=asc') }}">مقدار خارج شده اصلی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="issued_main_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_28 [issued_sub_quantity]-->
                        <th
                            class="sales_col_tableconfig_column_28 {{ config('table.tableconfig_column_28') }} tableconfig_column_28">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_issued_sub_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=issued_sub_quantity&sortorder=asc') }}">مقدار خارج شده فرعی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="issued_sub_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_29 [remaining_main_quantity]-->
                        <th
                            class="sales_col_tableconfig_column_29 {{ config('table.tableconfig_column_29') }} tableconfig_column_29">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_remaining_main_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=remaining_main_quantity&sortorder=asc') }}">مانده خارج نشده اصلی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="remaining_main_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_30 [remaining_sub_quantity]-->
                        <th
                            class="sales_col_tableconfig_column_30 {{ config('table.tableconfig_column_30') }} tableconfig_column_30">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_remaining_sub_quantity"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=remaining_sub_quantity&sortorder=asc') }}">مانده خارج نشده فرعی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="remaining_sub_quantity" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_31 [currency]-->
                        <th
                            class="sales_col_tableconfig_column_31 {{ config('table.tableconfig_column_31') }} tableconfig_column_31">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_currency"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=currency&sortorder=asc') }}">ارز<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="currency" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--tableconfig_column_32 [updated_at]-->
                        <th
                            class="sales_col_tableconfig_column_32 {{ config('table.tableconfig_column_32') }} tableconfig_column_32">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_updated_at"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/sales?action=sort&orderby=updated_at&sortorder=asc') }}">تاریخ بروزرسانی<span
                                        class="sorting-icons"><i class="ti-arrows-vertical"></i></span></a>
                                <span class="column-filter-dropdown" data-column="updated_at" title="فیلتر بر اساس مقادیر">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!--actions-->
                        <th class="sales_col_action with-table-config-icon actions_column"><a
                                href="javascript:void(0)">@lang('lang.action')</a>

                            <!--[tableconfig]-->
                            <div class="table-config-icon">
                                <span class="text-default js-toggle-table-config-panel"
                                    data-target="table-config-sales">
                                    <i class="sl-icon-settings">
                                    </i>
                                </span>
                            </div>
                </th>
            </tr>
            
            <!-- Column Search Inputs - ALL COLUMNS -->
            <tr class="column-search-row" style="background-color: #f8f9fa;">
                @if(config('visibility.sales_col_checkboxes'))
                <th class="list-checkbox-wrapper">
                    <!-- Empty for checkbox column -->
                </th>
                @endif
                
                <!--tableconfig_column_1 [id] search-->
                <th class="sales_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در ID..." 
                           data-column="sales_id"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_2 [document number] search-->
                <th class="sales_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در شماره سند..." 
                           data-column="document_number"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_3 [customer name] search-->
                <th class="sales_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در نام مشتری..." 
                           data-column="customer_name"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_4 [product name] search-->
                <th class="sales_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در نام محصول..." 
                           data-column="product_name"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_5 [main quantity] search-->
                <th class="sales_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مقدار..." 
                           data-column="main_quantity"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_6 [base price] search-->
                <th class="sales_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در قیمت..." 
                           data-column="base_price"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_7 [base net amount] search-->
                <th class="sales_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ خالص..." 
                           data-column="base_net_amount"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_8 [document type] search-->
                <th class="sales_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در نوع سند..." 
                           data-column="document_type"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_9 [created by] search-->
                <th class="sales_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در سازنده..." 
                           data-column="creator"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_10 [document date] search-->
                <th class="sales_col_tableconfig_column_10 {{ config('table.tableconfig_column_10') }} tableconfig_column_10">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در تاریخ سند..." 
                           data-column="document_date"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_11 [sales status] search-->
                <th class="sales_col_tableconfig_column_11 {{ config('table.tableconfig_column_11') }} tableconfig_column_11">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در وضعیت..." 
                           data-column="sales_status"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!-- Additional columns search inputs -->
                <!--tableconfig_column_12 [customer_code] search-->
                <th class="sales_col_tableconfig_column_12 {{ config('table.tableconfig_column_12') }} tableconfig_column_12">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در کد مشتری..." 
                           data-column="customer_code"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_13 [customer_full_name] search-->
                <th class="sales_col_tableconfig_column_13 {{ config('table.tableconfig_column_13') }} tableconfig_column_13">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در نام کامل..." 
                           data-column="customer_full_name"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_14 [sales_type] search-->
                <th class="sales_col_tableconfig_column_14 {{ config('table.tableconfig_column_14') }} tableconfig_column_14">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در نوع فروش..." 
                           data-column="sales_type"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_15 [product_code] search-->
                <th class="sales_col_tableconfig_column_15 {{ config('table.tableconfig_column_15') }} tableconfig_column_15">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در کد محصول..." 
                           data-column="product_code"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_16 [product_barcode] search-->
                <th class="sales_col_tableconfig_column_16 {{ config('table.tableconfig_column_16') }} tableconfig_column_16">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در بارکد..." 
                           data-column="product_barcode"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_17 [tracking_code] search-->
                <th class="sales_col_tableconfig_column_17 {{ config('table.tableconfig_column_17') }} tableconfig_column_17">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در کد پیگیری..." 
                           data-column="tracking_code"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_18 [main_unit] search-->
                <th class="sales_col_tableconfig_column_18 {{ config('table.tableconfig_column_18') }} tableconfig_column_18">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در واحد اصلی..." 
                           data-column="main_unit"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_19 [warehouse] search-->
                <th class="sales_col_tableconfig_column_19 {{ config('table.tableconfig_column_19') }} tableconfig_column_19">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در انبار..." 
                           data-column="warehouse"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_20 [base_sales_amount] search-->
                <th class="sales_col_tableconfig_column_20 {{ config('table.tableconfig_column_20') }} tableconfig_column_20">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ فروش..." 
                           data-column="base_sales_amount"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_21 [base_tax_amount] search-->
                <th class="sales_col_tableconfig_column_21 {{ config('table.tableconfig_column_21') }} tableconfig_column_21">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مالیات..." 
                           data-column="base_tax_amount"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_22 [base_duty_amount] search-->
                <th class="sales_col_tableconfig_column_22 {{ config('table.tableconfig_column_22') }} tableconfig_column_22">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در عوارض..." 
                           data-column="base_duty_amount"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_23 [base_additional_amount] search-->
                <th class="sales_col_tableconfig_column_23 {{ config('table.tableconfig_column_23') }} tableconfig_column_23">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مبلغ اضافی..." 
                           data-column="base_additional_amount"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_24 [base_increasing_factors] search-->
                <th class="sales_col_tableconfig_column_24 {{ config('table.tableconfig_column_24') }} tableconfig_column_24">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در عوامل افزایش..." 
                           data-column="base_increasing_factors"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_25 [month] search-->
                <th class="sales_col_tableconfig_column_25 {{ config('table.tableconfig_column_25') }} tableconfig_column_25">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در ماه..." 
                           data-column="month"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_26 [description] search-->
                <th class="sales_col_tableconfig_column_26 {{ config('table.tableconfig_column_26') }} tableconfig_column_26">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در توضیحات..." 
                           data-column="description"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_27 [issued_main_quantity] search-->
                <th class="sales_col_tableconfig_column_27 {{ config('table.tableconfig_column_27') }} tableconfig_column_27">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مقدار صادر شده..." 
                           data-column="issued_main_quantity"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_28 [issued_sub_quantity] search-->
                <th class="sales_col_tableconfig_column_28 {{ config('table.tableconfig_column_28') }} tableconfig_column_28">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در زیرواحد صادر شده..." 
                           data-column="issued_sub_quantity"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_29 [remaining_main_quantity] search-->
                <th class="sales_col_tableconfig_column_29 {{ config('table.tableconfig_column_29') }} tableconfig_column_29">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در مقدار باقی مانده..." 
                           data-column="remaining_main_quantity"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_30 [remaining_sub_quantity] search-->
                <th class="sales_col_tableconfig_column_30 {{ config('table.tableconfig_column_30') }} tableconfig_column_30">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در زیرواحد باقی مانده..." 
                           data-column="remaining_sub_quantity"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_31 [currency] search-->
                <th class="sales_col_tableconfig_column_31 {{ config('table.tableconfig_column_31') }} tableconfig_column_31">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در ارز..." 
                           data-column="currency"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--tableconfig_column_32 [updated_at] search-->
                <th class="sales_col_tableconfig_column_32 {{ config('table.tableconfig_column_32') }} tableconfig_column_32">
                    <input type="text" class="form-control form-control-sm column-search-input" 
                           placeholder="جستجو در بروزرسانی..." 
                           data-column="updated_at"
                           data-url="{{ urlResource('/sales') }}">
                </th>
                
                <!--actions-->
                <th class="sales_col_action actions_column">
                    <!-- Empty for actions column -->
                </th>
            </tr>
        </thead>
        <tbody id="sales-td-container">
                    <!--ajax content here-->
                    @include('pages.sales.components.table.ajax')
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
            @endif @if (@count($sales ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
</div>
</div>

<!-- Pagination -->
@if($sales && $sales->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                {{ cleanLang(__('lang.showing')) }} {{ $sales->firstItem() }} {{ cleanLang(__('lang.to')) }} {{ $sales->lastItem() }} 
                {{ cleanLang(__('lang.of')) }} {{ $sales->total() }} {{ cleanLang(__('lang.results')) }}
            </div>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endif
