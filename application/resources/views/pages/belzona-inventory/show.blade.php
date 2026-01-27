@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ _url('/') }}">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ _url('/accounting') }}">{{ cleanLang(__('lang.accounting')) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ _url('/belzona-inventory') }}">انبار بلزونا</a></li>
                        <li class="breadcrumb-item active">جزئیات</li>
                    </ol>
                </div>
                <h4 class="page-title">جزئیات ردیف انبار بلزونا</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>نام محصول:</strong> {{ $belzonaInventory->product_name }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>تاریخ:</strong> {{ $belzonaInventory->date }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>ورودی:</strong> {{ $belzonaInventory->input }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>خروجی:</strong> {{ $belzonaInventory->output }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>مانده:</strong> {{ $belzonaInventory->balance }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>شماره فاکتور:</strong> {{ $belzonaInventory->invoice_number }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>نام مشتری:</strong> {{ $belzonaInventory->customer_name }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ _url('/belzona-inventory/'.$belzonaInventory->belzona_inventory_id.'/edit') }}" class="btn btn-warning">
                            <i class="ti-pencil"></i> ویرایش
                        </a>
                        <form method="POST" action="{{ _url('/belzona-inventory/'.$belzonaInventory->belzona_inventory_id) }}" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('حذف شود؟')">
                                <i class="ti-trash"></i> حذف
                            </button>
                        </form>
                        <a href="{{ _url('/belzona-inventory') }}" class="btn btn-secondary">
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

