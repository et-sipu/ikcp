<?php
namespace App\Transformers;

use App\Models\InventoryItem;
use App\Models\InventoryItemCategory;
use App\Models\InventoryTransaction;
use League\Fractal\TransformerAbstract;

class RobInventroyTransformer extends TransformerAbstract
{
    private $forExport;


    /**
     * RobInventroyTransformer constructor.
     * @param $forExport
     */
    public function __construct($forExport = false)
    {
        $this->forExport = $forExport;
    }

    public function transform(InventoryTransaction $inventoryTransaction)
    {

        if($this->forExport) return $this->for_export($inventoryTransaction);

        return
            [
                'item_id'=>$inventoryTransaction->inventory_item->id,
//                'store'=> $inventoryTransaction->inventory->name,
                'store' => $inventoryTransaction->inventory->inventory_name,
                'store_id'=> $inventoryTransaction->inventory->id,
                'item'=>$inventoryTransaction->inventory_item->name,
                'category'=>$inventoryTransaction->inventory_item->category->name,
                'parent_category'=>$inventoryTransaction->inventory_item->category->parent ? $inventoryTransaction->inventory_item->category->parent->name : null,
                'quantity'=>$inventoryTransaction->quantity,
                'chart_data' => null,
                'chart_info' => null
            ];

    }
    /**
     * @param InventoryTransaction $inventoryTransaction
     *
     * @return array
     */
    public function for_export(InventoryTransaction $inventoryTransaction)
    {
        return [
            'Item ID'=>$inventoryTransaction->inventory_item->id,
            'Store Name'=> $inventoryTransaction->inventory->name,
            'Item'=>$inventoryTransaction->inventory_item->name,
            'Category'=>$inventoryTransaction->inventory_item->category->name,
            'Parent Category'=>$inventoryTransaction->inventory_item->category->parent ? $inventoryTransaction->inventory_item->category->parent->name : null,
            'Quantity'=>$inventoryTransaction->quantity,
        ];
    }
}