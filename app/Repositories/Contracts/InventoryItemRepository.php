<?php

namespace App\Repositories\Contracts;

use App\Models\InventoryItem;

/**
 * Interface InventoryItemlRepository.
 */
interface InventoryItemRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return InventoryItem
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param InventoryItem $inventory_item
     * @param array       $input
     *
     * @return mixed
     */
    public function update(InventoryItem $inventory_item, array $input);

    /**
     * @param InventoryItem $inventory_item
     *
     * @return mixed
     */
    public function destroy(InventoryItem $inventory_item);
}
