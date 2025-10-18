<!--table-->
<div id="inventory-table-wrapper">
<div class="table-responsive">
    <table id="inventory-table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="w-5">
                    <input type="checkbox" id="checkbox-all" class="filled-in chk-col-light-blue"
                        {{ $page['select_all'] ?? '' }}>
                    <label for="checkbox-all" class="p-0">&nbsp;</label>
                </th>
                <th class="w-5">{{ cleanLang(__('lang.id')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.inventory_name')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.inventory_code')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.current_quantity')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.current_avg_price')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.current_amount')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.minimum_stock')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.status')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.actions')) }}</th>
            </tr>
        </thead>
        <tbody id="inventory-td-container">
            @if($inventory && $inventory->count() > 0)
                @foreach($inventory as $item)
            <tr id="inventory_{{ $item->inventory_id }}">
                <td>
                    <input type="checkbox" id="checkbox-{{ $item->inventory_id }}" 
                           class="filled-in chk-col-light-blue checkbox" 
                           data-id="{{ $item->inventory_id }}">
                    <label for="checkbox-{{ $item->inventory_id }}" class="p-0">&nbsp;</label>
                </td>
                <td>{{ $item->formatted_id }}</td>
                <td>{{ $item->inventory_name }}</td>
                <td>{{ $item->inventory_code }}</td>
                <td>{{ number_format($item->current_quantity, 2) }}</td>
                <td>{{ formatCurrency($item->current_avg_price, 'IRR') }}</td>
                <td>{{ formatCurrency($item->current_amount, 'IRR') }}</td>
                <td>{{ number_format($item->minimum_stock, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $item->inventory_status == 'active' ? 'success' : 'secondary' }}">
                        {{ $item->inventory_status }}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ cleanLang(__('lang.actions')) }}
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ _url('/inventory/'.$item->inventory_id.'/edit') }}">
                                {{ cleanLang(__('lang.edit')) }}
                            </a>
                            <a class="dropdown-item" href="{{ _url('/inventory/'.$item->inventory_id) }}">
                                {{ cleanLang(__('lang.view')) }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void(0)" 
                               onclick="deleteInventory({{ $item->inventory_id }})">
                                {{ cleanLang(__('lang.delete')) }}
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">
                        <div class="text-muted py-4">
                            <i class="ti-package" style="font-size: 48px;"></i>
                            <br>
                            {{ cleanLang(__('lang.no_inventory_items_found')) }}
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
</div>

<!-- Pagination -->
@if($inventory && $inventory->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                {{ cleanLang(__('lang.showing')) }} {{ $inventory->firstItem() }} {{ cleanLang(__('lang.to')) }} {{ $inventory->lastItem() }} 
                {{ cleanLang(__('lang.of')) }} {{ $inventory->total() }} {{ cleanLang(__('lang.results')) }}
            </div>
            <div>
                {{ $inventory->links() }}
            </div>
        </div>
    </div>
</div>
@endif
