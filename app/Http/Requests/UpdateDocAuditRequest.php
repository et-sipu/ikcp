<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocAuditRequest extends FormRequest
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
            'audit_date' => 'required|date_format:Y-m-d',
            'nc' => 'required|number',
            'obs' => 'required|number',
            'audit_file' => 'nullable|mimes:doc,docx,xls,xlsx,pdf',
        ];

    }
}
