<div class="col-lg-6">
    <div class="text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal"
            data-target="#commonModal" data-url="{{ _url('/sales/create') }}"
            data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.add_sales_record')) }}"
            data-action-url="{{ _url('/sales') }}" data-action-method="POST"
            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
            data-save-button-text="{{ cleanLang(__('lang.save')) }}">
            <i class="ti-plus"></i> {{ cleanLang(__('lang.add_record')) }}
        </button>
    </div>
</div>
