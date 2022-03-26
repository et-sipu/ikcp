<?php
/**
 * Created by PhpStorm.
 * User: ahmadof
 * Date: 04/07/2018
 * Time: 9:07.
 */

namespace App\Transformers;

use App\Models\CrewRequest;
use League\Fractal\TransformerAbstract;

class CrewRequestTransformer extends TransformerAbstract
{
    public function transform(CrewRequest $crewRequest)
    {
        return [
            'id'                       => $crewRequest->id,
            'vessel_name'              => $crewRequest->vessel->name,
            'vessel_code'              => $crewRequest->vessel->code,
            'replacement_of'           => $crewRequest->replacement_of ? $crewRequest->replacement_of->name.' '.$crewRequest->replacement_of->surname : '',
            'suggested_seafarer'       => $crewRequest->suggested_seafarer ? $crewRequest->suggested_seafarer->name.' '.$crewRequest->suggested_seafarer->surname : '',
            'rank'                     => ['id' => $crewRequest->rank->id, 'name' => $crewRequest->rank->name],
            'to_sign_on'               => $crewRequest->to_sign_on,
            'created_at'               => $crewRequest->created_at->format('Y-m-d'),

            'vessel' => [
                'id'   => $crewRequest->vessel->id,
                'name' => $crewRequest->vessel->name,
            ],
            'candidate_seafarer' => $crewRequest->suggested_seafarer ? [
                'id'   => $crewRequest->suggested_seafarer->id,
                'name' => $crewRequest->suggested_seafarer->full_name,
            ] : [],
            'replaced_seafarer' => $crewRequest->replacement_of ? [
                'id'   => $crewRequest->replacement_of->id,
                'name' => $crewRequest->replacement_of->full_name,
            ] : [],
            'to_sign_on'        => $crewRequest->to_sign_on,
            'remarks'           => $crewRequest->remarks,
            'status'            => $crewRequest->status,

            'can_edit'            => $crewRequest->can_edit,
            'can_delete'          => $crewRequest->can_delete,
        ];
    }
}
