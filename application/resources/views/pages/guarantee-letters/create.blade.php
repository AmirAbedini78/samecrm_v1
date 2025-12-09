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
                <h3 class="page-title">{{ cleanLang(__('lang.add_guarantee_letter')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/guarantee-letters">{{ cleanLang(__('lang.guarantee_letters')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.add_guarantee_letter')) }}</li>
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
                        <form id="guarantee-letter-create-form" method="POST" action="{{ _url('/guarantee-letters') }}">
                            @csrf
                            
                            <!-- Basic Information -->
                            <h5 class="text-primary mb-3">{{ cleanLang(__('lang.basic_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.guarantee_number')) }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="guarantee_number" 
                                               value="{{ old('guarantee_number') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.guarantee_type')) }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="guarantee_type" required>
                                            <option value="">{{ cleanLang(__('lang.select_guarantee_type')) }}</option>
                                            <option value="شرکت در مناقصه" {{ old('guarantee_type') == 'شرکت در مناقصه' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.bid_bond')) }}
                                            </option>
                                            <option value="حسن انجام کار" {{ old('guarantee_type') == 'حسن انجام کار' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.performance_bond')) }}
                                            </option>
                                            <option value="پیش پرداخت" {{ old('guarantee_type') == 'پیش پرداخت' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.advance_payment_bond')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.industrial_type')) }}</label>
                                        <select class="form-control" name="industrial_type">
                                            <option value="">{{ cleanLang(__('lang.select_industrial_type')) }}</option>
                                            <option value="بلبرینگ" {{ old('industrial_type') == 'بلبرینگ' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.bearing')) }}
                                            </option>
                                            <option value="بلزونا" {{ old('industrial_type') == 'بلزونا' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.belzona')) }}
                                            </option>
                                            <option value="پایپ" {{ old('industrial_type') == 'پایپ' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.pipe')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank and Beneficiary Information -->
                            <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.bank_beneficiary_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.issuing_bank')) }}</label>
                                        <input type="text" class="form-control" name="issuing_bank" 
                                               value="{{ old('issuing_bank') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.beneficiary')) }}</label>
                                        <input type="text" class="form-control" name="beneficiary" 
                                               value="{{ old('beneficiary') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Amount Information -->
                            <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.amount_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.amount')) }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="amount" 
                                               value="{{ old('amount', 0) }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.currency')) }}</label>
                                        <select class="form-control" name="currency">
                                            <option value="IRR" {{ old('currency', 'IRR') == 'IRR' ? 'selected' : '' }}>IRR</option>
                                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Date Information -->
                            <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.date_information')) }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.issue_date')) }}</label>
                                        <input type="date" class="form-control" name="issue_date" 
                                               value="{{ old('issue_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.expiry_date')) }}</label>
                                        <input type="date" class="form-control" name="expiry_date" 
                                               value="{{ old('expiry_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.claim_date')) }}</label>
                                        <input type="date" class="form-control" name="claim_date" 
                                               value="{{ old('claim_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.return_date')) }}</label>
                                        <input type="date" class="form-control" name="return_date" 
                                               value="{{ old('return_date') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.status')) }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.guarantee_status')) }}</label>
                                        <select class="form-control" name="guarantee_status">
                                            <option value="active" {{ old('guarantee_status', 'active') == 'active' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.active')) }}
                                            </option>
                                            <option value="expired" {{ old('guarantee_status') == 'expired' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.expired')) }}
                                            </option>
                                            <option value="claimed" {{ old('guarantee_status') == 'claimed' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.claimed')) }}
                                            </option>
                                            <option value="returned" {{ old('guarantee_status') == 'returned' ? 'selected' : '' }}>
                                                {{ cleanLang(__('lang.returned')) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.description')) }}</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.description')) }}</label>
                                        <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Assigned Users -->
                            @if(config('visibility.guarantee_letters_assign_users'))
                            <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.assigned_users')) }}</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.select_users')) }}</label>
                                        <select class="form-control select2-multiple" name="assigned_user_ids[]" multiple>
                                            @if(@count($users ?? []))
                                                @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ in_array($user->id, old('assigned_user_ids', [])) ? 'selected' : '' }}>
                                                    {{ $user->first_name }} {{ $user->last_name }}
                                                </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-save"></i> {{ cleanLang(__('lang.save_guarantee_letter')) }}
                                        </button>
                                        <a href="{{ _url('/guarantee-letters') }}" class="btn btn-secondary">
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

