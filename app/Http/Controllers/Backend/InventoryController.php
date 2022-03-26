<?php

namespace App\Http\Controllers\Backend;

use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Repositories\Contracts\InventoryTransactionRepository;
use App\Transformers\InventoryTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Repositories\Contracts\InventoryRepository;
use Illuminate\Support\Facades\Gate;
use Spatie\Fractal\Fractal;

class InventoryController extends BackendController
{

    /**
     * @var InventoryRepository
     */
    protected $inventories;

    /**
     * Create a new controller instance.
     *
     * @param InventoryRepository $inventories
     */
    public function __construct(InventoryRepository $inventories)
    {
        $this->inventories = $inventories;
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
        $query = $this->inventories->query()->with([]);
        $query->join('vessels', 'vessels.id', '=', 'inventories.vessel_id');
		$query->select([\DB::raw('vessels.name as vessel_name'), 'inventories.*']);

        if (! Gate::check('view inventories')) {
            $vessel_id = auth()->user()->vessel ? auth()->user()->vessel->id : 0;
            $query->where('vessel_id', $vessel_id);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new InventoryTransformer());
    }

    /**
     * @param Inventory $inventory
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Inventory $inventory)
    {
         $this->authorize('view', $inventory);

        return Fractal::create()->item($inventory, new InventoryTransformer());
    }

    /**
     * @param Request $request
     * @param Inventory $inventory
     * @param InventoryItem $inventory_item
     * @return mixed
     */
    public function checkQuantity(Request $request, Inventory $inventory, InventoryItem $inventory_item)
    {
        $inventory_transactions = app()->make(InventoryTransactionRepository::class);

        return $inventory_transactions->check_quantity($inventory_item->id, $inventory->id, $request->get('variations') ?: []);
    }

    /**
     * @param StoreInventoryRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreInventoryRequest $request)
    {
         $this->authorize('create inventories');

        $this->inventories->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventories.created'));
    }

    /**
     * @param Inventory $inventory
     * @param UpdateInventoryRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Inventory $inventory, UpdateInventoryRequest $request)
    {
         $this->authorize('edit', $inventory);

        $this->inventories->update($inventory, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventories.updated'));
    }

    /**
     * @param Inventory $inventory
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Inventory $inventory, Request $request)
    {
         $this->authorize('delete', $inventory);

        $this->inventories->destroy($inventory);

        return $this->redirectResponse($request, __('alerts.backend.inventories.deleted'));
    }
}