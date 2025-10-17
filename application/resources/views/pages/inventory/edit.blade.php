@extends('layout.wrapper')
@section('content')
<!-- main content -->
<div class="container-fluid">
    <!-- page content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="inventoryForm" action="{{ _url('/inventory/'.$inventory->inventory_id) }}" method="POST" enctype="multipart/form-data">
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
                                            <label for="inventory_name">{{ cleanLang(__('lang.inventory_name')) }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inventory_name" name="inventory_name" 
                                                   value="{{ $inventory->inventory_name }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_code">{{ cleanLang(__('lang.inventory_code')) }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inventory_code" name="inventory_code" 
                                                   value="{{ $inventory->inventory_code }}" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_sku">{{ cleanLang(__('lang.inventory_sku')) }}</label>
                                            <input type="text" class="form-control" id="inventory_sku" name="inventory_sku" 
                                                   value="{{ $inventory->inventory_sku }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_barcode">{{ cleanLang(__('lang.inventory_barcode')) }}</label>
                                            <input type="text" class="form-control" id="inventory_barcode" name="inventory_barcode" 
                                                   value="{{ $inventory->inventory_barcode }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_description">{{ cleanLang(__('lang.inventory_description')) }}</label>
                                            <textarea class="form-control" id="inventory_description" name="inventory_description" rows="3">{{ $inventory->inventory_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pricing & Stock -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.pricing_stock')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="inventory_quantity">{{ cleanLang(__('lang.inventory_quantity')) }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="inventory_quantity" name="inventory_quantity" 
                                                   value="{{ $inventory->inventory_quantity }}" step="0.01" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_minimum_quantity">{{ cleanLang(__('lang.inventory_minimum_quantity')) }}</label>
                                            <input type="number" class="form-control" id="inventory_minimum_quantity" name="inventory_minimum_quantity" 
                                                   value="{{ $inventory->inventory_minimum_quantity }}" step="0.01">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_selling_price">{{ cleanLang(__('lang.inventory_selling_price')) }} <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="inventory_selling_price" name="inventory_selling_price" 
                                                   value="{{ $inventory->inventory_selling_price }}" step="0.01" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_cost_price">{{ cleanLang(__('lang.inventory_cost_price')) }}</label>
                                            <input type="number" class="form-control" id="inventory_cost_price" name="inventory_cost_price" 
                                                   value="{{ $inventory->inventory_cost_price }}" step="0.01">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_currency">{{ cleanLang(__('lang.inventory_currency')) }}</label>
                                            <select class="form-control" id="inventory_currency" name="inventory_currency">
                                                <option value="IRR" {{ $inventory->inventory_currency == 'IRR' ? 'selected' : '' }}>IRR</option>
                                                <option value="USD" {{ $inventory->inventory_currency == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ $inventory->inventory_currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_unit">{{ cleanLang(__('lang.inventory_unit')) }}</label>
                                            <select class="form-control" id="inventory_unit" name="inventory_unit">
                                                <option value="pcs" {{ $inventory->inventory_unit == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                                <option value="kg" {{ $inventory->inventory_unit == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                                <option value="liter" {{ $inventory->inventory_unit == 'liter' ? 'selected' : '' }}>Liter</option>
                                                <option value="meter" {{ $inventory->inventory_unit == 'meter' ? 'selected' : '' }}>Meter</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_status">{{ cleanLang(__('lang.inventory_status')) }}</label>
                                            <select class="form-control" id="inventory_status" name="inventory_status">
                                                <option value="active" {{ $inventory->inventory_status == 'active' ? 'selected' : '' }}>{{ cleanLang(__('lang.active')) }}</option>
                                                <option value="inactive" {{ $inventory->inventory_status == 'inactive' ? 'selected' : '' }}>{{ cleanLang(__('lang.inactive')) }}</option>
                                                <option value="discontinued" {{ $inventory->inventory_status == 'discontinued' ? 'selected' : '' }}>{{ cleanLang(__('lang.discontinued')) }}</option>
                                            </select>
                                        </div>
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
                                        <div class="form-group">
                                            <label for="inventory_supplier">{{ cleanLang(__('lang.inventory_supplier')) }}</label>
                                            <input type="text" class="form-control" id="inventory_supplier" name="inventory_supplier" 
                                                   value="{{ $inventory->inventory_supplier }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_location">{{ cleanLang(__('lang.inventory_location')) }}</label>
                                            <input type="text" class="form-control" id="inventory_location" name="inventory_location" 
                                                   value="{{ $inventory->inventory_location }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_brand">{{ cleanLang(__('lang.inventory_brand')) }}</label>
                                            <input type="text" class="form-control" id="inventory_brand" name="inventory_brand" 
                                                   value="{{ $inventory->inventory_brand }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="inventory_model">{{ cleanLang(__('lang.inventory_model')) }}</label>
                                            <input type="text" class="form-control" id="inventory_model" name="inventory_model" 
                                                   value="{{ $inventory->inventory_model }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ cleanLang(__('lang.notes')) }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="inventory_notes">{{ cleanLang(__('lang.inventory_notes')) }}</label>
                                            <textarea class="form-control" id="inventory_notes" name="inventory_notes" rows="4">{{ $inventory->inventory_notes }}</textarea>
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
                                                {{ cleanLang(__('lang.update_inventory_item')) }}
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

