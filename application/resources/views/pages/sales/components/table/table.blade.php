<!--table-->
<div id="sales-table-wrapper">
<div class="table-responsive">
    <table id="sales-table" class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="w-5">
                    <input type="checkbox" id="checkbox-all" class="filled-in chk-col-light-blue"
                        {{ $page['select_all'] ?? '' }}>
                    <label for="checkbox-all" class="p-0">&nbsp;</label>
                </th>
                <th class="w-5">{{ cleanLang(__('lang.id')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.document_number')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.customer_name')) }}</th>
                <th class="w-15">{{ cleanLang(__('lang.product_name')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.main_quantity')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.base_price')) }}</th>
                <th class="w-10">{{ cleanLang(__('lang.base_net_amount')) }}</th>
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
                <td>{{ $item->document_number }}</td>
                <td>{{ $item->customer_name }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ number_format($item->main_quantity, 2) }}</td>
                <td>{{ formatCurrency($item->base_price, $item->currency) }}</td>
                <td>{{ formatCurrency($item->base_net_amount, $item->currency) }}</td>
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
</div>

<!-- Pagination -->
@if($sales && $sales->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                {{ cleanLang(__('lang.showing')) }} {{ $sales->firstItem() }} {{ cleanLang(__('lang.to')) }} {{ $sales->lastItem() }} 
                {{ cleanLang(__('lang.of')) }} {{ $sales->total() }} {{ cleanLang(__('lang.results')) }}
            </div>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endif
