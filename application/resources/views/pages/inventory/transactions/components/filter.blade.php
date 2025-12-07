<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="transaction-filter-form" method="GET" action="{{ route('inventory.transactions.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>کالا</label>
                                <select class="form-control" name="filter_inventory_id">
                                    <option value="">همه</option>
                                    @foreach($inventories as $inventory)
                                        <option value="{{ $inventory->inventory_id }}" 
                                            {{ request('filter_inventory_id') == $inventory->inventory_id ? 'selected' : '' }}>
                                            {{ $inventory->inventory_code }} - {{ $inventory->inventory_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>نوع</label>
                                <select class="form-control" name="filter_transaction_type">
                                    <option value="">همه</option>
                                    <option value="input" {{ request('filter_transaction_type') == 'input' ? 'selected' : '' }}>ورود</option>
                                    <option value="output" {{ request('filter_transaction_type') == 'output' ? 'selected' : '' }}>خروج</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>از تاریخ</label>
                                <input type="date" class="form-control" name="filter_from_date" 
                                       value="{{ request('filter_from_date') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>تا تاریخ</label>
                                <input type="date" class="form-control" name="filter_to_date" 
                                       value="{{ request('filter_to_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>جستجو</label>
                                <input type="text" class="form-control" name="search_query" 
                                       value="{{ request('search_query') }}" placeholder="شماره سند، کد کالا...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-search"></i> جستجو
                            </button>
                            <a href="{{ route('inventory.transactions.index') }}" class="btn btn-secondary">
                                <i class="ti-reload"></i> پاک کردن فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

