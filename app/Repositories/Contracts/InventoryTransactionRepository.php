<?php

namespace App\Repositories\Contracts;

use App\Models\InventoryTransaction;

/**
 * Interface InventoryTransactionlRepository.
 */
interface InventoryTransactionRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return InventoryTransaction
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param InventoryTransaction $inventory_transaction
     * @param array       $input
     *
     * @return mixed
     */
    public function update(InventoryTransaction $inventory_transaction, array $input);

    /**
     * @param InventoryTransaction $inventory_transaction
     *
     * @return mixed
     */
    public function destroy(InventoryTransaction $inventory_transaction);

    /**
     * @param InventoryTransaction $inventory_transaction
     * @return mixed
     */
    public function check_transaction_quantity(InventoryTransaction $inventory_transaction);

    /**
     * @param $item_id
     * @param $inventory_id
     * @param $variations
     * @return mixed
     */
    public function check_quantity($item_id, $inventory_id, $variations = []);

}
