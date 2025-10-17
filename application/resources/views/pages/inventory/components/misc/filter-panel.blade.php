<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-inline" id="searchForm">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search_query" name="search_query"
                                    placeholder="{{ cleanLang(__('lang.search')) }}" value="{{ request('search_query') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="ti-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
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
            </div>
        </div>
    </div>
</div>
