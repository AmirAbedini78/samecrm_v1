<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryExpiryRequest extends FormRequest
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
            'expiry_date' => 'nullable|date',
            'auto_expiry_days' => 'nullable|integer|min:1|max:3650',
            'alert_days_before' => 'required|integer|min:1|max:365',
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
            'expiry_date.date' => 'تاریخ انقضا باید یک تاریخ معتبر باشد',
            'auto_expiry_days.integer' => 'تعداد روز انقضا باید عدد باشد',
            'auto_expiry_days.min' => 'تعداد روز انقضا باید حداقل 1 باشد',
            'auto_expiry_days.max' => 'تعداد روز انقضا نمی‌تواند بیشتر از 3650 باشد',
            'alert_days_before.required' => 'تعداد روز قبل از انقضا برای هشدار الزامی است',
            'alert_days_before.integer' => 'تعداد روز قبل از انقضا باید عدد باشد',
            'alert_days_before.min' => 'تعداد روز قبل از انقضا باید حداقل 1 باشد',
            'alert_days_before.max' => 'تعداد روز قبل از انقضا نمی‌تواند بیشتر از 365 باشد',
        ];
    }
}





