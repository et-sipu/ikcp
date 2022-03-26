<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSeafarerRequest extends FormRequest
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
            'personal_info.name'           => 'required',
            'personal_info.surname'        => 'required',
            'personal_info.sex'            => 'required',
            'personal_info.religion'       => 'required',
            'personal_info.nationality'    => 'required',
            'personal_info.date_of_birth'  => 'required|before:'.Carbon::now()->subYears(14)->minute(0)->second(0)->hour(0)->format('Y-m-d'), // Age must be at least 14 Year
            'personal_info.date_of_join'   => 'required|before_or_equal:'.Carbon::now()->format('Y-m-d'), // Join date must be before or equal today
            'personal_info.place_of_birth' => 'required',
            'personal_info.nric_no'        => [
                'required_if:personal_info.nationality.nationality,Malaysian',
                'nullable',
                'regex:/^\d{6}\-\d{2}\-\d{4}$/',
            ],
            // 'personal_info.address' => 'required',

            'contact_info.personal_hp'      => [
                'required',
                'regex:/^[\d\+\-\(\)]{7,15}$/',
            ],
            'contact_info.next_of_kin_name'      => 'required',
            'contact_info.next_of_kin_hp'        => [
                'required',
                'regex:/^[\d\+\-\(\)]{7,15}$/',
            ],

            'financial_info.payment_method'    => 'required',
            'financial_info.bank'              => 'required_if:financial_info.payment_method,Bank,Home Allotment',
            'financial_info.account_no'        => 'required_if:financial_info.payment_method,Bank,Home Allotment',
            'financial_info.swift_code'        => 'required_if:financial_info.payment_method,Home Allotment',
            'financial_info.country_of_origin' => 'required_if:financial_info.payment_method,Home Allotment',

            'capabilities_info.rank' => 'required',

            'documents_info.*.no'   => 'nullable|between:4,30',
            'documents_info.*.file' => 'nullable|mimes:jpeg,jpg,png,gif,pdf',

            //'documents_info.passport.no' => 'nullable|between:6,30',
            'documents_info.passport.expiry' => 'nullable|required_unless:documents_info.passport.no,|date_format:Y-m-d',

            //'documents_info.work_permit.no' => 'sometimes|between:6,30',
            'documents_info.work_permit.expiry' => 'required_with:documents_info.work_permit.no|date_format:Y-m-d',

            //'documents_info.medical_cert.no' => 'sometimes|between:6,30',
            'documents_info.medical_cert.expiry' => 'required_with:documents_info.medical_cert.no|date_format:Y-m-d',

            //'documents_info.coc_cert.no' => 'sometimes|between:6,30',
            'documents_info.coc_cert.expiry' => 'required_with:documents_info.coc_cert.no|date_format:Y-m-d',

            //'documents_info.smc.no' => 'sometimes|between:6,30',
            'documents_info.smc.expiry' => 'required_with:documents_info.smc.no|date_format:Y-m-d',

            //'documents_info.discharge_book.no' => 'sometimes|between:6,30',
            'documents_info.discharge_book.expiry' => 'required_with:documents_info.discharge_book.no|date_format:Y-m-d',
        ];
    }
}
