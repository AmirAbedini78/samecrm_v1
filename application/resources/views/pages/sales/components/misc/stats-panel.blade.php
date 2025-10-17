@if(isset($stats))
<div class="row">
    <!-- Total Sales -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-primary bg-soft">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="ti-shopping-cart font-20"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_sales')) }}</p>
                        <h4 class="my-1">{{ $stats['total_sales'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Sales -->
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
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.completed_sales')) }}</p>
                        <h4 class="my-1">{{ $stats['completed_sales'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Sales -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm rounded-circle bg-warning bg-soft">
                            <span class="avatar-title rounded-circle bg-warning">
                                <i class="ti-time font-20"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.pending_sales')) }}</p>
                        <h4 class="my-1">{{ $stats['pending_sales'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
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
                        <p class="text-uppercase fw-medium text-muted mb-0">{{ cleanLang(__('lang.total_revenue')) }}</p>
                        <h4 class="my-1">{{ formatCurrency($stats['total_revenue'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
