<!--table config panel-->
@if(config('visibility.guarantee_letters_table_config'))
<div class="right-sidebar" id="sidepanel-table-config-guarantee-letters">
    <div class="slimscrollright">
        <!--title-->
        <div class="rpanel-title">
            <i class="ti-settings"></i>{{ cleanLang(__('lang.table_configuration')) }}
            <span>
                <i class="ti-close js-close-side-panels" data-target="sidepanel-table-config-guarantee-letters"></i>
            </span>
        </div>
        <!--title-->
        <!--body-->
        <div class="r-panel-body">
            <p class="text-muted">{{ cleanLang(__('lang.select_columns_to_display')) }}</p>
            <!--columns-->
            <div class="table-config-columns">
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_1" data-column="tableconfig_column_1" checked>
                    <label class="form-check-label" for="tableconfig_column_1">
                        {{ cleanLang(__('lang.id')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_2" data-column="tableconfig_column_2" checked>
                    <label class="form-check-label" for="tableconfig_column_2">
                        {{ cleanLang(__('lang.guarantee_number')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_3" data-column="tableconfig_column_3" checked>
                    <label class="form-check-label" for="tableconfig_column_3">
                        {{ cleanLang(__('lang.guarantee_type')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_4" data-column="tableconfig_column_4" checked>
                    <label class="form-check-label" for="tableconfig_column_4">
                        {{ cleanLang(__('lang.industrial_type')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_5" data-column="tableconfig_column_5" checked>
                    <label class="form-check-label" for="tableconfig_column_5">
                        {{ cleanLang(__('lang.beneficiary')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_6" data-column="tableconfig_column_6" checked>
                    <label class="form-check-label" for="tableconfig_column_6">
                        {{ cleanLang(__('lang.amount')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_7" data-column="tableconfig_column_7" checked>
                    <label class="form-check-label" for="tableconfig_column_7">
                        {{ cleanLang(__('lang.issue_date')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_8" data-column="tableconfig_column_8" checked>
                    <label class="form-check-label" for="tableconfig_column_8">
                        {{ cleanLang(__('lang.expiry_date')) }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input table-config-checkbox" type="checkbox" 
                           id="tableconfig_column_9" data-column="tableconfig_column_9" checked>
                    <label class="form-check-label" for="tableconfig_column_9">
                        {{ cleanLang(__('lang.status')) }}
                    </label>
                </div>
            </div>
            <!--columns-->
            <!--save button-->
            <div class="mt-3">
                <button type="button" class="btn btn-primary btn-sm btn-block" id="save-table-config">
                    <i class="ti-save"></i> {{ cleanLang(__('lang.save_configuration')) }}
                </button>
            </div>
        </div>
        <!--body-->
    </div>
</div>
@endif
<!--table config panel-->
