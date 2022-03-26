<?php
namespace App\Transformers;

use App\Models\Inventory;
use League\Fractal\TransformerAbstract;

class InventoryTransformer extends TransformerAbstract
{

    public function transform(Inventory $inventory)
    {
        return [
            'id' => $inventory->id,
			'name' => $inventory->name,
			'vessel_id' => $inventory->vessel_id,
			'vessel_name' => $inventory->vessel->name,
			'vessel' => ['id' => $inventory->vessel->id,'name' => $inventory->vessel->name],
            'can_edit' => $inventory->can_edit,
            'can_delete' => $inventory->can_delete,
        ];
    }
}