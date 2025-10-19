<!-- right-sidebar -->
<div class="right-sidebar" id="table-config-sales">
    <form id="table-config-form">
        <div class="slimscrollright">
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.table_settings')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="table-config-sales"></i>
                </span>
            </div>

            <!--set ajax url on parent container-->
            <div class="r-panel-body table-config-ajax" data-url="{{ url('preferences/tables') }}" data-type="form"
                data-form-id="table-config-form" data-ajax-type="post" data-progress-bar="hidden">

                <!--tableconfig_column_1 [sales_id]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_1" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_1')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">شناسه فروش</span>
                    </label>
                </div>

                <!--tableconfig_column_2 [document_number]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_2" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_2')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">شماره سند</span>
                    </label>
                </div>

                <!--tableconfig_column_3 [customer_name]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_3" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_3')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">نام مشتری</span>
                    </label>
                </div>

                <!--tableconfig_column_4 [product_name]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_4" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_4')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">نام محصول</span>
                    </label>
                </div>

                <!--tableconfig_column_5 [main_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_5" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_5')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مقدار اصلی</span>
                    </label>
                </div>

                <!--tableconfig_column_6 [base_price]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_6" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_6')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">قیمت پایه</span>
                    </label>
                </div>

                <!--tableconfig_column_7 [base_net_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_7" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_7')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مبلغ خالص</span>
                    </label>
                </div>

                <!--tableconfig_column_8 [document_type]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_8" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_8')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">نوع سند</span>
                    </label>
                </div>

                <!--tableconfig_column_9 [sales_creatorid]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_9" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_9')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">ایجاد کننده</span>
                    </label>
                </div>

                <!--tableconfig_column_10 [document_date]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_10" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_10')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">تاریخ سند</span>
                    </label>
                </div>

                <!--tableconfig_column_11 [sales_status]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_11" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_11')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">وضعیت فروش</span>
                    </label>
                </div>

                <!-- Additional columns for sales -->
                <!--tableconfig_column_12 [customer_code]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_12" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_12')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">کد مشتری</span>
                    </label>
                </div>

                <!--tableconfig_column_13 [customer_full_name]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_13" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_13')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">نام کامل مشتری</span>
                    </label>
                </div>

                <!--tableconfig_column_14 [sales_type]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_14" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_14')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">نوع فروش</span>
                    </label>
                </div>

                <!--tableconfig_column_15 [product_code]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_15" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_15')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">کد محصول</span>
                    </label>
                </div>

                <!--tableconfig_column_16 [product_barcode]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_16" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_16')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">بارکد محصول</span>
                    </label>
                </div>

                <!--tableconfig_column_17 [tracking_code]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_17" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_17')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">کد ردیابی</span>
                    </label>
                </div>

                <!--tableconfig_column_18 [main_unit]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_18" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_18')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">واحد اصلی</span>
                    </label>
                </div>

                <!--tableconfig_column_19 [warehouse]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_19" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_19')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">انبار</span>
                    </label>
                </div>

                <!--tableconfig_column_20 [base_sales_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_20" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_20')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مبلغ فروش</span>
                    </label>
                </div>

                <!--tableconfig_column_21 [base_tax_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_21" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_21')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مبلغ مالیات</span>
                    </label>
                </div>

                <!--tableconfig_column_22 [base_duty_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_22" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_22')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مبلغ عوارض</span>
                    </label>
                </div>

                <!--tableconfig_column_23 [base_additional_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_23" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_23')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مبلغ اضافات</span>
                    </label>
                </div>

                <!--tableconfig_column_24 [base_increasing_factors]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_24" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_24')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">عوامل افزاینده</span>
                    </label>
                </div>

                <!--tableconfig_column_25 [month]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_25" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_25')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">ماه</span>
                    </label>
                </div>

                <!--tableconfig_column_26 [description]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_26" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_26')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">توضیحات</span>
                    </label>
                </div>

                <!--tableconfig_column_27 [issued_main_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_27" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_27')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مقدار خارج شده اصلی</span>
                    </label>
                </div>

                <!--tableconfig_column_28 [issued_sub_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_28" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_28')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مقدار خارج شده فرعی</span>
                    </label>
                </div>

                <!--tableconfig_column_29 [remaining_main_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_29" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_29')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مانده خارج نشده اصلی</span>
                    </label>
                </div>

                <!--tableconfig_column_30 [remaining_sub_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_30" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_30')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مانده خارج نشده فرعی</span>
                    </label>
                </div>

                <!--tableconfig_column_31 [currency]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_31" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_31')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">ارز</span>
                    </label>
                </div>

                <!--tableconfig_column_32 [updated_at]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_32" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_32')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">تاریخ بروزرسانی</span>
                    </label>
                </div>

            </div>

            <!--table name-->
            <input type="hidden" name="tableconfig_table_name" value="sales">

            <!--buttons-->
            <div class="buttons-block">
                <button type="button" name="foo1" class="btn btn-rounded-x btn-secondary js-close-side-panels"
                    data-target="table-config-sales">{{ cleanLang(__('lang.close')) }}</button>
                <input type="hidden" name="action" value="search">
            </div>
        </div>
        <!--body-->
</div>
</form>
</div>
<!--sidebar-->
