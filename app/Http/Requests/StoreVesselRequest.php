<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVesselRequest extends FormRequest
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
            'name'                  => 'required|unique:vessels',
            'code'                  => 'required|unique:vessels|between:3,5',
            'imo_number'            => 'required|size:7',
            'port_of_registry'      => 'required',
            'flag'                  => 'required',
            'certificates.*.file'   => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
            'certificates.*.expiry' => 'nullable|sometimes|date_format:Y-m-d',
            'certificates.*.type'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'certificates.*.file.mimes'      => 'The File attribute must be a file of type: jpeg, jpg, png, gif, pdf.',
            'certificates.*.expiry.required' => 'Expiry date of certificate is required',
            'certificates.*.type.required'   => 'Certificate type is required',
        ];
    }
}
