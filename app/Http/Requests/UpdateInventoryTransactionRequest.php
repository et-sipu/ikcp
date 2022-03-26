<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryTransactionRequest extends FormRequest
{
    /**
     * Determine if the meta is authorized to make this request.
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
            'transaction_type' => 'required',
            'inventory_item' => 'required',
//            'variations' => 'required',
            'variations.*' => 'required',
            'quantity' => 'required',
            'inventory' => 'required',
            'description' => 'required',
            'transaction_date' => 'nullable|date_format:Y-m-d',
//            'note' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'variations.*.required' => 'This field is required'
        ];
    }
}
