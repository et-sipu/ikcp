<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Transformers\DepartmentTransformer;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Repositories\Contracts\DepartmentRepository;

class DepartmentController extends BackendController
{
    /**
     * @var DepartmentRepository
     */
    protected $departments;

    /**
     * Create a new controller instance.
     *
     * @param DepartmentRepository $departments
     */
    public function __construct(DepartmentRepository $departments)
    {
        $this->departments = $departments;
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
        $query = $this->departments->query()->with(['hod']);

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['name']);

        return $requestSearchQuery->result(new DepartmentTransformer());
    }

    /**
     * @param Department $department
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(Department $department)
    {
        // $this->authorize('view departments');

        return Fractal::create()->item($department, new DepartmentTransformer());
    }

    /**
     * @param StoreDepartmentRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreDepartmentRequest $request)
    {
        // $this->authorize('create departments');

        $this->departments->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.departments.created'));
    }

    /**
     * @param Department              $department
     * @param UpdateDepartmentRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Department $department, UpdateDepartmentRequest $request)
    {
        // $this->authorize('edit departments');

        $this->departments->update($department, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.departments.updated'));
    }

    /**
     * @param Department $department
     * @param Request    $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Department $department, Request $request)
    {
        // $this->authorize('delete departments');

        $this->departments->destroy($department);

        return $this->redirectResponse($request, __('alerts.backend.departments.deleted'));
    }
}
