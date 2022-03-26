<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequisitionRequest extends FormRequest
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
                'released_to'              => 'required',
                'title'                    => 'required',
                'details'                  => 'required',
                'remarks'                  => 'required',
                'project'                  => 'required',
                'supplier'                 => 'required',
                'new_outstanding_invoices' => 'required|numeric|min:1',
                'attachments.*.file'       => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
            ];
        } elseif ('finance_approving' === $this->get('status')) {
            return [
                'last_pv_no'               => 'nullable|numeric',
                'last_payment_amount'      => 'nullable|numeric',
                'last_payment_date'        => 'nullable|date_format:Y-m-d',
                'old_outstanding_invoices' => 'nullable|numeric',
                'payment'                  => 'required|numeric|min:1',
            ];
        } elseif ('dec_approving' === $this->get('status')) {
            return [
                'approved_payment'         => 'required|numeric|min:1',
            ];
        } elseif ('releasing' === $this->get('status')) {
            return [
                'cheque_no'   => 'required',
                'cheque_bank' => 'required',
                'cheque_date' => 'required|date_format:Y-m-d',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'attachments.*.file.mimes'          => 'The attachments must be a files of type: jpeg, jpg, png, gif, pdf.',
            'new_outstanding_invoices.required' => 'The Payment field is required.',
            'new_outstanding_invoices.numeric'  => 'The Payment field must be a number.',
            'new_outstanding_invoices.min'      => 'The Payment must be at least 1.',
            'payment.required'                  => 'The Proposed Payment field is required.',
            'payment.numeric'                   => 'The Proposed Payment must be a number.',
            'payment.min'                       => 'The Proposed Payment must be at least 1.',
        ];
    }
}
