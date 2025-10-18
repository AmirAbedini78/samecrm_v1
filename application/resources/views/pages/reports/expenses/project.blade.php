@extends('layout.wrapper')

@section('content')
<div class="content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">{{ cleanLang(__('lang.expenses_by_project')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/reports">{{ cleanLang(__('lang.reports')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/reports/expenses">{{ cleanLang(__('lang.expenses')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.by_project')) }}</li>
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
                        <h5 class="card-title">{{ cleanLang(__('lang.expenses_by_project_report')) }}</h5>
                        <p class="text-muted">{{ cleanLang(__('lang.expenses_by_project_description')) }}</p>
                        
                        <!-- Report Content -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="ti-info-circle"></i> {{ cleanLang(__('lang.report_coming_soon')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content -->
</div>
@endsection
