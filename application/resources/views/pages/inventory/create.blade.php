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
                <h3 class="page-title">{{ cleanLang(__('lang.add_inventory')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">{{ cleanLang(__('lang.inventory')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.add_inventory')) }}</li>
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
                        <form id="inventory-create-form" method="POST" action="/inventory">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.inventory_name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_name" 
                                               value="{{ old('inventory_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.inventory_code')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_code" 
                                               value="{{ old('inventory_code') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.description')) }}</label>
                                        <textarea class="form-control" name="inventory_description" rows="3">{{ old('inventory_description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.barcode')) }}</label>
                                        <input type="text" class="form-control" name="inventory_barcode" 
                                               value="{{ old('inventory_barcode') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.category')) }}</label>
                                        <select class="form-control" name="inventory_categoryid">
                                            <option value="">{{ cleanLang(__('lang.select_category')) }}</option>
                                            @if($categories)
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->category_id }}" 
                                                            {{ old('inventory_categoryid') == $category->category_id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.status')) }}</label>
                                        <select class="form-control" name="inventory_status">
                                            <option value="active" {{ old('inventory_status', 'active') == 'active' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.active')) }}
                                            </option>
                                            <option value="inactive" {{ old('inventory_status') == 'inactive' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.inactive')) }}
                                            </option>
                                            <option value="discontinued" {{ old('inventory_status') == 'discontinued' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.discontinued')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- First Period Section -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.first_period')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_quantity')) }}</label>
                                        <input type="number" class="form-control" name="first_period_quantity" 
                                               value="{{ old('first_period_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_sub_quantity')) }}</label>
                                        <input type="number" class="form-control" name="first_period_sub_quantity" 
                                               value="{{ old('first_period_sub_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_amount')) }}</label>
                                        <input type="number" class="form-control" name="first_period_amount" 
                                               value="{{ old('first_period_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_avg_price')) }}</label>
                                        <input type="number" class="form-control" name="first_period_avg_price" 
                                               value="{{ old('first_period_avg_price', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Input Section -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.input_section')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_quantity')) }}</label>
                                        <input type="number" class="form-control" name="input_quantity" 
                                               value="{{ old('input_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_sub_quantity')) }}</label>
                                        <input type="number" class="form-control" name="input_sub_quantity" 
                                               value="{{ old('input_sub_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_amount')) }}</label>
                                        <input type="number" class="form-control" name="input_amount" 
                                               value="{{ old('input_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_avg_price')) }}</label>
                                        <input type="number" class="form-control" name="input_avg_price" 
                                               value="{{ old('input_avg_price', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Output Section -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.output_section')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_quantity')) }}</label>
                                        <input type="number" class="form-control" name="output_quantity" 
                                               value="{{ old('output_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_sub_quantity')) }}</label>
                                        <input type="number" class="form-control" name="output_sub_quantity" 
                                               value="{{ old('output_sub_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_amount')) }}</label>
                                        <input type="number" class="form-control" name="output_amount" 
                                               value="{{ old('output_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_avg_price')) }}</label>
                                        <input type="number" class="form-control" name="output_avg_price" 
                                               value="{{ old('output_avg_price', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Current Stock Section -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.current_stock')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_quantity')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="current_quantity" 
                                               value="{{ old('current_quantity', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_sub_quantity')) }}</label>
                                        <input type="number" class="form-control" name="current_sub_quantity" 
                                               value="{{ old('current_sub_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_amount')) }}</label>
                                        <input type="number" class="form-control" name="current_amount" 
                                               value="{{ old('current_amount', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_avg_price')) }}</label>
                                        <input type="number" class="form-control" name="current_avg_price" 
                                               value="{{ old('current_avg_price', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Weighing Section -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.weighing_section')) }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.weighing_input')) }}</label>
                                        <input type="number" class="form-control" name="weighing_input" 
                                               value="{{ old('weighing_input', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.weighing_output')) }}</label>
                                        <input type="number" class="form-control" name="weighing_output" 
                                               value="{{ old('weighing_output', 0) }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Limits & Units -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.stock_limits_units')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.minimum_stock')) }}</label>
                                        <input type="number" class="form-control" name="minimum_stock" 
                                               value="{{ old('minimum_stock', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.maximum_stock')) }}</label>
                                        <input type="number" class="form-control" name="maximum_stock" 
                                               value="{{ old('maximum_stock') }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.discrepancy')) }}</label>
                                        <input type="number" class="form-control" name="discrepancy" 
                                               value="{{ old('discrepancy', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.main_unit')) }}</label>
                                        <select class="form-control" name="main_unit">
                                            <option value="pcs" {{ old('main_unit', 'pcs') == 'pcs' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.pieces')) }}
                                            </option>
                                            <option value="kg" {{ old('main_unit') == 'kg' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.kilograms')) }}
                                            </option>
                                            <option value="liter" {{ old('main_unit') == 'liter' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.liters')) }}
                                            </option>
                                            <option value="box" {{ old('main_unit') == 'box' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.boxes')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.sub_unit')) }}</label>
                                        <input type="text" class="form-control" name="sub_unit" 
                                               value="{{ old('sub_unit') }}" placeholder="e.g., grams, ml">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.cost_price')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="inventory_cost_price" 
                                               value="{{ old('inventory_cost_price', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.selling_price')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="inventory_selling_price" 
                                               value="{{ old('inventory_selling_price', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.currency')) }}</label>
                                        <select class="form-control" name="inventory_currency">
                                            <option value="USD" {{ old('inventory_currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('inventory_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="GBP" {{ old('inventory_currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="IRR" {{ old('inventory_currency') == 'IRR' ? 'selected' : '' }}>IRR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.supplier')) }}</label>
                                        <input type="text" class="form-control" name="inventory_supplier" 
                                               value="{{ old('inventory_supplier') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.location')) }}</label>
                                        <input type="text" class="form-control" name="inventory_location" 
                                               value="{{ old('inventory_location') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.last_restocked')) }}</label>
                                        <input type="date" class="form-control" name="inventory_last_restocked" 
                                               value="{{ old('inventory_last_restocked') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.expiry_date')) }}</label>
                                        <input type="date" class="form-control" name="inventory_expiry_date" 
                                               value="{{ old('inventory_expiry_date') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.notes')) }}</label>
                                        <textarea class="form-control" name="inventory_notes" rows="3">{{ old('inventory_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-save"></i> {{ cleanLang(__('lang.save_inventory')) }}
                                        </button>
                                        <a href="/inventory" class="btn btn-secondary">
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

