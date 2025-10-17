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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.title')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sales_title" 
                                               value="{{ old('sales_title') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.reference')) }}</label>
                                        <input type="text" class="form-control" name="sales_reference" 
                                               value="{{ old('sales_reference') }}">
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

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.client')) }}</label>
                                        <select class="form-control" name="sales_clientid">
                                            <option value="">{{ cleanLang(__('lang.select_client')) }}</option>
                                            @if($clients)
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->client_id }}" 
                                                            {{ old('sales_clientid') == $client->client_id ? 'selected' : '' }}>
                                                        {{ $client->client_company_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.project')) }}</label>
                                        <select class="form-control" name="sales_projectid">
                                            <option value="">{{ cleanLang(__('lang.select_project')) }}</option>
                                            @if($projects)
                                                @foreach($projects as $project)
                                                    <option value="{{ $project->project_id }}" 
                                                            {{ old('sales_projectid') == $project->project_id ? 'selected' : '' }}>
                                                        {{ $project->project_title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.category')) }}</label>
                                        <select class="form-control" name="sales_categoryid">
                                            <option value="">{{ cleanLang(__('lang.select_category')) }}</option>
                                            @if($categories)
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->category_id }}" 
                                                            {{ old('sales_categoryid') == $category->category_id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.type')) }}</label>
                                        <select class="form-control" name="sales_type">
                                            <option value="sale" {{ old('sales_type', 'sale') == 'sale' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.sale')) }}
                                            </option>
                                            <option value="return" {{ old('sales_type') == 'return' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.return')) }}
                                            </option>
                                            <option value="refund" {{ old('sales_type') == 'refund' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.refund')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.quantity')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="sales_quantity" 
                                               value="{{ old('sales_quantity', 1) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.unit_price')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="sales_unit_price" 
                                               value="{{ old('sales_unit_price', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.currency')) }}</label>
                                        <select class="form-control" name="sales_currency">
                                            <option value="USD" {{ old('sales_currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('sales_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="GBP" {{ old('sales_currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="IRR" {{ old('sales_currency') == 'IRR' ? 'selected' : '' }}>IRR</option>
                                        </select>
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

