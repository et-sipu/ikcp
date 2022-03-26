<?php
namespace App\Transformers;

use App\Models\CompanyRegistrations;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Str;

class CompanyRegistrationsTransformer extends TransformerAbstract
{

    public function transform(CompanyRegistrations $company_registration)
    {
        return [
            'id' => $company_registration->id,
			'registration' => $company_registration->registration,
			'registration_no' => $company_registration->registration_no,
			'company' => $company_registration->company,
			'type' => $company_registration->type,
			'grade' => $company_registration->grade,
			'validity_from' => $company_registration->validity_from,
			'validity_to' => $company_registration->validity_to,
            'registrations_file_url' => $company_registration->registration_file_url,
            'registrations_file_name' => str_limit($company_registration->registration_file_name, 15),
            'can_edit' => $company_registration->can_edit,
            'can_delete' => $company_registration->can_delete,
            ];
    }
}