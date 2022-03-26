<?php

namespace App\Repositories;

use Exception;
use App\Models\InventoryItem;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\InventoryItemRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentInventoryItemRepository.
 */
class EloquentInventoryItemRepository extends EloquentBaseRepository implements InventoryItemRepository
{
    /**
     * EloquentInventoryItemRepository constructor.
     *
     * @param InventoryItem $inventory_item
     */
    public function __construct(InventoryItem $inventory_item)
    {
        parent::__construct($inventory_item);
    }

    /**
     * @param $name
     *
     * @return InventoryItem
     */
    public function find($name)
    {
        /* @var InventoryItem $inventory_item */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\InventoryItem
     */
    public function store(array $input)
    {
        /** @var InventoryItem $inventory_item */
        $input['category_id'] = $input['category'];
        $inventory_item = $this->make(array_except($input, []));

        if ($this->find($inventory_item->name)) {
            throw new GeneralException(__('exceptions.backend.inventory_items.already_exist'));
        }

        DB::transaction(function () use ( $inventory_item, $input) {
            if (!$inventory_item->save()) {
                throw new GeneralException(__('exceptions.backend.inventory_items.create'));
            }

            return true;
        });

        return $inventory_item;
    }

    /**
     * @param InventoryItem $inventory_item
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\InventoryItem
     */
    public function update(InventoryItem $inventory_item, array $input)
    {
        if (($existingInventoryItem = $this->find($inventory_item->name))
          && $existingInventoryItem->id !== $inventory_item->id
        ) {
            throw new GeneralException(__('exceptions.backend.inventory_items.already_exist'));
        }
        $input['category_id'] = $input['category'];

        DB::transaction(function () use ( $inventory_item, $input) {
            if (!$inventory_item->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.inventory_items.update'));
            }

            return true;
        });

        return $inventory_item;
    }

    /**
     * @param InventoryItem $inventory_item
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(InventoryItem $inventory_item)
    {
        if (! $inventory_item->delete()) {
            throw new GeneralException(__('exceptions.backend.inventory_items.delete'));
        }

        return true;
    }
}
