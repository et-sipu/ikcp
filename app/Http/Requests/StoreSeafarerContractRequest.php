<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeafarerContractRequest extends FormRequest
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
            'vessel'                 => 'required|array',
            'seafarer'               => 'required|array',
            'duration_of_service'    => 'required|integer|min:1|max:36',
            'basic_salary'           => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'scheduled_sign_on_date' => 'required|date_format:Y-m-d',
            'sign_on_port'           => 'required|array',
            'pay_leave'              => 'required|integer|min:0',
            'insurance_type'         => 'required',
            'insurance_company'      => 'required_if:insurance_type,SPIPKA',
            'insurance_expiry_date'  => 'nullable|required_if:insurance_type,SPIPKA|date_format:Y-m-d',
            'signed_contract'        => 'nullable|mimes:jpeg,jpg,png,pdf',
            'rank'                   => 'required|array'
        ];
    }
}
