<?php
namespace App\Transformers;

use App\Models\VesselInsurance;
use League\Fractal\TransformerAbstract;

class VesselInsuranceTransformer extends TransformerAbstract
{

    public function transform(VesselInsurance $vessel_insurance)
    {
        return [
            'id' => $vessel_insurance->id,
			'name' => $vessel_insurance->name,
			'type' => $vessel_insurance->type,
			'vessels' => $vessel_insurance->vessels,
			'expiry_date' => $vessel_insurance->expiry_date,
            'can_edit' => $vessel_insurance->can_edit,
            'attachments' => $vessel_insurance->attachments_info,
            'can_delete' => $vessel_insurance->can_delete,
        ];
    }
}