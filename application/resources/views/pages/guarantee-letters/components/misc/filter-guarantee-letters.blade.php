<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-guarantee-letters">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_guarantee_letters')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-guarantee-letters"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body">

                <!--guarantee number-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.guarantee_number')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_guarantee_number" id="filter_guarantee_number"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.guarantee_number')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--guarantee type-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.guarantee_type')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_guarantee_type" id="filter_guarantee_type"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="شرکت در مناقصه">{{ cleanLang(__('lang.bid_bond')) }}</option>
                                    <option value="حسن انجام کار">{{ cleanLang(__('lang.performance_bond')) }}</option>
                                    <option value="پیش پرداخت">{{ cleanLang(__('lang.advance_payment_bond')) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--industrial type-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.industrial_type')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_industrial_type" id="filter_industrial_type"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="بلبرینگ">{{ cleanLang(__('lang.bearing')) }}</option>
                                    <option value="بلزونا">{{ cleanLang(__('lang.belzona')) }}</option>
                                    <option value="پایپ">{{ cleanLang(__('lang.pipe')) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--beneficiary-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.beneficiary')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_beneficiary" id="filter_beneficiary"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.beneficiary')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--issuing bank-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.issuing_bank')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="filter_issuing_bank" id="filter_issuing_bank"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.issuing_bank')) }}">
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
                                <select name="filter_guarantee_status" id="filter_guarantee_status"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    <option value="active">{{ cleanLang(__('lang.active')) }}</option>
                                    <option value="expired">{{ cleanLang(__('lang.expired')) }}</option>
                                    <option value="claimed">{{ cleanLang(__('lang.claimed')) }}</option>
                                    <option value="returned">{{ cleanLang(__('lang.returned')) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!--assigned users-->
                @if(config('visibility.guarantee_letters_filter_assigned_users'))
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.assigned_users')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_assigned_users" id="filter_assigned_users"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @if(@count($users ?? []))
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!--expiry date range-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.expiry_date_range')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date" name="filter_expiry_date_from" id="filter_expiry_date_from"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.from_date')) }}">
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="filter_expiry_date_to" id="filter_expiry_date_to"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.to_date')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!--amount range-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.amount_range')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" name="filter_amount_from" id="filter_amount_from"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min_amount')) }}" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="filter_amount_to" id="filter_amount_to"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max_amount')) }}" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>

                <!--filter buttons-->
                <div class="filter-block">
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-sm btn-block js-ajax-ux-request"
                                    data-url="{{ urlResource('/guarantee-letters?action=search') }}"
                                    data-type="form"
                                    data-form-id="sidepanel-filter-guarantee-letters"
                                    data-ajax-type="POST">
                                    <i class="ti-filter"></i> {{ cleanLang(__('lang.apply_filters')) }}
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm btn-block"
                                    onclick="window.location.href='{{ urlResource('/guarantee-letters') }}'">
                                    <i class="ti-refresh"></i> {{ cleanLang(__('lang.clear_filters')) }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--body-->
        </div>
    </form>
</div>
<!-- right-sidebar -->

