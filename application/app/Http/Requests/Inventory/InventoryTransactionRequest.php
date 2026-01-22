<?php

namespace App\Http\Requests\Inventory;

use App\Enums\TransactionType;
use App\Exceptions\Inventory\InsufficientStockException;
use App\Models\Inventory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $rules = [
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'transaction_type' => ['required', Rule::enum(TransactionType::class)],
            'quantity' => 'required|numeric|min:0.01',
            'sub_quantity' => 'nullable|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'transaction_date' => 'required|date|before_or_equal:today',
            'document_number' => 'nullable|string|max:255',
            'base_document_number' => 'nullable|string|max:255',
            'warehouse' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check stock availability for output transactions
            if ($this->transaction_type === TransactionType::OUTPUT->value) {
                $inventory = Inventory::find($this->inventory_id);
                
                if ($inventory) {
                    $availableQuantity = $inventory->current_quantity ?? 0;
                    $requestedQuantity = (float) $this->quantity;
                    
                    if ($availableQuantity < $requestedQuantity) {
                        $validator->errors()->add(
                            'quantity',
                            "موجودی کافی نیست. موجودی فعلی: {$availableQuantity}، درخواستی: {$requestedQuantity}"
                        );
                    }
                }
            }
        });
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
            'transaction_type.enum' => 'نوع تراکنش باید ورود یا خروج باشد',
            'quantity.required' => 'مقدار الزامی است',
            'quantity.numeric' => 'مقدار باید عدد باشد',
            'quantity.min' => 'مقدار باید بیشتر از صفر باشد',
            'transaction_date.required' => 'تاریخ تراکنش الزامی است',
            'transaction_date.date' => 'تاریخ تراکنش باید یک تاریخ معتبر باشد',
            'transaction_date.before_or_equal' => 'تاریخ تراکنش نمی‌تواند از امروز بیشتر باشد',
        ];
    }
}





