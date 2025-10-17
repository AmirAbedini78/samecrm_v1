<!--table-->
<div class="table-responsive">
    <table id="sales-table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="w-5">
                    <input type="checkbox" id="checkbox-all" class="filled-in chk-col-light-blue"
                        {{ $page['select_all'] ?? '' }}>
                    <label for="checkbox-all" class="p-0">&nbsp;</label>
                </th>
                <th class="w-10">{{ cleanLang(__('lang.id')) }}</th>
                <th class="w-20">{{ cleanLang(__('lang.sales_title')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.sales_code')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.sales_quantity')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.sales_total_amount')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.sales_status')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.actions')) }}</th>
            </tr>
        </thead>
        <tbody id="sales-td-container">
            @foreach($sales as $item)
            <tr id="sales_{{ $item->sales_id }}">
                <td>
                    <input type="checkbox" id="checkbox-{{ $item->sales_id }}" 
                           class="filled-in chk-col-light-blue checkbox" 
                           data-id="{{ $item->sales_id }}">
                    <label for="checkbox-{{ $item->sales_id }}" class="p-0">&nbsp;</label>
                </td>
                <td>{{ $item->formatted_id }}</td>
                <td>{{ $item->sales_title }}</td>
                <td>{{ $item->sales_code }}</td>
                <td>{{ $item->sales_quantity }}</td>
                <td>{{ formatCurrency($item->sales_total_amount, $item->sales_currency) }}</td>
                <td>
                    <span class="badge badge-{{ $item->sales_status == 'completed' ? 'success' : 'warning' }}">
                        {{ $item->sales_status }}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ cleanLang(__('lang.actions')) }}
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ _url('/sales/'.$item->sales_id.'/edit') }}">
                                {{ cleanLang(__('lang.edit')) }}
                            </a>
                            <a class="dropdown-item" href="{{ _url('/sales/'.$item->sales_id) }}">
                                {{ cleanLang(__('lang.view')) }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void(0)" 
                               onclick="deleteSales({{ $item->sales_id }})">
                                {{ cleanLang(__('lang.delete')) }}
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
