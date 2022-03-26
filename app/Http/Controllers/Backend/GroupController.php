<?php

namespace App\Http\Controllers\Backend;

use App\Models\Group;
use App\Transformers\GroupTransformer;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Repositories\Contracts\GroupRepository;
use Spatie\Fractal\Fractal;

class GroupController extends BackendController
{

    /**
     * @var GroupRepository
     */
    protected $groups;

    /**
     * Create a new controller instance.
     *
     * @param GroupRepository $groups
     */
    public function __construct(GroupRepository $groups)
    {
        $this->groups = $groups;
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
        $query = $this->groups->query()->with([]);

        $requestSearchQuery = new RequestSearchQuery($request, $query,[]);

        return $requestSearchQuery->result(new GroupTransformer());
    }

    /**
     * @param Group $group
     *
     * @return \Spatie\Fractalistic\Fractal
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Group $group)
    {
        // $this->authorize('view groups');

        return Fractal::create()->item($group, new GroupTransformer());
    }

    /**
     * @param StoreGroupRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreGroupRequest $request)
    {
        // $this->authorize('create groups');

        $this->groups->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.groups.created'));
    }

    /**
     * @param Group $group
     * @param UpdateGroupRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Group $group, UpdateGroupRequest $request)
    {
        // $this->authorize('edit groups');

        $this->groups->update($group, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.groups.updated'));
    }

    /**
     * @param Group $group
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Group $group, Request $request)
    {
        // $this->authorize('delete groups');

        $this->groups->destroy($group);

        return $this->redirectResponse($request, __('alerts.backend.groups.deleted'));
    }
}