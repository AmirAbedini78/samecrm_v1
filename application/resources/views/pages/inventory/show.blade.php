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
                            <h4 class="card-title">{{ cleanLang(__('lang.inventory_details')) }}</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ _url('/inventory/'.$inventory->inventory_id.'/edit') }}" class="btn btn-primary">
                                <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                            </a>
                            <a href="{{ _url('/inventory') }}" class="btn btn-secondary">
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
                                            <td><strong>{{ cleanLang(__('lang.inventory_name')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_code')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_sku')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_sku ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_barcode')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_barcode ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_description')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_description ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_status')) }}:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $inventory->inventory_status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ $inventory->inventory_status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stock & Pricing -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.stock_pricing')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_quantity')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_quantity }} {{ $inventory->inventory_unit }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_minimum_quantity')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_minimum_quantity ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_selling_price')) }}:</strong></td>
                                            <td>{{ formatCurrency($inventory->inventory_selling_price, $inventory->inventory_currency) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_cost_price')) }}:</strong></td>
                                            <td>{{ formatCurrency($inventory->inventory_cost_price, $inventory->inventory_currency) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_currency')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_currency }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_unit')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_unit }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ cleanLang(__('lang.additional_information')) }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_supplier')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_supplier ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_location')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_location ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_brand')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_brand ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_model')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_model ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ cleanLang(__('lang.inventory_serial_number')) }}:</strong></td>
                                            <td>{{ $inventory->inventory_serial_number ?? '-' }}</td>
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
                                    <p>{{ $inventory->inventory_notes ?? cleanLang(__('lang.no_notes_available')) }}</p>
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
                                                    <td>{{ $inventory->creator->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.created_at')) }}:</strong></td>
                                                    <td>{{ $inventory->formatted_inventory_created }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.updated_at')) }}:</strong></td>
                                                    <td>{{ $inventory->formatted_inventory_updated }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ cleanLang(__('lang.inventory_id')) }}:</strong></td>
                                                    <td>{{ $inventory->formatted_id }}</td>
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

