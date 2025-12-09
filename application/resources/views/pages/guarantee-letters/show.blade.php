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
                <h3 class="page-title">{{ cleanLang(__('lang.view_guarantee_letter')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/guarantee-letters">{{ cleanLang(__('lang.guarantee_letters')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.view_guarantee_letter')) }}</li>
                </ul>
            </div>
            <div class="col-md-8 col-sm-12 text-end">
                @if(config('visibility.guarantee_letters_action_edit'))
                <a href="{{ _url('/guarantee-letters/' . $guarantee->guarantee_id . '/edit') }}" class="btn btn-primary">
                    <i class="ti-pencil"></i> {{ cleanLang(__('lang.edit')) }}
                </a>
                @endif
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
                        <!-- Basic Information -->
                        <h5 class="text-primary mb-3">{{ cleanLang(__('lang.basic_information')) }}</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.guarantee_number')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->guarantee_number ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.guarantee_type')) }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-info">{{ $guarantee->guarantee_type ?? '---' }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.industrial_type')) }}</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-secondary">{{ $guarantee->industrial_type ?? '---' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Bank and Beneficiary Information -->
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.bank_beneficiary_information')) }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.issuing_bank')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->issuing_bank ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.beneficiary')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->beneficiary ?? '---' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Information -->
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.amount_information')) }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.amount')) }}</label>
                                    <p class="form-control-plaintext">{{ formatCurrency($guarantee->amount ?? 0, $guarantee->currency ?? 'IRR') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.currency')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->currency ?? 'IRR' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Date Information -->
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.date_information')) }}</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.issue_date')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->issue_date ? runtimeDate($guarantee->issue_date) : '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.expiry_date')) }}</label>
                                    <p class="form-control-plaintext">
                                        @if($guarantee->expiry_date)
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($guarantee->expiry_date);
                                                $daysUntil = now()->diffInDays($expiryDate, false);
                                            @endphp
                                            <span class="{{ $daysUntil < 30 ? 'text-danger' : ($daysUntil < 90 ? 'text-warning' : '') }}">
                                                {{ runtimeDate($guarantee->expiry_date) }}
                                                @if($daysUntil < 0)
                                                    <span class="badge bg-danger">({{ cleanLang(__('lang.expired')) }})</span>
                                                @elseif($daysUntil < 30)
                                                    <span class="badge bg-warning">({{ $daysUntil }} {{ cleanLang(__('lang.days_remaining')) }})</span>
                                                @endif
                                            </span>
                                        @else
                                            ---
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.claim_date')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->claim_date ? runtimeDate($guarantee->claim_date) : '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.return_date')) }}</label>
                                    <p class="form-control-plaintext">{{ $guarantee->return_date ? runtimeDate($guarantee->return_date) : '---' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.status')) }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ cleanLang(__('lang.guarantee_status')) }}</label>
                                    <p class="form-control-plaintext">
                                        @if($guarantee->guarantee_status == 'active')
                                            <span class="badge bg-success">{{ cleanLang(__('lang.active')) }}</span>
                                        @elseif($guarantee->guarantee_status == 'expired')
                                            <span class="badge bg-danger">{{ cleanLang(__('lang.expired')) }}</span>
                                        @elseif($guarantee->guarantee_status == 'claimed')
                                            <span class="badge bg-warning">{{ cleanLang(__('lang.claimed')) }}</span>
                                        @elseif($guarantee->guarantee_status == 'returned')
                                            <span class="badge bg-info">{{ cleanLang(__('lang.returned')) }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $guarantee->guarantee_status ?? '---' }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.description')) }}</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="form-control-plaintext">{{ $guarantee->description ?? '---' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Users -->
                        @if(@count($guarantee->assignedUser ?? []))
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.assigned_users')) }}</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="form-control-plaintext">
                                        @foreach($guarantee->assignedUser as $user)
                                            <span class="badge bg-primary me-1">{{ $user->first_name }} {{ $user->last_name }}</span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Notifications -->
                        @if(@count($guarantee->notifications ?? []))
                        <h5 class="text-primary mb-3 mt-4">{{ cleanLang(__('lang.notifications')) }}</h5>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ cleanLang(__('lang.target_date')) }}</th>
                                                <th>{{ cleanLang(__('lang.alert_days_before')) }}</th>
                                                <th>{{ cleanLang(__('lang.notification_type')) }}</th>
                                                <th>{{ cleanLang(__('lang.status')) }}</th>
                                                <th>{{ cleanLang(__('lang.last_sent')) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($guarantee->notifications as $notification)
                                            <tr>
                                                <td>{{ $notification->target_date_column }}</td>
                                                <td>{{ $notification->alert_days_before }} {{ cleanLang(__('lang.days')) }}</td>
                                                <td>
                                                    @if($notification->notification_type == 'email')
                                                        <span class="badge bg-info">{{ cleanLang(__('lang.email')) }}</span>
                                                    @elseif($notification->notification_type == 'sms')
                                                        <span class="badge bg-success">{{ cleanLang(__('lang.sms')) }}</span>
                                                    @else
                                                        <span class="badge bg-primary">{{ cleanLang(__('lang.both')) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($notification->is_active)
                                                        <span class="badge bg-success">{{ cleanLang(__('lang.active')) }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ cleanLang(__('lang.inactive')) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $notification->last_sent_at ? runtimeDate($notification->last_sent_at) : '---' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <a href="{{ _url('/guarantee-letters') }}" class="btn btn-secondary">
                                    <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back_to_list')) }}
                                </a>
                            </div>
                        </div>
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

