<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
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
			'name' => 'required',
			'category' => 'required',
			'unit' => 'required',
//			'variants' => 'required',
            'variants.*.name'   => 'required',
            'variants.*.options'   => 'required',
        ];
    }
    public function messages()
    {
        return [
            'variants.*.name.required'           => 'Variation name is required',
            'variants.*.options.required'           => 'Variation options are required',
        ];
    }
}
