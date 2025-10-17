<div class="col-lg-6">
    <div class="text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal"
            data-target="#commonModal" data-url="{{ _url('/inventory/create') }}"
            data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.add_inventory_item')) }}"
            data-action-url="{{ _url('/inventory') }}" data-action-method="POST"
            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
            data-save-button-text="{{ cleanLang(__('lang.save')) }}">
            <i class="ti-plus"></i> {{ cleanLang(__('lang.add_item')) }}
        </button>
    </div>
</div>
