@if(isset($stats))
<div class="row">
    <!-- Total Items -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-primary bg-soft">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="ti-package font-20"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_items')) }}</p>
                        <h4 class="my-1">{{ $stats['total_items'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Items -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-success bg-soft">
                            <span class="avatar-title rounded-circle bg-success">
                                <i class="ti-check-box font-20"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.active_items')) }}</p>
                        <h4 class="my-1">{{ $stats['active_items'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-warning bg-soft">
                            <span class="avatar-title rounded-circle bg-warning">
                                <i class="ti-alert font-20"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.low_stock')) }}</p>
                        <h4 class="my-1">{{ $stats['low_stock'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Value -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-info bg-soft">
                            <span class="avatar-title rounded-circle bg-info">
                                <i class="ti-money font-20"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_value')) }}</p>
                        <h4 class="my-1">{{ formatCurrency($stats['total_value'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
