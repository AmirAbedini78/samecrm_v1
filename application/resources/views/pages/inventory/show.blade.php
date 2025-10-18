@extends('layout.wrapper')

@section('content')
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">{{ cleanLang(__('lang.view_inventory')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">{{ cleanLang(__('lang.inventory')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.view_inventory')) }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Page Content -->
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.inventory_name')) }}</label>
                                    <p class="form-control-plaintext">{{ $inventory->inventory_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.inventory_code')) }}</label>
                                    <p class="form-control-plaintext">{{ $inventory->inventory_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.first_period_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->first_period_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.first_period_sub_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->first_period_sub_quantity, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.first_period_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->first_period_amount, 'IRR') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.first_period_avg_price')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->first_period_avg_price, 'IRR') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.input_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->input_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.input_sub_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->input_sub_quantity, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.input_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->input_amount, 'IRR') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.input_avg_price')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->input_avg_price, 'IRR') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.output_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->output_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.output_sub_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->output_sub_quantity, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.output_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->output_amount, 'IRR') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.output_avg_price')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->output_avg_price, 'IRR') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.current_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->current_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.current_sub_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->current_sub_quantity, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.current_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->current_amount, 'IRR') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.current_avg_price')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($inventory->current_avg_price, 'IRR') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.weighing_input')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->weighing_input, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.weighing_output')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->weighing_output, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.minimum_stock')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->minimum_stock, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.maximum_stock')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->maximum_stock, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.discrepancy')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($inventory->discrepancy, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.main_unit')) }}</label>
                                    <p class="form-control-plaintext">{{ $inventory->main_unit }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.sub_unit')) }}</label>
                                    <p class="form-control-plaintext">{{ $inventory->sub_unit }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.status')) }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge badge-{{ $inventory->inventory_status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $inventory->inventory_status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.created_at')) }}</label>
                                    <p class="form-control-plaintext">{{ $inventory->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.updated_at')) }}</label>
                                    <p class="form-control-plaintext">{{ $inventory->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ _url('/inventory') }}" class="btn btn-secondary">
                                        {{ cleanLang(__('lang.back_to_inventory')) }}
                                    </a>
                                    <a href="{{ _url('/inventory/'.$inventory->inventory_id.'/edit') }}" class="btn btn-primary">
                                        {{ cleanLang(__('lang.edit')) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content -->
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection