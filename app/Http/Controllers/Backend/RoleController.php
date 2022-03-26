<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Transformers\RoleTransformer;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\Contracts\RoleRepository;

class RoleController extends BackendController
{
    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * Create a new controller instance.
     *
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        $query = $this->roles->query();

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->permission)) {
            $permission = $extraSearch->permission;
            $query->whereHas('permissions', function ($q) use ($permission) {
                $q->whereName($permission->id);
            });
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['name', 'display_name', 'description']);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'name',
                'order',
                'display_name',
                'description',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.name'),
                    __('validation.attributes.order'),
                    __('validation.attributes.display_name'),
                    __('validation.attributes.description'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'roles');
        }

        return $requestSearchQuery->result(new RoleTransformer());
    }

    /**
     * @param Role $role
     *
     * @return Role
     */
    public function show(Role $role)
    {
        return Fractal::create()->item($role, new RoleTransformer())
            ->includePermissions()
            ->toArray();

        return $role;
    }

    public function getPermissions()
    {
        $permissions = config('permissions');
        $roles = Role::join('permissions', 'permissions.role_id', '=', 'roles.id')
            ->select(['permissions.name', 'roles.display_name'])->get()
            ->transform(function ($item) { return ['role_name' => $item->display_name, 'permission_name' => $item->name]; })
            ->groupBy('permission_name')->toArray();
        foreach (array_keys($permissions) as $permission) {
            $permissions[$permission]['roles'] = isset($roles[$permission]) ? $roles[$permission] : [];
        }

        return $permissions;
    }

    /**
     * @param StoreRoleRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create roles');

        $this->roles->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.roles.created'));
    }

    /**
     * @param Role              $role
     * @param UpdateRoleRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(Role $role, UpdateRoleRequest $request)
    {
        $this->authorize('edit roles');

        $this->roles->update($role, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.roles.updated'));
    }

    /**
     * @param Role    $role
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(Role $role, Request $request)
    {
        $this->authorize('delete roles');

        $this->roles->destroy($role);

        return $this->redirectResponse($request, __('alerts.backend.roles.deleted'));
    }
}
