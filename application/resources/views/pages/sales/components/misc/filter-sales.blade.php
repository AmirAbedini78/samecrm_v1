<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-sales">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_sales')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-sales"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body">

                <!--document number-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.document_number')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_document_number" id="filter_document_number"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.document_number')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--customer name-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.customer_name')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_customer_name" id="filter_customer_name"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.customer_name')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--product name-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.product_name')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_product_name" id="filter_product_name"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.product_name')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--sales status-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.sales_status')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_sales_status" id="filter_sales_status"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="pending">{{ cleanLang(__('lang.pending')) }}</option>
                                    <option value="completed">{{ cleanLang(__('lang.completed')) }}</option>
                                    <option value="cancelled">{{ cleanLang(__('lang.cancelled')) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--document type-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.document_type')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_document_type" id="filter_document_type"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="sale">{{ cleanLang(__('lang.sale')) }}</option>
                                    <option value="invoice">{{ cleanLang(__('lang.invoice')) }}</option>
                                    <option value="quote">{{ cleanLang(__('lang.quote')) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--main quantity-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.main_quantity')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm">
                                <input type="number" name="filter_main_quantity_min" id="filter_main_quantity_min"
                                    class="form-control form-control-sm" placeholder="min" step="0.01">
                            </div>
                            <div class="col-md-6 input-group input-group-sm">
                                <input type="number" name="filter_main_quantity_max" id="filter_main_quantity_max"
                                    class="form-control form-control-sm" placeholder="max" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--base price-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.base_price')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm">
                                <span class="input-group-addon">{{ config('system.settings_system_currency_symbol') }}</span>
                                <input type="number" name="filter_base_price_min" id="filter_base_price_min"
                                    class="form-control form-control-sm" placeholder="min" step="0.01">
                            </div>
                            <div class="col-md-6 input-group input-group-sm">
                                <span class="input-group-addon">{{ config('system.settings_system_currency_symbol') }}</span>
                                <input type="number" name="filter_base_price_max" id="filter_base_price_max"
                                    class="form-control form-control-sm" placeholder="max" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--base net amount-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.base_net_amount')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm">
                                <span class="input-group-addon">{{ config('system.settings_system_currency_symbol') }}</span>
                                <input type="number" name="filter_base_net_amount_min" id="filter_base_net_amount_min"
                                    class="form-control form-control-sm" placeholder="min" step="0.01">
                            </div>
                            <div class="col-md-6 input-group input-group-sm">
                                <span class="input-group-addon">{{ config('system.settings_system_currency_symbol') }}</span>
                                <input type="number" name="filter_base_net_amount_max" id="filter_base_net_amount_max"
                                    class="form-control form-control-sm" placeholder="max" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--document date-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.document_date')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="filter_document_date_start"
                                    class="form-control form-control-sm pickadate" autocomplete="off"
                                    placeholder="Start">
                                <input class="mysql-date" type="hidden" name="filter_document_date_start"
                                    id="filter_document_date_start" value="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="filter_document_date_end"
                                    class="form-control form-control-sm pickadate" autocomplete="off" placeholder="End">
                                <input class="mysql-date" type="hidden" name="filter_document_date_end"
                                    id="filter_document_date_end" value="">
                            </div>
                        </div>
                    </div>
                </div>

                <!--created by -->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.added_by')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_sales_creatorid" id="filter_sales_creatorid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach(config('system.team_members') as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--buttons-->
                <div class="buttons-block">
                    <button type="button" name="foo1"
                        class="btn btn-rounded-x btn-secondary js-reset-filter-side-panel">{{ cleanLang(__('lang.reset')) }}</button>
                    <input type="hidden" name="action" value="search">
                    <input type="hidden" name="source" value="{{ $page['source_for_filter_panels'] ?? '' }}">
                    <button type="button"
                        class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/sales/search') }}" data-type="form" data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>
            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->
