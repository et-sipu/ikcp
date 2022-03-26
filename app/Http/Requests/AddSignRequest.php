<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddSignRequest extends FormRequest
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
            'sign_date' => 'required|date_format:Y-m-d',
//            'sign_type' => 'required|in:ON,OFF',
            'sign_type' => [
                            'required',
                            'in:ON,OFF',
//                            Rule::unique('signons')->where(function ($query) {
//                                return $query->where('seafarer_contract_id', $this->get('seafarer_contract_id'));
//                            })
                        ],
            'seafarer_contract_id' => Rule::unique('signons')->where(function ($query) {
                return $query->where('sign_type', $this->get('sign_type'));
            }),
        ];
    }
}
