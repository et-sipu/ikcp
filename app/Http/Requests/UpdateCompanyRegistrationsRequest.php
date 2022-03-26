<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRegistrationsRequest extends FormRequest
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
        $company_registration = $this->route('company_registration');

        return [
            // 'name' => 'required|unique:company_registrations,name,'.$company_registration->id,
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
