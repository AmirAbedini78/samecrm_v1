<!--table-->
<div class="table-responsive">
    <table id="inventory-table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="w-5">
                    <input type="checkbox" id="checkbox-all" class="filled-in chk-col-light-blue"
                        {{ $page['select_all'] ?? '' }}>
                    <label for="checkbox-all" class="p-0">&nbsp;</label>
                </th>
                <th class="w-10">{{ cleanLang(__('lang.id')) }}</th>
                <th class="w-20">{{ cleanLang(__('lang.inventory_name')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.inventory_code')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.inventory_quantity')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.inventory_selling_price')) }}</th>
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
                <td>{{ $item->inventory_quantity }}</td>
                <td>{{ formatCurrency($item->inventory_selling_price, $item->inventory_currency) }}</td>
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
                    <td colspan="8" class="text-center">
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
