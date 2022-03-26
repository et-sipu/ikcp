<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocTemplateRequest extends FormRequest
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
            'template_type' => 'required',
            'code' => 'required',
            'title' => 'required',
            'template' => 'nullable|mimes:doc,docx,xls,xlsx,pdf',
        ];
    }
}
