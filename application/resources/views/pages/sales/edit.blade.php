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
                <h3 class="page-title">{{ cleanLang(__('lang.edit_sales')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/sales">{{ cleanLang(__('lang.sales')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.edit_sales')) }}</li>
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
                        <form id="sales-edit-form" method="POST" action="/sales/{{ $sales->sales_id }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.document_type')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="document_type" 
                                               value="{{ old('document_type', $sales->document_type) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.document_number')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="document_number" 
                                               value="{{ old('document_number', $sales->document_number) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.document_date')) }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="document_date" 
                                               value="{{ old('document_date', $sales->document_date) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.customer_code')) }}</label>
                                        <input type="text" class="form-control" name="customer_code" 
                                               value="{{ old('customer_code', $sales->customer_code) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.customer_name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" 
                                               value="{{ old('customer_name', $sales->customer_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.customer_full_name')) }}</label>
                                        <input type="text" class="form-control" name="customer_full_name" 
                                               value="{{ old('customer_full_name', $sales->customer_full_name) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.sales_type')) }}</label>
                                        <input type="text" class="form-control" name="sales_type" 
                                               value="{{ old('sales_type', $sales->sales_type) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.product_code')) }}</label>
                                        <input type="text" class="form-control" name="product_code" 
                                               value="{{ old('product_code', $sales->product_code) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.product_name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" 
                                               value="{{ old('product_name', $sales->product_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.product_barcode')) }}</label>
                                        <input type="text" class="form-control" name="product_barcode" 
                                               value="{{ old('product_barcode', $sales->product_barcode) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.tracking_code')) }}</label>
                                        <input type="text" class="form-control" name="tracking_code" 
                                               value="{{ old('tracking_code', $sales->tracking_code) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.main_unit')) }}</label>
                                        <input type="text" class="form-control" name="main_unit" 
                                               value="{{ old('main_unit', $sales->main_unit) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.main_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="main_quantity" 
                                               value="{{ old('main_quantity', $sales->main_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.warehouse')) }}</label>
                                        <input type="text" class="form-control" name="warehouse" 
                                               value="{{ old('warehouse', $sales->warehouse) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_price')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_price" 
                                               value="{{ old('base_price', $sales->base_price) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_sales_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_sales_amount" 
                                               value="{{ old('base_sales_amount', $sales->base_sales_amount) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_tax_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_tax_amount" 
                                               value="{{ old('base_tax_amount', $sales->base_tax_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_duty_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_duty_amount" 
                                               value="{{ old('base_duty_amount', $sales->base_duty_amount) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_additional_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_additional_amount" 
                                               value="{{ old('base_additional_amount', $sales->base_additional_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_increasing_factors')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_increasing_factors" 
                                               value="{{ old('base_increasing_factors', $sales->base_increasing_factors) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_net_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="base_net_amount" 
                                               value="{{ old('base_net_amount', $sales->base_net_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.month')) }}</label>
                                        <input type="text" class="form-control" name="month" 
                                               value="{{ old('month', $sales->month) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.issued_main_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="issued_main_quantity" 
                                               value="{{ old('issued_main_quantity', $sales->issued_main_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.issued_sub_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="issued_sub_quantity" 
                                               value="{{ old('issued_sub_quantity', $sales->issued_sub_quantity) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.remaining_main_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="remaining_main_quantity" 
                                               value="{{ old('remaining_main_quantity', $sales->remaining_main_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.remaining_sub_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="remaining_sub_quantity" 
                                               value="{{ old('remaining_sub_quantity', $sales->remaining_sub_quantity) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.currency')) }}</label>
                                        <input type="text" class="form-control" name="currency" 
                                               value="{{ old('currency', $sales->currency) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.sales_status')) }}</label>
                                        <select class="form-control" name="sales_status">
                                            <option value="pending" {{ old('sales_status', $sales->sales_status) == 'pending' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.pending')) }}
                                            </option>
                                            <option value="completed" {{ old('sales_status', $sales->sales_status) == 'completed' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.completed')) }}
                                            </option>
                                            <option value="cancelled" {{ old('sales_status', $sales->sales_status) == 'cancelled' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.cancelled')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.description')) }}</label>
                                        <textarea class="form-control" name="description" rows="3">{{ old('description', $sales->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            {{ cleanLang(__('lang.update')) }}
                                        </button>
                                        <a href="{{ _url('/sales') }}" class="btn btn-secondary">
                                            {{ cleanLang(__('lang.cancel')) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
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