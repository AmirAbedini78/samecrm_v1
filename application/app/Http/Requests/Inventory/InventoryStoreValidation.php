<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryStoreValidation extends FormRequest {

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
            'inventory_name' => 'required|string|max:255',
            'inventory_sku' => 'required|string|max:255|unique:inventory,inventory_sku',
            'inventory_description' => 'nullable|string',
            'inventory_barcode' => 'nullable|string|max:255',
            'inventory_quantity' => 'required|numeric|min:0',
            'inventory_minimum_quantity' => 'nullable|numeric|min:0',
            'inventory_maximum_quantity' => 'nullable|numeric|min:0',
            'inventory_cost_price' => 'required|numeric|min:0',
            'inventory_selling_price' => 'required|numeric|min:0',
            'inventory_currency' => 'required|string|max:3',
            'inventory_unit' => 'required|string|max:50',
            'inventory_status' => 'required|string|in:active,inactive,discontinued',
            'inventory_categoryid' => 'nullable|exists:categories,category_id',
            'inventory_supplier' => 'nullable|string|max:255',
            'inventory_notes' => 'nullable|string',
            'inventory_location' => 'nullable|string|max:255',
            'inventory_last_restocked' => 'nullable|date',
            'inventory_expiry_date' => 'nullable|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'inventory_name.required' => __('lang.inventory_name_required'),
            'inventory_sku.required' => __('lang.inventory_sku_required'),
            'inventory_sku.unique' => __('lang.inventory_sku_unique'),
            'inventory_quantity.required' => __('lang.inventory_quantity_required'),
            'inventory_cost_price.required' => __('lang.inventory_cost_price_required'),
            'inventory_selling_price.required' => __('lang.inventory_selling_price_required'),
            'inventory_currency.required' => __('lang.inventory_currency_required'),
            'inventory_unit.required' => __('lang.inventory_unit_required'),
            'inventory_status.required' => __('lang.inventory_status_required'),
            'inventory_status.in' => __('lang.inventory_status_invalid'),
        ];
    }

}

