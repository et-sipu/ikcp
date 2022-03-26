<?php

namespace App\Http\Controllers\Backend;

use App\Models\InventoryItemCategory;
use App\Transformers\InventoryItemCategoryTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreInventoryItemCategoryRequest;
use App\Http\Requests\UpdateInventoryItemCategoryRequest;
use App\Repositories\Contracts\InventoryItemCategoryRepository;
use Illuminate\Support\Facades\DB;
use Spatie\Fractal\Fractal;

class InventoryItemCategoryController extends BackendController
{

    /**
     * @var InventoryItemCategoryRepository
     */
    protected $inventory_item_categories;

    /**
     * Create a new controller instance.
     *
     * @param InventoryItemCategoryRepository $inventory_item_categories
     */
    public function __construct(InventoryItemCategoryRepository $inventory_item_categories)
    {
        $this->inventory_item_categories = $inventory_item_categories;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     *
     */
    public function search(Request $request)
    {
        $query = $this->inventory_item_categories->query()->with([]);
        $query->leftJoin('inventory_item_categories as parent', 'parent.id', '=', 'inventory_item_categories.parent_id');
        $query->select([DB::raw('parent.name as parent_name'), 'inventory_item_categories.*']);

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new InventoryItemCategoryTransformer());
    }

    public function getAsTree(Request $request)
    {
        return $this->inventory_item_categories->get_categories_as_tree();
    }

    /**
     * @param InventoryItemCategory $inventory_item_category
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(InventoryItemCategory $inventory_item_category)
    {
        $this->authorize('view inventory item categories');

        return Fractal::create()->item($inventory_item_category, new InventoryItemCategoryTransformer());
    }

    /**
     * @param StoreInventoryItemCategoryRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreInventoryItemCategoryRequest $request)
    {
        $this->authorize('create inventory item categories');

        $this->inventory_item_categories->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventory_item_categories.created'));
    }

    /**
     * @param InventoryItemCategory $inventory_item_category
     * @param UpdateInventoryItemCategoryRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InventoryItemCategory $inventory_item_category, UpdateInventoryItemCategoryRequest $request)
    {
        $this->authorize('edit inventory item categories');

        $this->inventory_item_categories->update($inventory_item_category, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventory_item_categories.updated'));
    }

    /**
     * @param InventoryItemCategory $inventory_item_category
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(InventoryItemCategory $inventory_item_category, Request $request)
    {
        $this->authorize('delete inventory item categories');

        $this->inventory_item_categories->destroy($inventory_item_category);

        return $this->redirectResponse($request, __('alerts.backend.inventory_item_categories.deleted'));
    }
}