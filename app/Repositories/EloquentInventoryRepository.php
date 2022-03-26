<?php

namespace App\Repositories;

use Exception;
use App\Models\Inventory;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\InventoryRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentInventoryRepository.
 */
class EloquentInventoryRepository extends EloquentBaseRepository implements InventoryRepository
{
    /**
     * EloquentInventoryRepository constructor.
     *
     * @param Inventory $inventory
     */
    public function __construct(Inventory $inventory)
    {
        parent::__construct($inventory);
    }

    /**
     * @param $name
     *
     * @return Inventory
     */
    public function find($name)
    {
        /* @var Inventory $inventory */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Inventory
     */
    public function store(array $input)
    {
        $input['vessel_id'] = $input['vessel']['id'];
		/** @var Inventory $inventory */
        $inventory = $this->make(array_except($input, []));

        if ($this->find($inventory->name)) {
            throw new GeneralException(__('exceptions.backend.inventories.already_exist'));
        }

        DB::transaction(function () use ( $inventory, $input) {
            if (!$inventory->save()) {
                throw new GeneralException(__('exceptions.backend.inventories.create'));
            }

            return true;
        });

        return $inventory;
    }

    /**
     * @param Inventory $inventory
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Inventory
     */
    public function update(Inventory $inventory, array $input)
    {
        if (($existingInventory = $this->find($inventory->name))
          && $existingInventory->id !== $inventory->id
        ) {
            throw new GeneralException(__('exceptions.backend.inventories.already_exist'));
        }
        $input['vessel_id'] = $input['vessel']['id'];
		
        DB::transaction(function () use ( $inventory, $input) {
            if (!$inventory->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.inventories.update'));
            }

            return true;
        });

        return $inventory;
    }

    /**
     * @param Inventory $inventory
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Inventory $inventory)
    {
        if (! $inventory->delete()) {
            throw new GeneralException(__('exceptions.backend.inventories.delete'));
        }

        return true;
    }
}
