<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentRequest extends FormRequest
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
        $equipment = $this->route('equipment');

        return [
            // 'name' => 'required|unique:equipment,name,'.$equipment->id,
			'name' => 'required',
			'brand' => 'required',
			'model' => 'required',
			'location' => 'required',
			'serial_num' => 'required',
			'status' => 'required',
			'vessel' => 'required',
        ];
    }
}
