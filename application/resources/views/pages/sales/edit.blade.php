@extends('layout.wrapper')
@section('content')
<!-- main content -->
<div class="container-fluid">
    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="salesForm" action="{{ _url('/sales/'.$sales->sales_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.basic_information')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="sales_title">{{ cleanLang(__('lang.sales_title')) }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="sales_title" name="sales_title" 
                                                   value="{{ $sales->sales_title }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_code">{{ cleanLang(__('lang.sales_code')) }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="sales_code" name="sales_code" 
                                                   value="{{ $sales->sales_code }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_type">{{ cleanLang(__('lang.sales_type')) }}</label>
                                            <select class="form-control" id="sales_type" name="sales_type">
                                                <option value="sale" {{ $sales->sales_type == 'sale' ? 'selected' : '' }}>{{ cleanLang(__('lang.sale')) }}</option>
                                                <option value="return" {{ $sales->sales_type == 'return' ? 'selected' : '' }}>{{ cleanLang(__('lang.return')) }}</option>
                                                <option value="refund" {{ $sales->sales_type == 'refund' ? 'selected' : '' }}>{{ cleanLang(__('lang.refund')) }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_date">{{ cleanLang(__('lang.sales_date')) }} <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="sales_date" name="sales_date" 
                                                   value="{{ $sales->sales_date }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_description">{{ cleanLang(__('lang.sales_description')) }}</label>
                                            <textarea class="form-control" id="sales_description" name="sales_description" rows="3">{{ $sales->sales_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pricing & Quantity -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.pricing_quantity')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="sales_quantity">{{ cleanLang(__('lang.sales_quantity')) }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="sales_quantity" name="sales_quantity" 
                                                   value="{{ $sales->sales_quantity }}" step="0.01" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_unit_price">{{ cleanLang(__('lang.sales_unit_price')) }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="sales_unit_price" name="sales_unit_price" 
                                                   value="{{ $sales->sales_unit_price }}" step="0.01" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_total_amount">{{ cleanLang(__('lang.sales_total_amount')) }}</label>
                                            <input type="number" class="form-control" id="sales_total_amount" name="sales_total_amount" 
                                                   value="{{ $sales->sales_total_amount }}" step="0.01" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_discount_percentage">{{ cleanLang(__('lang.sales_discount_percentage')) }}</label>
                                            <input type="number" class="form-control" id="sales_discount_percentage" name="sales_discount_percentage" 
                                                   value="{{ $sales->sales_discount_percentage }}" step="0.01" max="100">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_tax_percentage">{{ cleanLang(__('lang.sales_tax_percentage')) }}</label>
                                            <input type="number" class="form-control" id="sales_tax_percentage" name="sales_tax_percentage" 
                                                   value="{{ $sales->sales_tax_percentage }}" step="0.01" max="100">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_final_amount">{{ cleanLang(__('lang.sales_final_amount')) }}</label>
                                            <input type="number" class="form-control" id="sales_final_amount" name="sales_final_amount" 
                                                   value="{{ $sales->sales_final_amount }}" step="0.01" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_currency">{{ cleanLang(__('lang.sales_currency')) }}</label>
                                            <select class="form-control" id="sales_currency" name="sales_currency">
                                                <option value="IRR" {{ $sales->sales_currency == 'IRR' ? 'selected' : '' }}>IRR</option>
                                                <option value="USD" {{ $sales->sales_currency == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ $sales->sales_currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status & Payment -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.status_payment')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="sales_status">{{ cleanLang(__('lang.sales_status')) }}</label>
                                            <select class="form-control" id="sales_status" name="sales_status">
                                                <option value="pending" {{ $sales->sales_status == 'pending' ? 'selected' : '' }}>{{ cleanLang(__('lang.pending')) }}</option>
                                                <option value="completed" {{ $sales->sales_status == 'completed' ? 'selected' : '' }}>{{ cleanLang(__('lang.completed')) }}</option>
                                                <option value="cancelled" {{ $sales->sales_status == 'cancelled' ? 'selected' : '' }}>{{ cleanLang(__('lang.cancelled')) }}</option>
                                                <option value="refunded" {{ $sales->sales_status == 'refunded' ? 'selected' : '' }}>{{ cleanLang(__('lang.refunded')) }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_payment_status">{{ cleanLang(__('lang.sales_payment_status')) }}</label>
                                            <select class="form-control" id="sales_payment_status" name="sales_payment_status">
                                                <option value="unpaid" {{ $sales->sales_payment_status == 'unpaid' ? 'selected' : '' }}>{{ cleanLang(__('lang.unpaid')) }}</option>
                                                <option value="paid" {{ $sales->sales_payment_status == 'paid' ? 'selected' : '' }}>{{ cleanLang(__('lang.paid')) }}</option>
                                                <option value="partially_paid" {{ $sales->sales_payment_status == 'partially_paid' ? 'selected' : '' }}>{{ cleanLang(__('lang.partially_paid')) }}</option>
                                                <option value="overdue" {{ $sales->sales_payment_status == 'overdue' ? 'selected' : '' }}>{{ cleanLang(__('lang.overdue')) }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_payment_method">{{ cleanLang(__('lang.sales_payment_method')) }}</label>
                                            <input type="text" class="form-control" id="sales_payment_method" name="sales_payment_method" 
                                                   value="{{ $sales->sales_payment_method }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_due_date">{{ cleanLang(__('lang.sales_due_date')) }}</label>
                                            <input type="date" class="form-control" id="sales_due_date" name="sales_due_date" 
                                                   value="{{ $sales->sales_due_date }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Customer Information -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.customer_information')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="sales_customer_name">{{ cleanLang(__('lang.sales_customer_name')) }}</label>
                                            <input type="text" class="form-control" id="sales_customer_name" name="sales_customer_name" 
                                                   value="{{ $sales->sales_customer_name }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_customer_phone">{{ cleanLang(__('lang.sales_customer_phone')) }}</label>
                                            <input type="text" class="form-control" id="sales_customer_phone" name="sales_customer_phone" 
                                                   value="{{ $sales->sales_customer_phone }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_customer_address">{{ cleanLang(__('lang.sales_customer_address')) }}</label>
                                            <textarea class="form-control" id="sales_customer_address" name="sales_customer_address" rows="3">{{ $sales->sales_customer_address }}</textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_salesperson">{{ cleanLang(__('lang.sales_salesperson')) }}</label>
                                            <input type="text" class="form-control" id="sales_salesperson" name="sales_salesperson" 
                                                   value="{{ $sales->sales_salesperson }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="sales_invoice_number">{{ cleanLang(__('lang.sales_invoice_number')) }}</label>
                                            <input type="text" class="form-control" id="sales_invoice_number" name="sales_invoice_number" 
                                                   value="{{ $sales->sales_invoice_number }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.notes')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="sales_notes">{{ cleanLang(__('lang.sales_notes')) }}</label>
                                            <textarea class="form-control" id="sales_notes" name="sales_notes" rows="4">{{ $sales->sales_notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group text-right">
                                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                                {{ cleanLang(__('lang.cancel')) }}
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                {{ cleanLang(__('lang.update_sales_record')) }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--page content -->
</div>
<!--main content -->
@endsection

