<?php

namespace App\Repositories;

use Exception;
use App\Models\InventoryTransaction;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\InventoryTransactionRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentInventoryTransactionRepository.
 */
class EloquentInventoryTransactionRepository extends EloquentBaseRepository implements InventoryTransactionRepository
{
    /**
     * EloquentInventoryTransactionRepository constructor.
     *
     * @param InventoryTransaction $inventory_transaction
     */
    public function __construct(InventoryTransaction $inventory_transaction)
    {
        parent::__construct($inventory_transaction);
    }

    /**
     * @param $name
     *
     * @return InventoryTransaction
     */
    public function find($name)
    {
        /* @var InventoryTransaction $inventory_transaction */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\InventoryTransaction
     */
    public function store(array $input)
    {
        $input['inventory_id'] = $input['inventory']['id'];
		$input['inventory_item_id'] = $input['inventory_item']['id'];
		$input['transaction_date'] = $input['transaction_date'] ?: date("Y-m-d");
		/** @var InventoryTransaction $inventory_transaction */
        $inventory_transaction = $this->make(array_except($input, []));

        if ($this->find($inventory_transaction->name)) {
            throw new GeneralException(__('exceptions.backend.inventory_transactions.already_exist'));
        }

        if ($inventory_transaction->transaction_type == 'check-out' && $inventory_transaction->quantity > $this->check_transaction_quantity($inventory_transaction)) {
            throw new GeneralException(__('exceptions.backend.inventory_transactions.insufficient_items'));
        }

        DB::transaction(function () use ( $inventory_transaction, $input) {

            if($inventory_transaction->transaction_type == 'check-out') $inventory_transaction->quantity *= -1;

            if (!$inventory_transaction->save()) {
                throw new GeneralException(__('exceptions.backend.inventory_transactions.create'));
            }

            return true;
        });

        return $inventory_transaction;
    }

    public function check_transaction_quantity(InventoryTransaction $inventory_transaction)
    {
        return $this->check_quantity($inventory_transaction->inventory_item_id, $inventory_transaction->inventory_id, $inventory_transaction->variations ?: []);
    }

    public function check_quantity($item_id, $inventory_id, $variations = [])
    {

        $query = $this->query();

        $query = $query->where('inventory_item_id',$item_id)
            ->where('inventory_id', $inventory_id);
//            ->where('transaction_type', 'check-in');

        foreach ($variations as $variation => $value) {
            $query->whereRaw("JSON_EXTRACT(variations,'$.".$variation."') ='".$value."'");
        }

        $quantity = $query->sum('quantity');

        return $quantity;

    }

    /**
     * @param InventoryTransaction $inventory_transaction
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\InventoryTransaction
     */
    public function update(InventoryTransaction $inventory_transaction, array $input)
    {
        if (($existingInventoryTransaction = $this->find($inventory_transaction->name))
          && $existingInventoryTransaction->id !== $inventory_transaction->id
        ) {
            throw new GeneralException(__('exceptions.backend.inventory_transactions.already_exist'));
        }
        $input['inventory_id'] = $input['inventory']['id'];
		$input['inventory_item_id'] = $input['inventory_item']['id'];
        $input['transaction_date'] = $input['transaction_date'] ?: date("Y-m-d");

        DB::transaction(function () use ( $inventory_transaction, $input) {
            if (!$inventory_transaction->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.inventory_transactions.update'));
            }

            return true;
        });

        return $inventory_transaction;
    }

    /**
     * @param InventoryTransaction $inventory_transaction
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(InventoryTransaction $inventory_transaction)
    {
        if (! $inventory_transaction->delete()) {
            throw new GeneralException(__('exceptions.backend.inventory_transactions.delete'));
        }

        return true;
    }
}
