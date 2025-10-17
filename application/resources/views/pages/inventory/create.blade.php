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
                                        <label>{{ cleanLang(__('lang.name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_name" 
                                               value="{{ old('inventory_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.sku')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_sku" 
                                               value="{{ old('inventory_sku') }}" required>
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

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.quantity')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="inventory_quantity" 
                                               value="{{ old('inventory_quantity', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.minimum_quantity')) }}</label>
                                        <input type="number" class="form-control" name="inventory_minimum_quantity" 
                                               value="{{ old('inventory_minimum_quantity', 0) }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.maximum_quantity')) }}</label>
                                        <input type="number" class="form-control" name="inventory_maximum_quantity" 
                                               value="{{ old('inventory_maximum_quantity') }}" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.unit')) }}</label>
                                        <select class="form-control" name="inventory_unit">
                                            <option value="pcs" {{ old('inventory_unit', 'pcs') == 'pcs' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.pieces')) }}
                                            </option>
                                            <option value="kg" {{ old('inventory_unit') == 'kg' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.kilograms')) }}
                                            </option>
                                            <option value="liter" {{ old('inventory_unit') == 'liter' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.liters')) }}
                                            </option>
                                            <option value="box" {{ old('inventory_unit') == 'box' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.boxes')) }}
                                            </option>
                                        </select>
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

