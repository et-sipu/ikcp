<?php

namespace App\Http\Controllers\Backend;

use App\Models\InventoryItem;
use App\Transformers\InventoryItemTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreInventoryItemRequest;
use App\Http\Requests\UpdateInventoryItemRequest;
use App\Repositories\Contracts\InventoryItemRepository;
use Illuminate\Support\Facades\DB;
use Spatie\Fractal\Fractal;

class InventoryItemController extends BackendController
{

    /**
     * @var InventoryItemRepository
     */
    protected $inventory_items;

    /**
     * Create a new controller instance.
     *
     * @param InventoryItemRepository $inventory_items
     */
    public function __construct(InventoryItemRepository $inventory_items)
    {
        $this->inventory_items = $inventory_items;
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
        $query = $this->inventory_items->query()->with([]);
        $query->join('inventory_item_categories', 'inventory_item_categories.id', '=', 'inventory_items.category_id');
        $query->select([DB::raw('inventory_item_categories.name as category_name'), 'inventory_items.*']);

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new InventoryItemTransformer());
    }

    /**
     * @param InventoryItem $inventory_item
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(InventoryItem $inventory_item)
    {
        $this->authorize('view inventory items');

        return Fractal::create()->item($inventory_item, new InventoryItemTransformer());
    }

    /**
     * @param Request $request
     * @param InventoryItem $inventory_item
     * @return mixed
     */
    public function getVariants(InventoryItem $inventory_item)
    {
        return $inventory_item->variants ?: [];
    }

    /**
     * @param StoreInventoryItemRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreInventoryItemRequest $request)
    {
        $this->authorize('create inventory items');

        $this->inventory_items->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventory_items.created'));
    }

    /**
     * @param InventoryItem $inventory_item
     * @param UpdateInventoryItemRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InventoryItem $inventory_item, UpdateInventoryItemRequest $request)
    {
        $this->authorize('edit inventory items');

        $this->inventory_items->update($inventory_item, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventory_items.updated'));
    }

    /**
     * @param InventoryItem $inventory_item
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(InventoryItem $inventory_item, Request $request)
    {
        $this->authorize('delete inventory items');

        $this->inventory_items->destroy($inventory_item);

        return $this->redirectResponse($request, __('alerts.backend.inventory_items.deleted'));
    }
}