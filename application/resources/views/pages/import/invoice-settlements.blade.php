@extends('layout.wrapper')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">{{ cleanLang(__('lang.import_invoice_settlements')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.invoice_settlements')) }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ cleanLang(__('lang.import_invoice_settlements_data')) }}</h5>
                        <p class="text-muted">{{ cleanLang(__('lang.import_invoice_settlements_description')) }}</p>

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
                                @if(!empty($results['skipped_details']))
                                    <hr>
                                    <h6 class="text-warning">{{ cleanLang(__('lang.skipped_details')) }}</h6>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.row_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.reason')) }}</th>
                                                    <th>{{ cleanLang(__('lang.document_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_name')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($results['skipped_details'] as $detail)
                                                <tr>
                                                    <td>{{ $detail['row_number'] }}</td>
                                                    <td>{{ $detail['reason'] }}</td>
                                                    <td>{{ $detail['document_number'] ?? '-' }}</td>
                                                    <td>{{ $detail['customer_name'] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="invoice-settlements-import-form" method="POST" action="/import/invoice-settlements" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.select_file')) }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="attachments[]" accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            {{ cleanLang(__('lang.supported_formats')) }}: XLSX, XLS, CSV ({{ cleanLang(__('lang.max_file_size')) }}: 10MB)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>{{ cleanLang(__('lang.sample_format')) }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.document_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.document_date')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_name')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_net_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.paid_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.balance_amount')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>7240</td>
                                                    <td>1404/04/17</td>
                                                    <td>Sample Customer</td>
                                                    <td>1358500000</td>
                                                    <td>500000000</td>
                                                    <td>858500000</td>
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
                                        <a href="/invoice-settlements" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back_to_invoice_settlements')) }}
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
</div>
@endsection

@section('footerjs')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('invoice-settlements-import-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ti-spinner"></i> {{ cleanLang(__('lang.uploading')) }}';

        fetch('/import/invoice-settlements', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        }).then(response => response.json())
          .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
          }).catch(() => {
            alert('{{ cleanLang(__('lang.import_failed_generic')) }}');
          }).finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}';
          });
    });
});
</script>
@endsection

