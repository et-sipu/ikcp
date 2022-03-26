<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
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
        $inventory = $this->route('inventory');

        return [
            // 'name' => 'required|unique:inventories,name,'.$inventory->id,
			'name' => 'required',
			'vessel' => 'required',
        ];
    }
}
