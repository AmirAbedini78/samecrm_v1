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
                <h3 class="page-title">{{ cleanLang(__('lang.import_sales')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/sales">{{ cleanLang(__('lang.sales')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.import_sales')) }}</li>
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
                        <h5 class="card-title">{{ cleanLang(__('lang.import_sales_data')) }}</h5>
                        <p class="text-muted">{{ cleanLang(__('lang.import_sales_description')) }}</p>
                        
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
                        <form id="sales-import-form" method="POST" action="/import/sales" enctype="multipart/form-data">
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
                                                    <th>{{ cleanLang(__('lang.document_type')) }}</th>
                                                    <th>{{ cleanLang(__('lang.document_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.document_date')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_code')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_name')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_full_name')) }}</th>
                                                    <th>{{ cleanLang(__('lang.sales_type')) }}</th>
                                                    <th>{{ cleanLang(__('lang.product_code')) }}</th>
                                                    <th>{{ cleanLang(__('lang.product_name')) }}</th>
                                                    <th>{{ cleanLang(__('lang.product_barcode')) }}</th>
                                                    <th>{{ cleanLang(__('lang.tracking_code')) }}</th>
                                                    <th>{{ cleanLang(__('lang.main_unit')) }}</th>
                                                    <th>{{ cleanLang(__('lang.main_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.warehouse')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_price')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_sales_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_tax_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_duty_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_additional_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_increasing_factors')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_net_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.month')) }}</th>
                                                    <th>{{ cleanLang(__('lang.description')) }}</th>
                                                    <th>{{ cleanLang(__('lang.issued_main_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.issued_sub_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.remaining_main_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.remaining_sub_quantity')) }}</th>
                                                    <th>{{ cleanLang(__('lang.currency')) }}</th>
                                                    <th>{{ cleanLang(__('lang.sales_status')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>sale</td>
                                                    <td>SAL001</td>
                                                    <td>2024-01-01</td>
                                                    <td>CUST001</td>
                                                    <td>Customer Name</td>
                                                    <td>Full Customer Name</td>
                                                    <td>sale</td>
                                                    <td>PROD001</td>
                                                    <td>Product Name</td>
                                                    <td>123456789</td>
                                                    <td>TRK001</td>
                                                    <td>pcs</td>
                                                    <td>10</td>
                                                    <td>Warehouse A</td>
                                                    <td>10000</td>
                                                    <td>100000</td>
                                                    <td>9000</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>109000</td>
                                                    <td>1403/01</td>
                                                    <td>Sample Description</td>
                                                    <td>5</td>
                                                    <td>0</td>
                                                    <td>5</td>
                                                    <td>0</td>
                                                    <td>IRR</td>
                                                    <td>pending</td>
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
                                        <a href="/sales" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back_to_sales')) }}
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
    $('#sales-import-form').on('submit', function(e) {
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
            url: '/import/sales',
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
