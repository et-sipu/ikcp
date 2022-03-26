<?php
namespace App\Transformers;

use App\Models\InventoryItem;
use League\Fractal\TransformerAbstract;

class InventoryItemTransformer extends TransformerAbstract
{

    public function transform(InventoryItem $inventory_item)
    {
        return [
            'id' => $inventory_item->id,
			'part_no' => $inventory_item->part_no,
			'name' => $inventory_item->name,
			'description' => $inventory_item->description,
			'category_id' => $inventory_item->category_id,
			'category' => $inventory_item->category_id,
			'category_name' => $inventory_item->category->name,
            'parent_category_name'=> $inventory_item->category && $inventory_item->category->parent ? $inventory_item->category->parent->name : null,
			'unit' => $inventory_item->unit,
			'usage' => $inventory_item->usage,
			'variants' => $inventory_item->variants ?: [],
            'can_edit' => $inventory_item->can_edit,
            'can_delete' => $inventory_item->can_delete,
        ];
    }
}