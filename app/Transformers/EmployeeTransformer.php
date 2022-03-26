<?php

namespace App\Transformers;

use App\Models\Employee;
use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
{
    /**
     * @var bool
     */
    private $forShow;

    /**
     * EmployeeTransformer constructor.
     *
     * @param bool $forShow
     */
    public function __construct($forShow = false)
    {
        $this->forShow = $forShow;
    }

    public function transform(Employee $employee)
    {
        if ($this->forShow) {
            $data = [];

            $data['personal_info'] = $employee->toArray();
            $data['personal_info']['nationality'] = $employee->nationality_obj;
            $data['personal_info']['photo_path'] = $employee->photo_path;
            $data['personal_info']['signature_path'] = $employee->signature_path;
            $data['personal_info']['name'] = ucwords($employee->name);
            $data['personal_info']['surname'] = ucwords($employee->surname);
            $data['personal_info']['email'] = $employee->user ? $employee->user->email : null;
            $data['contact_info'] = $employee->contactInfo ? $employee->contactInfo->toArray()
                                                            : [
                                                                    'personal_hp'             => null,
                                                                    'next_of_kin_name'        => null,
                                                                    'next_of_kin_hp'          => null,
                                                                    'next_of_kin_address'     => null,
                                                                    'next_of_kin_relationship'=> null,
                                                                ];
            $data['financial_info'] = $employee->financialInfo ? $employee->financialInfo->toArray()
                                                                 : [
                                                                        'payment_method' => 'Cash',
                                                                        'bank'           => null,
                                                                        'account_no'     => null,
                                                                        'income_tax_no'  => null,
                                                                        'epf'            => null,
                                                                        'socso_no'       => null,
                                                                        'zakat'          => null,
                                                                ];
            $data['qualifications_info'] = [
                                            'academic_qualifications'     => [],
                                            'professional_qualifications' => [],
                                        ];
            if ($employee->academicQualifications) {
                $academic_qualifications = $employee->academicQualifications->toArray();
                $academic_qualifications = array_map(function ($query) {
                    $query['url'] = $query['degree_photocopy_path'];
                    $query['file'] = null;
                    unset($query['media'], $query['degree_photocopy_path']);

                    return $query;
                }, $academic_qualifications);

                $data['qualifications_info']['academic_qualifications'] = $academic_qualifications;
            }
            if ($employee->professionalQualifications) {
                $professional_qualifications = $employee->professionalQualifications->toArray();
                $professional_qualifications = array_map(function ($q) {
                    $q['url'] = $q['degree_photocopy_path'];
                    $q['file'] = null;
                    unset($q['media'], $q['degree_photocopy_path']);

                    return $q;
                }, $professional_qualifications);

                $data['qualifications_info']['professional_qualifications'] = $professional_qualifications;
            }
            $data['spouse_info'] = $employee->spouseInfo
                                        ? $employee->spouseInfo->toArray()
                                        : [
                                                'name'            => null,
                                                'nric_no'         => null,
                                                'occupation'      => null,
                                                'employer_name'   => null,
                                                'employer_address'=> null,
                                                'hp'              => null,
                                                'dom'             => null,
                                            ];

            $data['parents_info'] = $employee->parentsInfo
                                        ? $employee->parentsInfo->toArray()
                                        : [
                                                'father_name'  => null,
                                                'father_status'=> null,
                                                'mother_name'  => null,
                                                'mother_status'=> null,
                                            ];

            $data['children_info'] = $employee->childrenInfo && $employee->childrenInfo->children ? $employee->childrenInfo->children : [];
            $data['job_info'] = $employee->jobInfo
                                    ? $employee->jobInfo->toArray()
                                    : [
                                        'department'         => null,
                                        'branch'             => null,
                                        'report_to'          => null,
                                        'level'              => null,
                                        'designation'        => null,
                                        'email'              => null,
                                        'employment_status'  => 'on_probation',
                                        'probationary_period'=> 6, // Six months
                                        'staff_id'           => null,
                                        'thumbs'             => null,
                                    ];

            $data['job_info']['branch'] = $employee->jobInfo && $employee->jobInfo->branch ? ['id' => $employee->jobInfo->branch->id, 'name' => $employee->jobInfo->branch->name] : null;

            $data['job_info']['report_to'] = $employee->jobInfo && $employee->jobInfo->report_to ? ['id' => $employee->jobInfo->report_to->id, 'name' => $employee->jobInfo->report_to->full_name] : null;

            $data['job_info']['level'] = $employee->jobInfo && $employee->jobInfo->level ? ['id' => $employee->jobInfo->level->id, 'level' => $employee->jobInfo->level->level] : null;

            $data['job_info']['department'] = $employee->jobInfo && $employee->jobInfo && $employee->jobInfo->department ? ['id' => $employee->jobInfo->department->id, 'name' => $employee->jobInfo->department->name] : null;

            $data['job_info']['designation'] = $employee->jobInfo && $employee->jobInfo && $employee->jobInfo->designation ? ['id' => $employee->jobInfo->designation->id, 'name' => $employee->jobInfo->designation->title] : null;

            $data['job_info']['thumbs'] = $employee->thumbs && \count($employee->thumbs->pluck('staff_id')->toArray()) > 0 ? implode(',', $employee->thumbs->pluck('staff_id')->toArray()) : null;

            $data['documents_info'] = $employee->documents;
            $documents = [];

            $documents['PASSPORT'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['WORK_PERMIT'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];

            foreach ($employee->documents as $document) {
                $documents[$document->document_type] = [
                    'no'     => $document->document_no,
                    'id'     => $document->id,
                    'expiry' => $document->document_expiry_date,
                    'url'    => isset($employee->documents_paths[$document->document_type]) ? $employee->documents_paths[$document->document_type] : null,
                    'file'   => null,
                ];
            }
            $data['documents_info'] = $documents;
            $data['can_edit'] = $employee->can_edit;

            return $data;
        }

        return [
            'id'                        => $employee->id,
            'name'                      => ucwords($employee->name),
            'surname'                   => ucwords($employee->surname),
            'full_name'                 => ucwords($employee->full_name),
            'department_name'           => $employee->department_name,
            'branch_name'               => $employee->branch_name,
//            'sex'                       => $employee->sex,
//            'religion'                  => $employee->religion,
            'nationality'               => $employee->real_nationality,
            'nric_no'                   => $employee->nric_no,
//            'date_of_join'              => $employee->date_of_join,
//            'date_of_birth'             => $employee->date_of_birth,
//            'place_of_birth'            => $employee->place_of_birth,
//            'blacklisted'               => $employee->blacklisted,
            'date_and_place_of_birth'   => $employee->date_of_birth.' , '.$employee->place_of_birth,
//            'rank'                      => ($employee->capabilitiesInfo && $employee->capabilitiesInfo->rank) ? $employee->capabilitiesInfo->rank->name : '',
//            'coc_type'                  => $employee->capabilitiesInfo->coc_certificate_type,
//            'coc_expiry'                => ($employee->documents->where('document_type', 'COC_CERT')->first()) ? $employee->documents->where('document_type', 'COC_CERT')->first()->document_expiry_date : '',
            'passport'                  => $employee->documents->where('document_type', 'PASSPORT')->first() ? $employee->documents->where('document_type', 'PASSPORT')->first()->document_no : '',
            'passport_expiry'           => $employee->documents->where('document_type', 'PASSPORT')->first() ? $employee->documents->where('document_type', 'PASSPORT')->first()->document_expiry_date : '',
            'can_edit'                  => $employee->can_edit,
            'can_delete'                => $employee->can_delete,
            '_rowVariant'               => $employee->blacklisted ? 'danger' : ('permanent' !== $employee->jobInfo->employment_status ? $employee->jobInfo->employment_status : ''),
        ];
    }
}
