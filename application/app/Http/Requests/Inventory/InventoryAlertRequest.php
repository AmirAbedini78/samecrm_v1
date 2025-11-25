<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAlertRequest extends FormRequest
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
        $rules = [
            'inventory_id' => 'nullable|exists:inventory,inventory_id',
            'alert_type' => 'required|in:expiry,quantity,minimum,maximum',
            'alert_email' => 'boolean',
            'alert_sms' => 'boolean',
            'is_active' => 'boolean',
        ];

        // Conditional validation based on alert_type
        if ($this->alert_type === 'expiry') {
            $rules['threshold_days'] = 'required|integer|min:1|max:365';
        } else {
            $rules['threshold_value'] = 'required|numeric|min:0';
        }

        // Email addresses validation if email alert is enabled
        if ($this->alert_email) {
            $rules['alert_email_addresses'] = 'required|string';
        }

        // Phone numbers validation if SMS alert is enabled
        if ($this->alert_sms) {
            $rules['alert_phone_numbers'] = 'required|string';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'inventory_id.exists' => 'کالای انتخاب شده معتبر نیست',
            'alert_type.required' => 'نوع هشدار الزامی است',
            'alert_type.in' => 'نوع هشدار معتبر نیست',
            'threshold_value.required' => 'مقدار آستانه الزامی است',
            'threshold_value.numeric' => 'مقدار آستانه باید عدد باشد',
            'threshold_value.min' => 'مقدار آستانه نمی‌تواند منفی باشد',
            'threshold_days.required' => 'تعداد روز آستانه الزامی است',
            'threshold_days.integer' => 'تعداد روز آستانه باید عدد باشد',
            'threshold_days.min' => 'تعداد روز آستانه باید حداقل 1 باشد',
            'threshold_days.max' => 'تعداد روز آستانه نمی‌تواند بیشتر از 365 باشد',
            'alert_email_addresses.required' => 'آدرس ایمیل الزامی است',
            'alert_phone_numbers.required' => 'شماره تلفن الزامی است',
        ];
    }
}

