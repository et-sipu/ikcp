<?php

namespace App\Http\Controllers\Backend;

use App\Models\Designation;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use App\Transformers\DesignationTransformer;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Repositories\Contracts\DesignationRepository;

class DesignationController extends BackendController
{
    /**
     * @var DesignationRepository
     */
    protected $designations;

    /**
     * Create a new controller instance.
     *
     * @param DesignationRepository $designations
     */
    public function __construct(DesignationRepository $designations)
    {
        $this->designations = $designations;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array
     */
    public function search(Request $request)
    {
        $query = $this->designations->query()->with([]);
        $query->join('departments', 'departments.id', '=', 'designations.department_id');

        $query->select([DB::raw('departments.name as department_name'), 'designations.*']);

        $extraSearch = \is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));

        if (isset($extraSearch->department_id) && is_numeric($extraSearch->department_id)) {
            $query->where('department_id', $extraSearch->department_id);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['title', 'department.name']);

        return $requestSearchQuery->result(new DesignationTransformer());
    }

    /**
     * @param Designation $designation
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Designation $designation)
    {
        $this->authorize('view designations');

        return Fractal::create()->item($designation, new DesignationTransformer());
    }

    /**
     * @param StoreDesignationRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreDesignationRequest $request)
    {
        $this->authorize('create designations');

        $this->designations->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.designations.created'));
    }

    /**
     * @param Designation              $designation
     * @param UpdateDesignationRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Designation $designation, UpdateDesignationRequest $request)
    {
        $this->authorize('edit designations');

        $this->designations->update($designation, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.designations.updated'));
    }

    /**
     * @param Designation $designation
     * @param Request     $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Designation $designation, Request $request)
    {
        $this->authorize('delete designations');

        $this->designations->destroy($designation);

        return $this->redirectResponse($request, __('alerts.backend.designations.deleted'));
    }
}
