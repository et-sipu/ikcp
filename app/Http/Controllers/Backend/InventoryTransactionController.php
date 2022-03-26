<?php

namespace App\Http\Controllers\Backend;

use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\InventoryItemCategory;
use App\Models\InventoryTransaction;
use App\Transformers\InventoryTransactionTransformer;
use App\Transformers\RobInventroyTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreInventoryTransactionRequest;
use App\Http\Requests\UpdateInventoryTransactionRequest;
use App\Repositories\Contracts\InventoryTransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Fractal\Fractal;

class InventoryTransactionController extends BackendController
{

    /**
     * @var InventoryTransactionRepository
     */
    protected $inventory_transactions;

    /**
     * Create a new controller instance.
     *
     * @param InventoryTransactionRepository $inventory_transactions
     */
    public function __construct(InventoryTransactionRepository $inventory_transactions)
    {
        $this->inventory_transactions = $inventory_transactions;
    }

    public function export(Request $request)
    {
        $this->authorize('view inventory transactions');

        $query = $this->extractQuery($request);

        $requestSearchQuery = new RequestSearchQuery($request, $query);

        return $requestSearchQuery->export('inventory_transactions', new RobInventroyTransformer(true));
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
        $query = $this->inventory_transactions->query()->with([]);
        $query->join('inventories', 'inventories.id', '=', 'inventory_transactions.inventory_id');
        $query->join('vessels', 'vessels.id', '=', 'inventories.vessel_id');
        $query->join('inventory_items', 'inventory_items.id', '=', 'inventory_transactions.inventory_item_id');
		$query->select([\DB::raw('inventory_items.name as inventory_item_name, concat(inventories.name,\'@\',vessels.name) as inventory_name'), 'inventory_transactions.*']);

        if (! Gate::check('view inventory transactions')) {
            $vessel_id = auth()->user()->vessel ? auth()->user()->vessel->id : 0;
            $query->whereHas('inventory', function(Builder $q) use($vessel_id){
                $q->whereHas('vessel', function(Builder $q) use($vessel_id){
                    $q->whereId($vessel_id);
                });
            });
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new InventoryTransactionTransformer());
    }

    /**
     * @param InventoryTransaction $inventory_transaction
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(InventoryTransaction $inventory_transaction)
    {
         $this->authorize('view inventory transactions');

        return Fractal::create()->item($inventory_transaction, new InventoryTransactionTransformer());
    }

    /**
     * @param StoreInventoryTransactionRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreInventoryTransactionRequest $request)
    {
         $this->authorize('create inventory transactions');

        $this->inventory_transactions->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventory_transactions.created'));
    }

    /**
     * @param InventoryTransaction $inventory_transaction
     * @param UpdateInventoryTransactionRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InventoryTransaction $inventory_transaction, UpdateInventoryTransactionRequest $request)
    {
         $this->authorize('edit inventory transactions');

        $this->inventory_transactions->update($inventory_transaction, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.inventory_transactions.updated'));
    }

    /**
     * @param InventoryTransaction $inventory_transaction
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(InventoryTransaction $inventory_transaction, Request $request)
    {
         $this->authorize('delete inventory transactions');

        $this->inventory_transactions->destroy($inventory_transaction);

        return $this->redirectResponse($request, __('alerts.backend.inventory_transactions.deleted'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function report(Request $request){

        $transactions = InventoryTransaction::query()->with(['inventory','inventory_item']);
        $transactions->join('inventories', 'inventories.id', '=', 'inventory_transactions.inventory_id');
        $transactions->join('inventory_items', 'inventory_items.id', '=', 'inventory_transactions.inventory_item_id');
        $transactions->join('vessels', 'vessels.id', '=', 'inventories.vessel_id');

        $transactions->select(DB::raw('concat(inventories.name,\'@\',vessels.name) as store,inventory_items.name as item,inventory_id,inventory_item_id, SUM(quantity) as quantity'));
        $transactions->groupBy('inventory_id');
        $transactions->groupBy('inventory_item_id');
//        $transactions->groupBy('inventories.name');
        $transactions->groupBy(DB::raw('concat(inventories.name,\'@\',vessels.name)'));
        $transactions->groupBy('inventory_items.name');
        
        if (! Gate::check('view rob report')) {
            $vessel_id = auth()->user()->vessel ? auth()->user()->vessel->id : 0;
            $transactions->whereHas('inventory', function(Builder $q) use($vessel_id){
                $q->whereHas('vessel', function(Builder $q) use($vessel_id){
                    $q->whereId($vessel_id);
                });
            });
        }

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->inventory_selected) && \is_array($extraSearch->inventory_selected) && \count($extraSearch->inventory_selected) > 0) {
            $store_id = array_map(function ($value) {
                    return $value->id;
                }, $extraSearch->inventory_selected);
            $transactions->whereIn('inventory_id', $store_id);
        }
        if (isset($extraSearch->item_selected) && \is_array($extraSearch->item_selected) && \count($extraSearch->item_selected) > 0) {
            $item_id = array_map(function ($value) {
                    return $value->id;
                }, $extraSearch->item_selected);
            $transactions->whereIn('inventory_item_id', $item_id);
        }
        if (isset($extraSearch->category_selected)) {
            $category=InventoryItemCategory::find($extraSearch->category_selected);
            if($category->parent_id){
                    $transactions->where('category_id','=', $extraSearch->category_selected);
            }
            else{
                    $id= $extraSearch->category_selected;
                    $transactions->where('category_id','=', $extraSearch->category_selected);
                    $transactions->orwhereHas('inventory_item', function (Builder $q) use ($id) {
                        $q->whereHas('category', function (Builder $q) use ($id) {
                            return $q->where('parent_id','=',$id);
                        });
                    });
            }
        }

        $requestSearchQuery = new RequestSearchQuery($request, $transactions,[]);

        return $requestSearchQuery->result(new RobInventroyTransformer());

     }

    /**
     * @param InventoryItem $inventory_item
     * @param Inventory $store_id
     * @return array
     */
    public function item_transaction_chart (InventoryItem $inventory_item, Inventory $store_id){
        $items= InventoryTransaction::where('inventory_item_id','=',$inventory_item->id)
         ->where('inventory_id','=',$store_id->id)->get();
        $quantity=0;
        $item_transactions=[];
        foreach ($items as $item){
            $quantity=$quantity+$item->quantity;
            $date = $item->transaction_date ?: $item->created_at->format('Y-m-d');
            $item_transaction=['name'=> $item->inventory_item->name, 'sum'=> $quantity, 'date'=>$date, 'description' => sprintf('(%s quantity %s) %s',$item->transaction_type, $item->quantity, $item->note ) ];
            array_push($item_transactions,$item_transaction);
        }
        return $item_transactions;
     }

    public function extractQuery(Request $request): Builder
    {
        $query = InventoryTransaction::query()->with(['inventory','inventory_item']);
        $query->join('inventories', 'inventories.id', '=', 'inventory_transactions.inventory_id');
        $query->join('inventory_items', 'inventory_items.id', '=', 'inventory_transactions.inventory_item_id');
        $query->select(DB::raw('inventories.name as store,inventory_items.name as item,inventory_id,inventory_item_id, SUM(quantity) as quantity'));
        $query->groupBy('inventory_id');
        $query->groupBy('inventory_item_id');
        $query->groupBy('inventories.name');
        $query->groupBy('inventory_items.name');
        $extraSearch = \is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));

        if (isset($extraSearch->inventory_selected) && \is_array($extraSearch->inventory_selected) && \count($extraSearch->inventory_selected) > 0) {
            $store_id = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->inventory_selected);
            $query->whereIn('inventory_id', $store_id);
        }
        if (isset($extraSearch->item_selected) && \is_array($extraSearch->item_selected) && \count($extraSearch->item_selected) > 0) {
            $item_id = array_map(function ($value) {
                return $value->id;
            }, $extraSearch->item_selected);
            $query->whereIn('inventory_item_id', $item_id);
        }
        if (isset($extraSearch->category_selected)) {
            $category=InventoryItemCategory::find($extraSearch->category_selected);
            if($category->parent_id){
                $query->where('category_id','=', $extraSearch->category_selected);
            }
            else{
                $id= $extraSearch->category_selected;
                $query->where('category_id','=', $extraSearch->category_selected);
                $query->orwhereHas('inventory_item', function (Builder $q) use ($id) {
                    $q->whereHas('category', function (Builder $q) use ($id) {
                        return $q->where('parent_id','=',$id);
                    });
                });
            }
        }
        return $query;
    }
}