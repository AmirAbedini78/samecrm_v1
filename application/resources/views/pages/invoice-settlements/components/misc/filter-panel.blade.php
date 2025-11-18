<div class="right-sidebar" id="sidepanel-filter-invoice-settlements">
    <form>
        <div class="slimscrollright">
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_invoice_settlements')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-invoice-settlements"></i>
                </span>
            </div>
            <div class="r-panel-body">
                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.document_number')) }}</div>
                    <div class="fields">
                        <input type="text" name="filter_document_number" class="form-control form-control-sm"
                            placeholder="{{ cleanLang(__('lang.document_number')) }}">
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.customer_name')) }}</div>
                    <div class="fields">
                        <input type="text" name="filter_customer_name" class="form-control form-control-sm"
                            placeholder="{{ cleanLang(__('lang.customer_name')) }}">
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.base_net_amount')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_base_net_amount_min"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min')) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_base_net_amount_max"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.paid_amount')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_paid_amount_min"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min')) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_paid_amount_max"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.balance_amount')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_balance_amount_min"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min')) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_balance_amount_max"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.document_date')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" name="filter_document_date_start" class="form-control form-control-sm"
                                    placeholder="{{ cleanLang(__('lang.start')) }}">
                            </div>
                            <div class="col-6">
                                <input type="text" name="filter_document_date_end" class="form-control form-control-sm"
                                    placeholder="{{ cleanLang(__('lang.end')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.currency')) }}</div>
                    <div class="fields">
                        <select name="filter_currency[]" class="form-control form-control-sm select2-basic" multiple>
                            <option value="IRR">IRR</option>
                        </select>
                    </div>
                </div>

                <div class="buttons-block">
                    <button type="button" class="btn btn-rounded-x btn-secondary js-reset-filter-side-panel">
                        {{ cleanLang(__('lang.reset')) }}
                    </button>
                    <button type="button"
                        class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/invoice-settlements/search') }}"
                        data-type="form"
                        data-ajax-type="GET">
                        {{ cleanLang(__('lang.apply_filter')) }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

