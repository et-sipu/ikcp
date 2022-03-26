<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRegistrationsRequest extends FormRequest
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
            // 'name' => 'required|unique:company_registrations',
			'registration' => 'required',
			'registration_no' => 'required',
			'company' => 'required',
			'type' => 'required',
			'grade' => 'required',
			'validity_from' => 'required',
			'validity_to' => 'required',
        ];
    }
}
