<?php
namespace App\Transformers;

use App\Models\Equipment;
use League\Fractal\TransformerAbstract;

class EquipmentTransformer extends TransformerAbstract
{

    public function transform(Equipment $equipment)
    {
        $brand_name= [ 'name' =>$equipment->brand ];
        return [
            'id' => $equipment->id,
			'name' => $equipment->name,
			'brand' => $brand_name,
			'model' => $equipment->model,
			'location' => $equipment->location,
			'serial_num' => $equipment->serial_num,
			'status' => $equipment->status,
            'vessel_id' => $equipment->vessel_id,
            'vessel_name' => $equipment->vessel->name,
            'vessel' => ['id' => $equipment->vessel->id,'name' => $equipment->vessel->name],
            'can_edit' => $equipment->can_edit,
            'can_delete' => $equipment->can_delete,
            'can_create_own' => $equipment->can_create_own,
        ];
    }
}