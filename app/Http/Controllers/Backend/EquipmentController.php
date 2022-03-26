<?php

namespace App\Http\Controllers\Backend;

use App\Models\Equipment;
use App\Models\SeafarerContract;
use App\Transformers\EquipmentTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Repositories\Contracts\EquipmentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Fractal\Fractal;

class EquipmentController extends BackendController
{

    /**
     * @var EquipmentRepository
     */
    protected $equipment;
    protected $repo_name = 'equipment';

    /**
     * Create a new controller instance.
     *
     * @param EquipmentRepository $equipment
     */
    public function __construct(EquipmentRepository $equipment)
    {
        $this->equipment = $equipment;
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
        $query = $this->equipment->query()->with(['vessel']);
        $query->join('vessels', 'vessels.id', '=', 'equipment.vessel_id');
        $query->select([\DB::raw('vessels.name as vessel_name'), 'equipment.*']);

        if (! Gate::check('view equipment')) {
            $query->whereVesselId(auth()->user()->vessel ? auth()->user()->vessel->id : 0);
        }
        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new EquipmentTransformer());
    }

    /**
     * @param Equipment $equipment
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Equipment $equipment)
    {
         $this->authorize('view', $equipment);

        return Fractal::create()->item($equipment, new EquipmentTransformer());
    }

    /**
     * @param StoreEquipmentRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreEquipmentRequest $request)
    {
         $this->authorize('create equipment');

        $this->equipment->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.equipment.created'));
    }

    /**
     * @param Equipment $equipment
     * @param UpdateEquipmentRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Equipment $equipment, UpdateEquipmentRequest $request)
    {
         $this->authorize('edit', $equipment);

        $this->equipment->update($equipment, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.equipment.updated'));
    }

    /**
     * @param Equipment $equipment
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Equipment $equipment, Request $request)
    {
         $this->authorize('delete', $equipment);

        $this->equipment->destroy($equipment);

        return $this->redirectResponse($request, __('alerts.backend.equipment.deleted'));
    }

    /**
     * @return array
     */
    public function brands()
    {
        $equipment = DB::table('equipment')->distinct()->get(['brand']);

        $brands=  array_pluck($equipment,'brand');

        return $brands;
    }
}