<!-- right-sidebar -->
<div class="right-sidebar" id="table-config-inventory">
    <form id="table-config-form">
        <div class="slimscrollright">
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.table_settings')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="table-config-inventory"></i>
                </span>
            </div>

            <!--set ajax url on parent container-->
            <div class="r-panel-body table-config-ajax" data-url="{{ url('preferences/tables') }}" data-type="form"
                data-form-id="table-config-form" data-ajax-type="post" data-progress-bar="hidden">

                <!--tableconfig_column_1 [inventory_id]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_1" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_1')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">شناسه کالا</span>
                    </label>
                </div>

                <!--tableconfig_column_2 [inventory_name]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_2" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_2')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">نام کالا</span>
                    </label>
                </div>

                <!--tableconfig_column_3 [inventory_code]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_3" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_3')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">کد کالا</span>
                    </label>
                </div>

                <!--tableconfig_column_4 [current_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_4" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_4')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">موجودی فعلی</span>
                    </label>
                </div>

                <!--tableconfig_column_5 [current_avg_price]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_5" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_5')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">قیمت متوسط</span>
                    </label>
                </div>

                <!--tableconfig_column_6 [current_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_6" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_6')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مبلغ کل</span>
                    </label>
                </div>

                <!--tableconfig_column_7 [minimum_stock]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_7" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_7')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">حداقل موجودی</span>
                    </label>
                </div>

                <!--tableconfig_column_8 [inventory_categoryid]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_8" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_8')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">دسته‌بندی</span>
                    </label>
                </div>

                <!--tableconfig_column_9 [inventory_creatorid]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_9" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_9')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">ایجاد کننده</span>
                    </label>
                </div>

                <!--tableconfig_column_10 [created_at]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_10" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_10')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">تاریخ ایجاد</span>
                    </label>
                </div>

                <!--tableconfig_column_11 [inventory_status]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_11" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_11')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">وضعیت</span>
                    </label>
                </div>

                <!-- Additional columns for inventory -->
                <!--tableconfig_column_12 [first_period_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_12" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_12')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">اول دوره - مقدار</span>
                    </label>
                </div>

                <!--tableconfig_column_13 [first_period_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_13" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_13')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">اول دوره - مبلغ</span>
                    </label>
                </div>

                <!--tableconfig_column_14 [input_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_14" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_14')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">ورودی - مقدار</span>
                    </label>
                </div>

                <!--tableconfig_column_15 [input_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_15" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_15')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">ورودی - مبلغ</span>
                    </label>
                </div>

                <!--tableconfig_column_16 [output_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_16" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_16')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">خروجی - مقدار</span>
                    </label>
                </div>

                <!--tableconfig_column_17 [output_amount]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_17" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_17')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">خروجی - مبلغ</span>
                    </label>
                </div>

                <!--tableconfig_column_18 [current_sub_quantity]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_18" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_18')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">موجودی فرعی</span>
                    </label>
                </div>

                <!--tableconfig_column_19 [weighing_input]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_19" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_19')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">توزین ورود</span>
                    </label>
                </div>

                <!--tableconfig_column_20 [weighing_output]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_20" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_20')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">توزین خروج</span>
                    </label>
                </div>

                <!--tableconfig_column_21 [maximum_stock]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_21" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_21')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">حداکثر موجودی</span>
                    </label>
                </div>

                <!--tableconfig_column_22 [discrepancy]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_22" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_22')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">مغایرت</span>
                    </label>
                </div>

                <!--tableconfig_column_23 [main_unit]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_23" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_23')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">واحد اصلی</span>
                    </label>
                </div>

                <!--tableconfig_column_24 [sub_unit]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_24" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_24')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">واحد فرعی</span>
                    </label>
                </div>

                <!--tableconfig_column_25 [updated_at]-->
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_25" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_25')) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">تاریخ بروزرسانی</span>
                    </label>
                </div>

            </div>

            <!--table name-->
            <input type="hidden" name="tableconfig_table_name" value="inventory">

            <!--buttons-->
            <div class="buttons-block">
                <button type="button" name="foo1" class="btn btn-rounded-x btn-secondary js-close-side-panels"
                    data-target="table-config-inventory">{{ cleanLang(__('lang.close')) }}</button>
                <input type="hidden" name="action" value="search">
            </div>
        </div>
        <!--body-->
</div>
</form>
</div>
<!--sidebar-->
