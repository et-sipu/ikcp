<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Transformers\NotificationTransformer;
use Illuminate\Notifications\DatabaseNotification;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Http\Requests\UpdateUserProfileRequest;

class UserController extends BackendController
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository                             $users
     * @param \App\Repositories\Contracts\RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function getActiveUserCounter()
    {
        return $this->users->query()->whereActive(true)->count();
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
        $query = $this->users->query()->with(['roles.permissions', 'department']);
        $query->leftJoin('departments', 'departments.id', '=', 'users.department_id');
        $query->select([DB::raw('departments.name as department_name'), 'users.*']);

        $extraSearch = json_decode($request->get('extraSearch'));
        if (isset($extraSearch->permission_selected) && \count($extraSearch->permission_selected) > 0) {
            $permission = $extraSearch->permission_selected[0];
            $query->whereHas('roles', function ($q) use ($permission) {
                $q->whereHas('permissions', function ($q) use ($permission) {
                    $q->whereName($permission->id);
                });
            });
        }

        if (isset($extraSearch->role_selected) && \is_array($extraSearch->role_selected) && \count($extraSearch->role_selected) > 0) {
            $role = array_pluck($extraSearch->role_selected, 'id');

            $query->whereHas('roles', function ($q) use ($role) {
                $q->whereIn('id', $role);
            });
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, [
            '`users.name`',
            '`departments.name`',
            'roles.name',
            'email',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'name',
                'email',
                'active',
                'confirmed',
                'last_access_at',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.name'),
                    __('validation.attributes.email'),
                    __('validation.attributes.active'),
                    __('validation.attributes.confirmed'),
                    __('labels.last_access_at'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'users');
        }

        return $requestSearchQuery->result(new UserTransformer());
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function show(User $user)
    {
        if (! $user->can_edit) {
            // Only Super admin can access himself
            abort(403);
        }

        return Fractal::create()->item($user, new UserTransformer())
            ->toArray();
    }

    public function getRoles()
    {
        return $this->roles->getAllowedRoles();
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create users');

        $this->users->store($request->input(), true);

        return $this->redirectResponse($request, __('alerts.backend.users.created'));
    }

    /**
     * @param User              $user
     * @param UpdateUserRequest $request
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize('edit users');

        if (! $request->get('roles')) {
            $request->merge(['roles' => []]);
        }
        $this->users->update($user, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.users.updated'));
    }

    /**
     * @param UpdateUserProfileRequest|UpdateUserRequest|Request $request
     *
     * @return mixed
     */
    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $this->users->update(auth()->user(), $request->input());

        return $this->redirectResponse($request, __('alerts.backend.users.profile_updated'));
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(User $user, Request $request)
    {
        $this->authorize('delete users');

        $this->users->destroy($user);

        return $this->redirectResponse($request, __('alerts.backend.users.deleted'));
    }

    /**
     * @param User $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function impersonate(User $user)
    {
        $this->authorize('impersonate users');

        return $this->users->impersonate($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function batchAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids');

        switch ($action) {
            case 'destroy':
                $this->authorize('delete users');

                $this->users->batchDestroy($ids);

                return $this->redirectResponse($request, __('alerts.backend.users.bulk_destroyed'));
                break;
            case 'enable':
                $this->authorize('edit users');

                $this->users->batchEnable($ids);

                return $this->redirectResponse($request, __('alerts.backend.users.bulk_enabled'));
                break;
            case 'disable':
                $this->authorize('edit users');

                $this->users->batchDisable($ids);

                return $this->redirectResponse($request, __('alerts.backend.users.bulk_disabled'));
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activeToggle(Request $request, User $user)
    {
        $this->authorize('edit users');
        $user->update(['active' => ! $user->active]);

        return $this->redirectResponse($request, __('alerts.backend.users.status_updated'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getPermission(Request $request)
    {
        $search = $request->get('search');
        $data = Config::get('permissions');
        $permissions = [];
        foreach ($data as $key => $datum) {
            if (preg_match('/'.$search.'/i', __($datum['display_name']))) {
                array_push($permissions, ['id' => $key, 'name' => __($datum['display_name'])]);
            }
        }

        return $permissions;
    }
}
