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
                <h3 class="page-title">{{ cleanLang(__('lang.import_inventory')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/inventory">{{ cleanLang(__('lang.inventory')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.import_inventory')) }}</li>
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
                        <h5 class="card-title">{{ cleanLang(__('lang.import_inventory_data')) }}</h5>
                        <p class="text-muted">{{ cleanLang(__('lang.import_inventory_description')) }}</p>
                        
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Import Form -->
                        <form id="inventory-import-form" method="POST" action="/import/inventory" enctype="multipart/form-data">
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
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.inventory_code')) }}</th>
                                                    <th>{{ cleanLang(__('lang.inventory_name')) }}</th>
                                                    <th>{{ cleanLang(__('lang.first_period_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.first_period_sub_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.first_period_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.first_period_avg_price')) }}</th>
                                                    <th>{{ cleanLang(__('lang.input_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.input_sub_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.input_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.input_avg_price')) }}</th>
                                                    <th>{{ cleanLang(__('lang.output_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.output_sub_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.output_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.output_avg_price')) }}</th>
                                                    <th>{{ cleanLang(__('lang.current_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.current_sub_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.current_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.current_avg_price')) }}</th>
                                                    <th>{{ cleanLang(__('lang.weighing_input')) }}</th>
                                                    <th>{{ cleanLang(__('lang.weighing_output')) }}</th>
                                                    <th>{{ cleanLang(__('lang.minimum_stock')) }}</th>
                                                    <th>{{ cleanLang(__('lang.maximum_stock')) }}</th>
                                                    <th>{{ cleanLang(__('lang.discrepancy')) }}</th>
                                                    <th>{{ cleanLang(__('lang.main_unit')) }}</th>
                                                    <th>{{ cleanLang(__('lang.sub_unit')) }}</th>
                                                    <th>{{ cleanLang(__('lang.inventory_status')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>INV001</td>
                                                    <td>Sample Product</td>
                                                    <td>100</td>
                                                    <td>0</td>
                                                    <td>1000000</td>
                                                    <td>10000</td>
                                                    <td>50</td>
                                                    <td>0</td>
                                                    <td>500000</td>
                                                    <td>10000</td>
                                                    <td>30</td>
                                                    <td>0</td>
                                                    <td>300000</td>
                                                    <td>10000</td>
                                                    <td>120</td>
                                                    <td>0</td>
                                                    <td>1200000</td>
                                                    <td>10000</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>10</td>
                                                    <td>200</td>
                                                    <td>0</td>
                                                    <td>pcs</td>
                                                    <td>kg</td>
                                                    <td>active</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}
                                        </button>
                                        <a href="/inventory" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back_to_inventory')) }}
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

@section('footerjs')
<script>
$(document).ready(function() {
    // Handle file upload
    $('#inventory-import-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var fileInput = $('input[type="file"]')[0];
        
        if (fileInput.files.length === 0) {
            alert('Please select a file');
            return;
        }
        
        // Add file to formData
        formData.append('attachments[]', fileInput.files[0]);
        
        // Show loading
        $('button[type="submit"]').prop('disabled', true).html('<i class="ti-spinner"></i> Uploading...');
        
        $.ajax({
            url: '/import/inventory',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Import successful: ' + response.message);
                    location.reload();
                } else {
                    alert('Import failed: ' + response.message);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    alert('Import failed: ' + response.message);
                } else {
                    alert('Import failed: Please try again');
                }
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}');
            }
        });
    });
});
</script>
@endsection
