<?php

namespace App\Repositories;

use Exception;
use App\Models\InventoryItemCategory;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\InventoryItemCategoryRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentInventoryItemCategoryRepository.
 */
class EloquentInventoryItemCategoryRepository extends EloquentBaseRepository implements InventoryItemCategoryRepository
{
    /**
     * EloquentInventoryItemCategoryRepository constructor.
     *
     * @param InventoryItemCategory $inventory_item_category
     */
    public function __construct(InventoryItemCategory $inventory_item_category)
    {
        parent::__construct($inventory_item_category);
    }

    /**
     * @param $name
     *
     * @return InventoryItemCategory
     */
    public function find($name)
    {
        /* @var InventoryItemCategory $inventory_item_category */
        return false;//$this->query()->whereName($name)->first();
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\InventoryItemCategory
     */
    public function store(array $input)
    {
        /** @var InventoryItemCategory $inventory_item_category */
        $input['parent_id'] = $input['parent'];
        $inventory_item_category = $this->make(array_except($input, []));

        if ($this->find($inventory_item_category->name)) {
            throw new GeneralException(__('exceptions.backend.inventory_item_categories.already_exist'));
        }

        DB::transaction(function () use ( $inventory_item_category, $input) {
            if (!$inventory_item_category->save()) {
                throw new GeneralException(__('exceptions.backend.inventory_item_categories.create'));
            }

            return true;
        });

        return $inventory_item_category;
    }

    /**
     * @param InventoryItemCategory $inventory_item_category
     * @param array       $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\InventoryItemCategory
     */
    public function update(InventoryItemCategory $inventory_item_category, array $input)
    {
        if($inventory_item_category->id == $input['parent']) {
            throw new GeneralException(__('exceptions.backend.inventory_item_categories.id_same_parent'));
        }

        if (($existingInventoryItemCategory = $this->find($inventory_item_category->name))
          && $existingInventoryItemCategory->id !== $inventory_item_category->id
        ) {
            throw new GeneralException(__('exceptions.backend.inventory_item_categories.already_exist'));
        }

        $input['parent_id'] = $input['parent'];

        DB::transaction(function () use ( $inventory_item_category, $input) {
            if (!$inventory_item_category->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.inventory_item_categories.update'));
            }

            return true;
        });

        return $inventory_item_category;
    }

    /**
     * @param InventoryItemCategory $inventory_item_category
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(InventoryItemCategory $inventory_item_category)
    {
        if (! $inventory_item_category->delete()) {
            throw new GeneralException(__('exceptions.backend.inventory_item_categories.delete'));
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function get_categories_as_tree()
    {
        $results = [];
        $main_categories = $this->query()->whereNull('parent_id')->get();

        $main_categories->each(function($category) use(&$results){
            array_push($results, $this->get_category_children($category));
        });

        return $results;
    }

    public function get_category_children(InventoryItemCategory $category)
    {
        if($category->children->count() == 0) return ['id' => $category->id, 'label' => $category->name];

        $children = [];
        $category->children->each(function($c) use(&$children){
            array_push($children, $this->get_category_children($c));
        });

        return ['id' => $category->id, 'label' => $category->name, 'children' => $children];
    }

}
