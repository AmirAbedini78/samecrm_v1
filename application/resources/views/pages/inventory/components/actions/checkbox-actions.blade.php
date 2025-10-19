<!--bulk actions container-->
<div class="inventory-checkbox-actions-container" id="inventory-checkbox-actions-container" style="display: none;">
    <div class="list-table-actions-wrapper">
        <div class="list-table-actions">
            <div class="list-table-actions-inner">
                <div class="list-table-actions-left">
                    <span class="list-table-actions-count">
                        <span class="list-table-actions-count-number">0</span>
                        <span class="list-table-actions-count-text">{{ cleanLang(__('lang.selected')) }}</span>
                    </span>
                </div>
                <div class="list-table-actions-right">
                    <!--delete-->
                    @if(config('visibility.action_buttons_delete'))
                    <button type="button" class="btn btn-outline-danger btn-sm confirm-action-danger"
                        data-confirm-title="{{ cleanLang(__('lang.delete_inventory_items')) }}"
                        data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                        data-url="{{ url('/inventory/delete') }}">
                        <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
                    </button>
                    @endif
                    <!--change category-->
                    <button type="button" class="btn btn-outline-info btn-sm actions-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#actionsModal"
                        data-modal-title="{{ cleanLang(__('lang.change_category')) }}"
                        data-url="{{ url('/inventory/change-category') }}"
                        data-loading-target="actionsModalBody">
                        <i class="sl-icon-folder"></i> {{ cleanLang(__('lang.change_category')) }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--bulk actions container-->
