<?php

namespace App\Transformers;

use App\Models\Seafarer;
use League\Fractal\TransformerAbstract;

class SeafarerTransformer extends TransformerAbstract
{
    /**
     * @var bool
     */
    private $forShow;

    /**
     * SeafarerTransformer constructor.
     *
     * @param bool $forShow
     */
    public function __construct($forShow = false)
    {
        $this->forShow = $forShow;
    }

    public function transform(Seafarer $seafarer)
    {
        if ($this->forShow) {
            $data = [];

            $data['personal_info'] = $seafarer->toArray();
            $data['personal_info']['nationality'] = $seafarer->nationality_obj;
            $data['personal_info']['photo_path'] = $seafarer->photo_path;
            $data['personal_info']['signature_path'] = $seafarer->signature_path;
            $data['contact_info'] = $seafarer->contactInfo ? $seafarer->contactInfo->toArray() : null;
            $data['financial_info'] = $seafarer->financialInfo ? $seafarer->financialInfo->toArray() : null;
            $data['capabilities_info'] = $seafarer->capabilitiesInfo ? $seafarer->capabilitiesInfo->toArray() : null;
            if ($data['capabilities_info'] && $seafarer->capabilitiesInfo->rank) {
                $data['capabilities_info']['rank'] = ['id' => $seafarer->capabilitiesInfo->rank->id, 'name' => $seafarer->capabilitiesInfo->rank->name];
            }
            $data['documents_info'] = $seafarer->documents;
            $documents = [];

            $documents['PASSPORT'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['WORK_PERMIT'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['MEDICAL_CERT'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['SMC'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['DISCHARGE_BOOK'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['COC_CERT'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['GOC_GMDSS'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];
            $documents['MG_COR'] = ['no' => null, 'id' => null, 'expiry' => null, 'url' => null, 'file' => null];

            foreach ($seafarer->documents as $document) {
                $documents[$document->document_type] = [
                    'no'     => $document->document_no,
                    'id'     => $document->id,
                    'expiry' => $document->document_expiry_date,
                    'url'    => isset($seafarer->documents_paths[$document->document_type]) ? $seafarer->documents_paths[$document->document_type] : null,
                    'file'   => null,
                ];
            }
            $data['documents_info'] = $documents;
            $data['can_edit'] = $seafarer->can_edit;
            $last_contract = $seafarer->last_contract();
            $data['last_contract'] = $last_contract ? [
                'id'            => $last_contract->id,
                'salary'        => $last_contract->basic_salary,
                'currency'      => $last_contract->currency,
                'pay_frequency' => $last_contract->pay_frequency,
                'last_salary'   => $last_contract->currency.''.$last_contract->basic_salary.'/'.$last_contract->pay_frequency,
            ] : null;

            return $data;
        }

        return [
            'id'                        => $seafarer->id,
            'name'                      => $seafarer->name,
            'surname'                   => $seafarer->surname,
            'full_name'                 => $seafarer->full_name,
//            'sex'                       => $seafarer->sex,
//            'religion'                  => $seafarer->religion,
            'nationality'               => $seafarer->real_nationality,
            'nric_no'                   => $seafarer->nric_no,
//            'date_of_join'              => $seafarer->date_of_join,
//            'date_of_birth'             => $seafarer->date_of_birth,
//            'place_of_birth'            => $seafarer->place_of_birth,
            'blacklisted'               => $seafarer->blacklisted,
            'date_and_place_of_birth'   => $seafarer->date_of_birth.' , '.$seafarer->place_of_birth,
            'rank'                      => $seafarer->capabilitiesInfo && $seafarer->capabilitiesInfo->rank ? $seafarer->capabilitiesInfo->rank->name : '',
            'coc_type'                  => $seafarer->capabilitiesInfo->coc_certificate_type,
            'coc_expiry'                => $seafarer->documents->where('document_type', 'COC_CERT')->first() ? $seafarer->documents->where('document_type', 'COC_CERT')->first()->document_expiry_date : '',
            'passport'                  => $seafarer->documents->where('document_type', 'PASSPORT')->first() ? $seafarer->documents->where('document_type', 'PASSPORT')->first()->document_no : '',
            'passport_expiry'           => $seafarer->documents->where('document_type', 'PASSPORT')->first() ? $seafarer->documents->where('document_type', 'PASSPORT')->first()->document_expiry_date : '',
            'can_edit'                  => $seafarer->can_edit,
            'can_delete'                => $seafarer->can_delete,
            '_rowVariant'               => $seafarer->blacklisted ? 'danger' : '',
        ];
    }
}
