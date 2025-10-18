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
                <h3 class="page-title">{{ cleanLang(__('lang.view_sales')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/sales">{{ cleanLang(__('lang.sales')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.view_sales')) }}</li>
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
                                    <label>{{ cleanLang(__('lang.document_type')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->document_type }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.document_number')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->document_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.document_date')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->document_date }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.customer_code')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->customer_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.customer_name')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->customer_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.customer_full_name')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->customer_full_name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.sales_type')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->sales_type }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.product_code')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->product_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.product_name')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->product_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.product_barcode')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->product_barcode }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.tracking_code')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->tracking_code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.main_unit')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->main_unit }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.main_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($sales->main_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.warehouse')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->warehouse }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_price')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_price, $sales->currency) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_sales_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_sales_amount, $sales->currency) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_tax_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_tax_amount, $sales->currency) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_duty_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_duty_amount, $sales->currency) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_additional_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_additional_amount, $sales->currency) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_increasing_factors')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_increasing_factors, $sales->currency) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.base_net_amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($sales->base_net_amount, $sales->currency) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.month')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->month }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.issued_main_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($sales->issued_main_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.issued_sub_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($sales->issued_sub_quantity, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.remaining_main_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($sales->remaining_main_quantity, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.remaining_sub_quantity')) }}</label>
                                    <p class="form-control-plaintext">{{ number_format($sales->remaining_sub_quantity, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.currency')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->currency }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.sales_status')) }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge badge-{{ $sales->sales_status == 'completed' ? 'success' : 'warning' }}">
                                            {{ $sales->sales_status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.description')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->description }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.created_at')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.updated_at')) }}</label>
                                    <p class="form-control-plaintext">{{ $sales->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ _url('/sales') }}" class="btn btn-secondary">
                                        {{ cleanLang(__('lang.back_to_sales')) }}
                                    </a>
                                    <a href="{{ _url('/sales/'.$sales->sales_id.'/edit') }}" class="btn btn-primary">
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