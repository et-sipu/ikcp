<?php
namespace App\Transformers;

use App\Models\InventoryTransaction;
use League\Fractal\TransformerAbstract;

class InventoryTransactionTransformer extends TransformerAbstract
{

    public function transform(InventoryTransaction $inventory_transaction)
    {
        return [
            'id' => $inventory_transaction->id,
			'transaction_type' => $inventory_transaction->transaction_type,
			'inventory_item_id' => $inventory_transaction->inventory_item_id,
			'variations' => $inventory_transaction->variations,
			'quantity' => abs($inventory_transaction->quantity),
			'inventory_id' => $inventory_transaction->inventory_id,
			'description' => $inventory_transaction->description,
			'note' => $inventory_transaction->note,
			'transaction_date' => $inventory_transaction->transaction_date,
			'location' => $inventory_transaction->location,
            'expired_at' => $inventory_transaction->expired_at,
			'inventory_name' => $inventory_transaction->inventory->inventory_name,
			'inventory' => ['id' => $inventory_transaction->inventory->id,'name' => $inventory_transaction->inventory->name],
			'inventory_item_name' => $inventory_transaction->inventory_item->name,
			'inventory_item' => ['id' => $inventory_transaction->inventory_item->id,'name' => $inventory_transaction->inventory_item->name, 'unit' => $inventory_transaction->inventory_item->unit],
            'can_edit' => $inventory_transaction->can_edit,
            'can_delete' => $inventory_transaction->can_delete,
        ];
    }
}