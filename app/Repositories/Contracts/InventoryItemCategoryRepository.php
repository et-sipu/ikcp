<?php

namespace App\Repositories\Contracts;

use App\Models\InventoryItemCategory;

/**
 * Interface InventoryItemCategorylRepository.
 */
interface InventoryItemCategoryRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return InventoryItemCategory
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param InventoryItemCategory $inventory_item_category
     * @param array       $input
     *
     * @return mixed
     */
    public function update(InventoryItemCategory $inventory_item_category, array $input);

    /**
     * @param InventoryItemCategory $inventory_item_category
     *
     * @return mixed
     */
    public function destroy(InventoryItemCategory $inventory_item_category);

    /**
     * @return mixed
     */
    public function get_categories_as_tree();
}
