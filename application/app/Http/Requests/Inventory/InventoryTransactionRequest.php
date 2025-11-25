<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'transaction_type' => 'required|in:input,output',
            'quantity' => 'required|numeric|min:0',
            'sub_quantity' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'transaction_date' => 'required|date',
            'document_number' => 'nullable|string|max:255',
            'warehouse' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'inventory_id.required' => 'کد کالا الزامی است',
            'inventory_id.exists' => 'کالای انتخاب شده معتبر نیست',
            'transaction_type.required' => 'نوع تراکنش الزامی است',
            'transaction_type.in' => 'نوع تراکنش باید ورود یا خروج باشد',
            'quantity.required' => 'مقدار الزامی است',
            'quantity.numeric' => 'مقدار باید عدد باشد',
            'quantity.min' => 'مقدار نمی‌تواند منفی باشد',
            'transaction_date.required' => 'تاریخ تراکنش الزامی است',
            'transaction_date.date' => 'تاریخ تراکنش باید یک تاریخ معتبر باشد',
        ];
    }
}

