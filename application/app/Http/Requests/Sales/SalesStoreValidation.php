<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class SalesStoreValidation extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'sales_title' => 'required|string|max:255',
            'sales_description' => 'nullable|string',
            'sales_type' => 'required|string|in:sale,return,refund',
            'sales_quantity' => 'required|numeric|min:0.01',
            'sales_unit_price' => 'required|numeric|min:0',
            'sales_total_amount' => 'nullable|numeric|min:0',
            'sales_discount_amount' => 'nullable|numeric|min:0',
            'sales_tax_amount' => 'nullable|numeric|min:0',
            'sales_final_amount' => 'required|numeric|min:0',
            'sales_currency' => 'required|string|max:3',
            'sales_status' => 'required|string|in:pending,completed,cancelled,refunded',
            'sales_payment_status' => 'required|string|in:unpaid,paid,partially_paid,overdue',
            'sales_payment_method' => 'nullable|string|max:50',
            'sales_date' => 'required|date',
            'sales_due_date' => 'nullable|date',
            'sales_clientid' => 'nullable|exists:clients,client_id',
            'sales_projectid' => 'nullable|exists:projects,project_id',
            'sales_categoryid' => 'nullable|exists:categories,category_id',
            'sales_reference' => 'nullable|string|max:255',
            'sales_notes' => 'nullable|string',
            'sales_salesperson' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'sales_title.required' => __('lang.sales_title_required'),
            'sales_type.required' => __('lang.sales_type_required'),
            'sales_type.in' => __('lang.sales_type_invalid'),
            'sales_quantity.required' => __('lang.sales_quantity_required'),
            'sales_unit_price.required' => __('lang.sales_unit_price_required'),
            'sales_final_amount.required' => __('lang.sales_final_amount_required'),
            'sales_currency.required' => __('lang.sales_currency_required'),
            'sales_status.required' => __('lang.sales_status_required'),
            'sales_status.in' => __('lang.sales_status_invalid'),
            'sales_payment_status.required' => __('lang.sales_payment_status_required'),
            'sales_payment_status.in' => __('lang.sales_payment_status_invalid'),
            'sales_date.required' => __('lang.sales_date_required'),
        ];
    }

}

