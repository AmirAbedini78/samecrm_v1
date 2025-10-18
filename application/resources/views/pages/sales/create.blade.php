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
                <h3 class="page-title">{{ cleanLang(__('lang.add_sales')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/sales">{{ cleanLang(__('lang.sales')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.add_sales')) }}</li>
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
                        <form id="sales-create-form" method="POST" action="/sales">
                            @csrf
                            <!-- Document Information -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.document_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.document_type')) }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="document_type" required>
                                            <option value="sale" {{ old('document_type', 'sale') == 'sale' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.sale')) }}
                                            </option>
                                            <option value="return" {{ old('document_type') == 'return' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.return')) }}
                                            </option>
                                            <option value="refund" {{ old('document_type') == 'refund' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.refund')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.document_number')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="document_number" 
                                               value="{{ old('document_number') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.document_date')) }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="document_date" 
                                               value="{{ old('document_date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Information -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.customer_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.customer_code')) }}</label>
                                        <input type="text" class="form-control" name="customer_code" 
                                               value="{{ old('customer_code') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.customer_name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" 
                                               value="{{ old('customer_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.customer_full_name')) }}</label>
                                        <input type="text" class="form-control" name="customer_full_name" 
                                               value="{{ old('customer_full_name') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Product Information -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.product_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.product_code')) }}</label>
                                        <input type="text" class="form-control" name="product_code" 
                                               value="{{ old('product_code') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.product_name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" 
                                               value="{{ old('product_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.product_barcode')) }}</label>
                                        <input type="text" class="form-control" name="product_barcode" 
                                               value="{{ old('product_barcode') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.tracking_code')) }}</label>
                                        <input type="text" class="form-control" name="tracking_code" 
                                               value="{{ old('tracking_code') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.description')) }}</label>
                                        <textarea class="form-control" name="sales_description" rows="3">{{ old('sales_description') }}</textarea>
                                    </div>
                                </div>
                            </div>


                            <!-- Pricing & Quantity -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.pricing_quantity')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.main_unit')) }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="main_unit" required>
                                            <option value="pcs" {{ old('main_unit', 'pcs') == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                            <option value="kg" {{ old('main_unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                            <option value="liter" {{ old('main_unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                            <option value="meter" {{ old('main_unit') == 'meter' ? 'selected' : '' }}>Meter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.main_quantity')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="main_quantity" 
                                               value="{{ old('main_quantity', 1) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.warehouse')) }}</label>
                                        <input type="text" class="form-control" name="warehouse" 
                                               value="{{ old('warehouse') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.currency')) }}</label>
                                        <select class="form-control" name="currency">
                                            <option value="IRR" {{ old('currency', 'IRR') == 'IRR' ? 'selected' : '' }}>IRR</option>
                                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Base Currency Pricing -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.base_currency_pricing')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_price')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="base_price" 
                                               value="{{ old('base_price', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_sales_amount')) }}</label>
                                        <input type="number" class="form-control" name="base_sales_amount" 
                                               value="{{ old('base_sales_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_tax_amount')) }}</label>
                                        <input type="number" class="form-control" name="base_tax_amount" 
                                               value="{{ old('base_tax_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_duty_amount')) }}</label>
                                        <input type="number" class="form-control" name="base_duty_amount" 
                                               value="{{ old('base_duty_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_additional_amount')) }}</label>
                                        <input type="number" class="form-control" name="base_additional_amount" 
                                               value="{{ old('base_additional_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_increasing_factors')) }}</label>
                                        <input type="number" class="form-control" name="base_increasing_factors" 
                                               value="{{ old('base_increasing_factors', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.base_net_amount')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="base_net_amount" 
                                               value="{{ old('base_net_amount', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.month')) }}</label>
                                        <input type="text" class="form-control" name="month" 
                                               value="{{ old('month') }}" placeholder="e.g., 1403/01">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.additional_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.description')) }}</label>
                                        <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantities -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.quantities')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.issued_main_quantity')) }}</label>
                                        <input type="number" class="form-control" name="issued_main_quantity" 
                                               value="{{ old('issued_main_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.issued_sub_quantity')) }}</label>
                                        <input type="number" class="form-control" name="issued_sub_quantity" 
                                               value="{{ old('issued_sub_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.remaining_main_quantity')) }}</label>
                                        <input type="number" class="form-control" name="remaining_main_quantity" 
                                               value="{{ old('remaining_main_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.remaining_sub_quantity')) }}</label>
                                        <input type="number" class="form-control" name="remaining_sub_quantity" 
                                               value="{{ old('remaining_sub_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.discount_amount')) }}</label>
                                        <input type="number" class="form-control" name="sales_discount_amount" 
                                               value="{{ old('sales_discount_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.tax_amount')) }}</label>
                                        <input type="number" class="form-control" name="sales_tax_amount" 
                                               value="{{ old('sales_tax_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.final_amount')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="sales_final_amount" 
                                               value="{{ old('sales_final_amount', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.salesperson')) }}</label>
                                        <input type="text" class="form-control" name="sales_salesperson" 
                                               value="{{ old('sales_salesperson') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.status')) }}</label>
                                        <select class="form-control" name="sales_status">
                                            <option value="pending" {{ old('sales_status', 'pending') == 'pending' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.pending')) }}
                                            </option>
                                            <option value="completed" {{ old('sales_status') == 'completed' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.completed')) }}
                                            </option>
                                            <option value="cancelled" {{ old('sales_status') == 'cancelled' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.cancelled')) }}
                                            </option>
                                            <option value="refunded" {{ old('sales_status') == 'refunded' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.refunded')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.payment_status')) }}</label>
                                        <select class="form-control" name="sales_payment_status">
                                            <option value="unpaid" {{ old('sales_payment_status', 'unpaid') == 'unpaid' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.unpaid')) }}
                                            </option>
                                            <option value="paid" {{ old('sales_payment_status') == 'paid' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.paid')) }}
                                            </option>
                                            <option value="partially_paid" {{ old('sales_payment_status') == 'partially_paid' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.partially_paid')) }}
                                            </option>
                                            <option value="overdue" {{ old('sales_payment_status') == 'overdue' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.overdue')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.payment_method')) }}</label>
                                        <select class="form-control" name="sales_payment_method">
                                            <option value="">{{ cleanLang(__('lang.select_payment_method')) }}</option>
                                            <option value="cash" {{ old('sales_payment_method') == 'cash' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.cash')) }}
                                            </option>
                                            <option value="bank_transfer" {{ old('sales_payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.bank_transfer')) }}
                                            </option>
                                            <option value="credit_card" {{ old('sales_payment_method') == 'credit_card' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.credit_card')) }}
                                            </option>
                                            <option value="check" {{ old('sales_payment_method') == 'check' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.check')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.date')) }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="sales_date" 
                                               value="{{ old('sales_date', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.due_date')) }}</label>
                                        <input type="date" class="form-control" name="sales_due_date" 
                                               value="{{ old('sales_due_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.total_amount')) }}</label>
                                        <input type="number" class="form-control" name="sales_total_amount" 
                                               value="{{ old('sales_total_amount', 0) }}" step="0.01" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.notes')) }}</label>
                                        <textarea class="form-control" name="sales_notes" rows="3">{{ old('sales_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-save"></i> {{ cleanLang(__('lang.save_sales')) }}
                                        </button>
                                        <a href="/sales" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.cancel')) }}
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

