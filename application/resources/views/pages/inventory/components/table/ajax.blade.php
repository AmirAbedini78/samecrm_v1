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
