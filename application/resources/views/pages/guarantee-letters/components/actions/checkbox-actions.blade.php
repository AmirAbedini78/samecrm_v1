<!--bulk actions container-->
<div class="guarantees-checkbox-actions-container hidden" id="guarantees-checkbox-actions-container">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <span class="selected-count">
                        <strong id="guarantees-selected-count">0</strong> {{ cleanLang(__('lang.selected')) }}
                    </span>
                </div>
                <div class="col-md-6 text-end">
                    @if(config('visibility.guarantee_letters_bulk_delete'))
                    <button type="button" class="btn btn-danger btn-sm confirm-action-danger"
                        data-confirm-type="delete"
                        data-confirm-title="{{ cleanLang(__('lang.delete_selected_guarantee_letters')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
                        data-confirm-button-text="{{ cleanLang(__('lang.yes_delete_it')) }}"
                        data-url="/guarantee-letters/delete"
                        id="guarantees-bulk-delete-button">
                        <i class="ti-trash"></i> {{ cleanLang(__('lang.delete_selected')) }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

