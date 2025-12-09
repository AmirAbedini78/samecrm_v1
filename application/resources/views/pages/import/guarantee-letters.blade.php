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
                <h3 class="page-title">{{ cleanLang(__('lang.import_guarantee_letters')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/guarantee-letters">{{ cleanLang(__('lang.guarantee_letters')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.import_guarantee_letters')) }}</li>
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
                        <h5 class="card-title">{{ cleanLang(__('lang.import_guarantee_letters_data')) }}</h5>
                        <p class="text-muted">{{ cleanLang(__('lang.import_guarantee_letters_description')) }}</p>
                        
                        <!-- Import Results -->
                        @if(session('import_results'))
                            @php $results = session('import_results'); @endphp
                            <div class="alert alert-{{ $results['success'] ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">{{ cleanLang(__('lang.import_results')) }}</h6>
                                <p>{{ $results['message'] }}</p>
                                <hr>
                                <p class="mb-0">
                                    <strong>{{ cleanLang(__('lang.imported')) }}:</strong> {{ $results['imported'] }} | 
                                    <strong>{{ cleanLang(__('lang.skipped')) }}:</strong> {{ $results['skipped'] }}
                                </p>
                                
                                @if(isset($results['skipped_details']) && count($results['skipped_details']) > 0)
                                    <hr>
                                    <h6 class="text-warning">{{ cleanLang(__('lang.skipped_details')) }}</h6>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.row_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.reason')) }}</th>
                                                    <th>{{ cleanLang(__('lang.guarantee_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.guarantee_type')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($results['skipped_details'] as $detail)
                                                <tr>
                                                    <td>{{ $detail['row_number'] }}</td>
                                                    <td><span class="badge bg-warning">{{ $detail['reason'] }}</span></td>
                                                    <td>{{ $detail['guarantee_number'] ?? '-' }}</td>
                                                    <td>{{ $detail['guarantee_type'] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Import Form -->
                        <form id="guarantee-letters-import-form" method="POST" action="{{ _url('/import/guarantee-letters') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.select_file')) }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="attachments[]" 
                                               accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            {{ cleanLang(__('lang.supported_formats')) }}: XLSX, XLS, CSV ({{ cleanLang(__('lang.max_file_size')) }}: 10MB)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Format -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>{{ cleanLang(__('lang.sample_format')) }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.guarantee_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.guarantee_type')) }}</th>
                                                    <th>{{ cleanLang(__('lang.industrial_type')) }}</th>
                                                    <th>{{ cleanLang(__('lang.issuing_bank')) }}</th>
                                                    <th>{{ cleanLang(__('lang.beneficiary')) }}</th>
                                                    <th>{{ cleanLang(__('lang.amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.currency')) }}</th>
                                                    <th>{{ cleanLang(__('lang.issue_date')) }}</th>
                                                    <th>{{ cleanLang(__('lang.expiry_date')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>GL-001</td>
                                                    <td>شرکت در مناقصه</td>
                                                    <td>بلبرینگ</td>
                                                    <td>بانک ملی</td>
                                                    <td>شرکت الف</td>
                                                    <td>1000000</td>
                                                    <td>IRR</td>
                                                    <td>1403/01/01</td>
                                                    <td>1403/12/29</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-muted mt-2">
                                        <strong>{{ cleanLang(__('lang.note')) }}:</strong> {{ cleanLang(__('lang.excel_file_must_contain_sheets')) }}: 
                                        "شرکت در مناقصه", "حسن انجام کار", "پیش پرداخت"
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-upload"></i> {{ cleanLang(__('lang.import_guarantee_letters')) }}
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

