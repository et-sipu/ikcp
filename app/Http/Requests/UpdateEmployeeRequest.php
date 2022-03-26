<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'photo'                          => 'nullable|mimes:jpeg,jpg,png,gif',
            'personal_info.name'             => 'required',
            'personal_info.surname'          => 'required',
            'personal_info.sex'              => 'required',
            'personal_info.race'             => 'required',
            'personal_info.marital_status'   => 'required',
            'personal_info.religion'         => 'required',
//            'personal_info.blood_type'       => 'required',
            'personal_info.nationality'      => 'required',
            'personal_info.date_of_birth'    => 'required|before:'.Carbon::now()->subYears(16)->minute(0)->second(0)->hour(0)->format('Y-m-d'), // Age must be at least 14 Year
            'personal_info.date_of_join'     => 'required|before_or_equal:'.Carbon::now()->format('Y-m-d'), // Join date must be before or equal today
            'personal_info.place_of_birth'   => 'required',
            'personal_info.nric_no'          => [
                'required_if:personal_info.nationality.nationality,Malaysian',
                'nullable',
                'regex:/^\d{6}\-\d{2}\-\d{4}$/',
            ],
            'personal_info.address' => 'required',

            'contact_info.personal_hp'      => [
                'required',
                'regex:/^[\d\+\-\(\)]{7,15}$/',
            ],
            'contact_info.personal_email'      => 'nullable|email',

            'contact_info.next_of_kin_name'              => 'required',
            'contact_info.next_of_kin_address'           => 'required',
            'contact_info.next_of_kin_relationship'      => 'required',
            'contact_info.next_of_kin_hp'                => [
                'required',
                'regex:/^[\d\+\-\(\)]{7,15}$/',
            ],

            'spouse_info.name'       => 'required_if:personal_info.marital_status,Married',
            'spouse_info.nric_no'    => 'required_if:personal_info.marital_status,Married',

//            'parents_info.father_name'      => 'required',
//            'parents_info.father_status'    => 'required',
//            'parents_info.mother_name'      => 'required',
//            'parents_info.mother_status'    => 'required',

            'financial_info.payment_method'    => 'required',
            'financial_info.bank'              => 'required_if:financial_info.payment_method,Bank',
            'financial_info.account_no'        => 'required_if:financial_info.payment_method,Bank',
//            'financial_info.socso_no'        => 'required',
//            'financial_info.epf'        => 'required',
//            'financial_info.income_tax_no'        => 'required',

            'qualifications_info.academic_qualifications.*.year'        => 'required|numeric|between:1950,'.date('Y'),
            'qualifications_info.academic_qualifications.*.authority'   => 'required',
            'qualifications_info.academic_qualifications.*.degree'      => 'required',
            'qualifications_info.academic_qualifications.*.file'        => 'required_if:qualifications_info.academic_qualifications.*.url,|nullable|mimes:jpeg,jpg,png,gif,pdf',

            'qualifications_info.professional_qualifications.*.year'        => 'required|numeric|between:1950,'.date('Y'),
            'qualifications_info.professional_qualifications.*.authority'   => 'required',
            'qualifications_info.professional_qualifications.*.degree'      => 'required',
            'qualifications_info.professional_qualifications.*.file'        => 'required_if:qualifications_info.professional_qualifications.*.url,|nullable|mimes:jpeg,jpg,png,gif,pdf',

            'children_info.*.name'     => 'required',
            'children_info.*.dob'      => 'date_format:Y-m-d',
            'children_info.*.gender'   => 'required',

            'job_info.branch'                => 'required',
            'job_info.department'            => 'required',
            'job_info.level'                 => 'required',
            'job_info.employment_status'     => 'required',
            'job_info.probationary_period'   => 'nullable|required_if:job_info.employment_status,on_probation|numeric',
//            'job_info.email'   => 'nullable|email',
            'job_info.email'    => ['nullable', 'email', 'regex:/(.*)@(inaikiara\.com\.my|selatmelaka\.com\.my|imwsb\.com\.my|rimbaramin\.com\.my)$/i'],
            'job_info.thumbs'   => [
                'nullable',
                'regex:/^\d+(,\d+)*$/',
            ],

            'documents_info.*.no'   => 'nullable|between:4,30',
            'documents_info.*.file' => 'nullable|mimes:jpeg,jpg,png,gif,pdf',

            //'documents_info.passport.no' => 'nullable|between:6,30',
            'documents_info.passport.expiry' => 'nullable|required_unless:documents_info.passport.no,|date_format:Y-m-d',

            //'documents_info.work_permit.no' => 'nullable|between:6,30',
            'documents_info.work_permit.expiry' => 'nullable|required_unless:documents_info.work_permit.no,|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'qualifications_info.academic_qualifications.*.file.required_if'     => 'The file can\'t be empty',
            'qualifications_info.professional_qualifications.*.file.required_if' => 'The file can\'t be empty',
            'job_info.email.regex'                                               => 'Email address must be @ one of the official company domains (inaikiara.com.my, selatmelaka.com.my, imwsb.com.my or rimbaramin.com.my)',
        ];
    }
}
