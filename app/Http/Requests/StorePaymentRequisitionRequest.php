<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequisitionRequest extends FormRequest
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
            'released_to'              => 'required',
            'title'                    => 'required',
            'details'                  => 'required',
            'remarks'                  => 'required',
            'project'                  => 'required',
            'supplier'                 => 'required',
            'new_outstanding_invoices' => 'required|numeric|min:1',
            'attachments.*.file'       => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
        ];
    }

    public function messages()
    {
        return [
            'attachments.*.file.mimes'          => 'The attachments must be a files of type: jpeg, jpg, png, gif, pdf.',
            'new_outstanding_invoices.required' => 'The Payment field is required.',
            'new_outstanding_invoices.numeric'  => 'The Payment field must be a number.',
            'new_outstanding_invoices.min'      => 'The Payment must be at least 1.',
        ];
    }
}
