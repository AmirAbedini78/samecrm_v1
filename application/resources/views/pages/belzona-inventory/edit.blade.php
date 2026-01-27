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
                        <li class="breadcrumb-item active">ویرایش</li>
                    </ol>
                </div>
                <h4 class="page-title">ویرایش ردیف انبار بلزونا</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ _url('/belzona-inventory/'.$belzonaInventory->belzona_inventory_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نام محصول</label>
                                <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $belzonaInventory->product_name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاریخ</label>
                                <input type="datetime-local" name="date" class="form-control" value="{{ old('date', $belzonaInventory->date ? \Carbon\Carbon::parse($belzonaInventory->date)->format('Y-m-d\\TH:i') : '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ورودی</label>
                                <input type="text" name="input" class="form-control" value="{{ old('input', $belzonaInventory->input) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">خروجی</label>
                                <input type="text" name="output" class="form-control" value="{{ old('output', $belzonaInventory->output) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">مانده</label>
                                <input type="text" name="balance" class="form-control" value="{{ old('balance', $belzonaInventory->balance) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">شماره فاکتور</label>
                                <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $belzonaInventory->invoice_number) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نام مشتری</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $belzonaInventory->customer_name) }}">
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-save"></i> ذخیره
                            </button>
                            <a href="{{ _url('/belzona-inventory/'.$belzonaInventory->belzona_inventory_id) }}" class="btn btn-secondary">
                                بازگشت
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

