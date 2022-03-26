<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashRequisitionRequest extends FormRequest
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
        if ('hod_approving' === $this->get('status')) {
            return [
                'purpose'            => 'required',
                'amount'             => 'required|numeric',
                'attachments.*.file' => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
            ];
        }

        if ('proofing' === $this->get('status')) {
            return [
                'receipts.*.file' => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
                'receipts'        => 'required|array|min:1',
            ];
        }

        return [
            'approved_amount'             => 'required|numeric',
            'cash_advance_taken'          => 'required|numeric',
            'cash_advance_taken_date'     => 'nullable|date_format:Y-m-d',
            'total_receipt_returned'      => 'required|numeric',
            'total_receipt_returned_date' => 'nullable|date_format:Y-m-d',
            'outstanding_ca'              => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'attachments.*.file.mimes' => 'The attachments must be a files of type: jpeg, jpg, png, gif, pdf.',
            'receipts.*.file.mimes'    => 'The attachments must be a files of type: jpeg, jpg, png, gif, pdf.',
        ];
    }
}
