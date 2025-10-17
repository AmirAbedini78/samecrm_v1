@extends('layout.wrapper')
@section('content')
<!-- main content -->
<div class="container-fluid">
    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header Actions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">{{ cleanLang(__('lang.sales_details')) }}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ _url('/sales/'.$sales->sales_id.'/edit') }}" class="btn btn-primary">
                                <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                            </a>
                            <a href="{{ _url('/sales') }}" class="btn btn-secondary">
                                <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back_to_list')) }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.basic_information')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_title')) }}:</strong></td>
                                            <td>{{ $sales->sales_title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_code')) }}:</strong></td>
                                            <td>{{ $sales->sales_code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_type')) }}:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $sales->sales_type == 'sale' ? 'success' : 'warning' }}">
                                                    {{ $sales->sales_type }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_date')) }}:</strong></td>
                                            <td>{{ $sales->sales_date }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_description')) }}:</strong></td>
                                            <td>{{ $sales->sales_description ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_status')) }}:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $sales->sales_status == 'completed' ? 'success' : ($sales->sales_status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $sales->sales_status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing & Payment -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.pricing_payment')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_quantity')) }}:</strong></td>
                                            <td>{{ $sales->sales_quantity }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_unit_price')) }}:</strong></td>
                                            <td>{{ formatCurrency($sales->sales_unit_price, $sales->sales_currency) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_total_amount')) }}:</strong></td>
                                            <td>{{ formatCurrency($sales->sales_total_amount, $sales->sales_currency) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_discount_percentage')) }}:</strong></td>
                                            <td>{{ $sales->sales_discount_percentage }}%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_tax_percentage')) }}:</strong></td>
                                            <td>{{ $sales->sales_tax_percentage }}%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_final_amount')) }}:</strong></td>
                                            <td><strong>{{ formatCurrency($sales->sales_final_amount, $sales->sales_currency) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_currency')) }}:</strong></td>
                                            <td>{{ $sales->sales_currency }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_payment_status')) }}:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $sales->sales_payment_status == 'paid' ? 'success' : ($sales->sales_payment_status == 'unpaid' ? 'danger' : 'warning') }}">
                                                    {{ $sales->sales_payment_status }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_payment_method')) }}:</strong></td>
                                            <td>{{ $sales->sales_payment_method ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.customer_information')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_customer_name')) }}:</strong></td>
                                            <td>{{ $sales->sales_customer_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_customer_phone')) }}:</strong></td>
                                            <td>{{ $sales->sales_customer_phone ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_customer_address')) }}:</strong></td>
                                            <td>{{ $sales->sales_customer_address ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_salesperson')) }}:</strong></td>
                                            <td>{{ $sales->sales_salesperson ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.sales_invoice_number')) }}:</strong></td>
                                            <td>{{ $sales->sales_invoice_number ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.notes')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <p>{{ $sales->sales_notes ?? cleanLang(__('lang.no_notes_available')) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.system_information')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.created_by')) }}:</strong></td>
                                                    <td>{{ $sales->creator->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.created_at')) }}:</strong></td>
                                                    <td>{{ $sales->formatted_sales_created }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.updated_at')) }}:</strong></td>
                                                    <td>{{ $sales->formatted_sales_updated }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.sales_id')) }}:</strong></td>
                                                    <td>{{ $sales->formatted_id }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--page content -->
</div>
<!--main content -->
@endsection

