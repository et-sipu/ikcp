<?php

namespace App\Transformers;

use App\Models\SeafarerContract;
use League\Fractal\TransformerAbstract;

class SeafarerContractTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'crew_request',
    ];

    public function transform(SeafarerContract $contract)
    {
        return [
            'id'                       => $contract->id,
            'vessel_name'              => $contract->vessel->code,
            'vessel_id'                => $contract->vessel_id,
            'vessel'                   => [
                'name'              => $contract->vessel->name,
                'id'                => $contract->vessel_id,
            ],
            'seafarer_name'           => $contract->seafarer ? $contract->seafarer->full_name : '',
            'seafarer_id'             => $contract->seafarer_id,
            'seafarer'                => $contract->seafarer ? [
                //'name'              => $contract->seafarer->name,
                'name'              => $contract->seafarer->full_name,
                'id'                => $contract->seafarer_id,
            ] : [],
            'rank_name'                            => $contract->rank ? $contract->rank->name : ($contract->seafarer && $contract->seafarer->capabilitiesInfo && $contract->seafarer->capabilitiesInfo->rank ? $contract->seafarer->capabilitiesInfo->rank->name : ''),
            'rank'                            => ['id' => $contract->contract_rank_id , 'name' => $contract->rank->name],
            'basic_salary'                    => $contract->basic_salary,
            'currency'                        => $contract->currency,
            'pay_frequency'                   => $contract->pay_frequency,
            'pay_leave'                       => $contract->pay_leave,
            'scheduled_sign_on_date'          => $contract->scheduled_sign_on_date,
            'sign_on_date'                    => $contract->sign_on_date
                    ? $contract->sign_on_date
                    : ($contract->sign_on ? ['date' => $contract->sign_on->sign_date, 'status' => $contract->sign_on->status, 'id' => $contract->sign_on->id] : null),
            'sign_off_date'          => $contract->sign_off_date
                    ? $contract->sign_off_date
                    : ($contract->sign_off ? ['date' => $contract->sign_off->sign_date, 'status' => $contract->sign_off->status, 'id' => $contract->sign_off->id] : null),
            'sign_on_port_name'       => $contract->sign_on_port->name,
            'sign_on_port'            => [
                'id'  => $contract->sign_on_port->id,
                'name'=> $contract->sign_on_port->name,
            ],
            'duration_of_service'                => $contract->duration_of_service,
            'duration_of_service_unit'           => $contract->duration_of_service_unit,
            'tested_by'                          => $contract->tested_by,
            'insurance_type'                     => $contract->insurance_type,
            'insurance_company'                  => $contract->insurance_company,
            'insurance_expiry_date'              => $contract->insurance_expiry_date,
            'signed_contract_path'               => $contract->signed_contract_path,
            'status'                             => $contract->status,
            'remarks'                            => $contract->remarks,
            //'can_edit'          => $contract->can_edit,
            //'can_delete'          => $contract->can_delete,
            //'can_approve'          => $contract->can_approve,
            //'can_add_signs'          => $contract->can_add_signs,
        ];
    }

    public function includeCrewRequest(SeafarerContract $contract)
    {
        return $contract->crew_request ? $this->item($contract->crew_request, new CrewRequestTransformer()) : null;
    }
}
