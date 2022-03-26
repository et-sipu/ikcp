<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashRequisitionRequest extends FormRequest
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
            'request_type'       => 'required',
            'purpose'            => 'required',
            'amount'             => 'required|numeric',
            'attachments.*.file' => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
        ];
    }

    public function messages()
    {
        return [
            'attachments.*.file.mimes' => 'The attachments must be a files of type: jpeg, jpg, png, gif, pdf.',
        ];
    }
}
