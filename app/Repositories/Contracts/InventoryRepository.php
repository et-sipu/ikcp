<?php

namespace App\Repositories\Contracts;

use App\Models\Inventory;

/**
 * Interface InventorylRepository.
 */
interface InventoryRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Inventory
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Inventory $inventory
     * @param array       $input
     *
     * @return mixed
     */
    public function update(Inventory $inventory, array $input);

    /**
     * @param Inventory $inventory
     *
     * @return mixed
     */
    public function destroy(Inventory $inventory);
}
