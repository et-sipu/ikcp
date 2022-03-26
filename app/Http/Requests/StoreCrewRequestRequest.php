<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCrewRequestRequest extends FormRequest
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
            'vessel'             => 'required|array',
            'replaced_seafarer'  => 'nullable|array',
            'candidate_seafarer' => 'nullable|array',
            'to_sign_on'         => 'required|date_format:Y-m-d',
            'rank'               => 'required',
        ];
    }
}
