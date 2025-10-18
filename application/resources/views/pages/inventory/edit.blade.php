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
                <h3 class="page-title">{{ cleanLang(__('lang.edit_inventory')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">{{ cleanLang(__('lang.inventory')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.edit_inventory')) }}</li>
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
                        <form id="inventory-edit-form" method="POST" action="/inventory/{{ $inventory->inventory_id }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.inventory_name')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_name" 
                                               value="{{ old('inventory_name', $inventory->inventory_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.inventory_code')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="inventory_code" 
                                               value="{{ old('inventory_code', $inventory->inventory_code) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="first_period_quantity" 
                                               value="{{ old('first_period_quantity', $inventory->first_period_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_sub_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="first_period_sub_quantity" 
                                               value="{{ old('first_period_sub_quantity', $inventory->first_period_sub_quantity) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="first_period_amount" 
                                               value="{{ old('first_period_amount', $inventory->first_period_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.first_period_avg_price')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="first_period_avg_price" 
                                               value="{{ old('first_period_avg_price', $inventory->first_period_avg_price) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="input_quantity" 
                                               value="{{ old('input_quantity', $inventory->input_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_sub_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="input_sub_quantity" 
                                               value="{{ old('input_sub_quantity', $inventory->input_sub_quantity) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="input_amount" 
                                               value="{{ old('input_amount', $inventory->input_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.input_avg_price')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="input_avg_price" 
                                               value="{{ old('input_avg_price', $inventory->input_avg_price) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="output_quantity" 
                                               value="{{ old('output_quantity', $inventory->output_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_sub_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="output_sub_quantity" 
                                               value="{{ old('output_sub_quantity', $inventory->output_sub_quantity) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="output_amount" 
                                               value="{{ old('output_amount', $inventory->output_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.output_avg_price')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="output_avg_price" 
                                               value="{{ old('output_avg_price', $inventory->output_avg_price) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="current_quantity" 
                                               value="{{ old('current_quantity', $inventory->current_quantity) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_sub_quantity')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="current_sub_quantity" 
                                               value="{{ old('current_sub_quantity', $inventory->current_sub_quantity) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_amount')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="current_amount" 
                                               value="{{ old('current_amount', $inventory->current_amount) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.current_avg_price')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="current_avg_price" 
                                               value="{{ old('current_avg_price', $inventory->current_avg_price) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.weighing_input')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="weighing_input" 
                                               value="{{ old('weighing_input', $inventory->weighing_input) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.weighing_output')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="weighing_output" 
                                               value="{{ old('weighing_output', $inventory->weighing_output) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.minimum_stock')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="minimum_stock" 
                                               value="{{ old('minimum_stock', $inventory->minimum_stock) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.maximum_stock')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="maximum_stock" 
                                               value="{{ old('maximum_stock', $inventory->maximum_stock) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.discrepancy')) }}</label>
                                        <input type="number" step="0.01" class="form-control" name="discrepancy" 
                                               value="{{ old('discrepancy', $inventory->discrepancy) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.main_unit')) }}</label>
                                        <input type="text" class="form-control" name="main_unit" 
                                               value="{{ old('main_unit', $inventory->main_unit) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.sub_unit')) }}</label>
                                        <input type="text" class="form-control" name="sub_unit" 
                                               value="{{ old('sub_unit', $inventory->sub_unit) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.status')) }}</label>
                                        <select class="form-control" name="inventory_status">
                                            <option value="active" {{ old('inventory_status', $inventory->inventory_status) == 'active' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.active')) }}
                                            </option>
                                            <option value="inactive" {{ old('inventory_status', $inventory->inventory_status) == 'inactive' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.inactive')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            {{ cleanLang(__('lang.update')) }}
                                        </button>
                                        <a href="{{ _url('/inventory') }}" class="btn btn-secondary">
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