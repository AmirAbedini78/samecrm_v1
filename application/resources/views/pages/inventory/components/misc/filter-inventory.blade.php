<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-inventory">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_inventory')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-inventory"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body">

                <!--inventory name-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.inventory_name')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_inventory_name" id="filter_inventory_name"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.inventory_name')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--inventory code-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.inventory_code')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_inventory_code" id="filter_inventory_code"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.inventory_code')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--category-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.category')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_inventory_categoryid" id="filter_inventory_categoryid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">
                                        {{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--current quantity-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.current_quantity')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm">
                                <input type="number" name="filter_current_quantity_min" id="filter_current_quantity_min"
                                    class="form-control form-control-sm" placeholder="min" step="0.01">
                            </div>
                            <div class="col-md-6 input-group input-group-sm">
                                <input type="number" name="filter_current_quantity_max" id="filter_current_quantity_max"
                                    class="form-control form-control-sm" placeholder="max" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--current amount-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.current_amount')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm">
                                <span class="input-group-addon">{{ config('system.settings_system_currency_symbol') }}</span>
                                <input type="number" name="filter_current_amount_min" id="filter_current_amount_min"
                                    class="form-control form-control-sm" placeholder="min" step="0.01">
                            </div>
                            <div class="col-md-6 input-group input-group-sm">
                                <span class="input-group-addon">{{ config('system.settings_system_currency_symbol') }}</span>
                                <input type="number" name="filter_current_amount_max" id="filter_current_amount_max"
                                    class="form-control form-control-sm" placeholder="max" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--minimum stock-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.minimum_stock')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6 input-group input-group-sm">
                                <input type="number" name="filter_minimum_stock_min" id="filter_minimum_stock_min"
                                    class="form-control form-control-sm" placeholder="min" step="0.01">
                            </div>
                            <div class="col-md-6 input-group input-group-sm">
                                <input type="number" name="filter_minimum_stock_max" id="filter_minimum_stock_max"
                                    class="form-control form-control-sm" placeholder="max" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--stock level-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.stock_level')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_stock_level" id="filter_stock_level"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="low_stock">{{ cleanLang(__('lang.low_stock')) }}</option>
                                    <option value="out_of_stock">{{ cleanLang(__('lang.out_of_stock')) }}</option>
                                    <option value="sufficient_stock">{{ cleanLang(__('lang.sufficient_stock')) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--status-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.status')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_inventory_status" id="filter_inventory_status"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="active">{{ cleanLang(__('lang.active')) }}</option>
                                    <option value="inactive">{{ cleanLang(__('lang.inactive')) }}</option>
                                </select>
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
                                <select name="filter_inventory_creatorid" id="filter_inventory_creatorid"
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

                <!--date created-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.date_created')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="filter_created_date_start"
                                    class="form-control form-control-sm pickadate" autocomplete="off"
                                    placeholder="Start">
                                <input class="mysql-date" type="hidden" name="filter_created_date_start"
                                    id="filter_created_date_start" value="">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="filter_created_date_end"
                                    class="form-control form-control-sm pickadate" autocomplete="off" placeholder="End">
                                <input class="mysql-date" type="hidden" name="filter_created_date_end"
                                    id="filter_created_date_end" value="">
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
                        data-url="{{ urlResource('/inventory/search') }}" data-type="form" data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>
            </div>
            <!--body-->
        </div>
    </form>
</div>
<!--sidebar-->
