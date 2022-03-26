<?php
namespace App\Transformers;

use App\Models\InventoryItemCategory;
use League\Fractal\TransformerAbstract;

class InventoryItemCategoryTransformer extends TransformerAbstract
{

    public function transform(InventoryItemCategory $inventory_item_category)
    {
        return [
            'id' => $inventory_item_category->id,
			'name' => $inventory_item_category->name,
			'parent_id' => $inventory_item_category->parent_id,
			'parent' => $inventory_item_category->parent_id,
			'parent_name' => $inventory_item_category->parent ? $inventory_item_category->parent->name : '',
            'can_edit' => $inventory_item_category->can_edit,
            'can_delete' => $inventory_item_category->can_delete,
        ];
    }
}