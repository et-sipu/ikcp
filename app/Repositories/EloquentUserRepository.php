<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Models\Comment;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use Illuminate\Support\Arr;
use App\Events\CommentAdded;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Hash;
use App\Models\Contracts\Commentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Config\Repository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;

/**
 * Class EloquentUserRepository.
 */
class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var RoleRepository
     */
    protected $roles;
    protected $comment;

    /**
     * EloquentUserRepository constructor.
     *
     * @param User                                       $user
     * @param \App\Repositories\Contracts\RoleRepository $roles
     * @param \Illuminate\Contracts\Config\Repository    $config
     */
    public function __construct(
        User $user,
        RoleRepository $roles,
        Repository $config,
        Comment $comment
    ) {
        parent::__construct($user);
        $this->roles = $roles;
        $this->config = $config;
        $this->comment = $comment;
    }

    /**
     * @param string $slug
     *
     * @return User
     */
    public function findBySlug($slug)
    {
        return $this->query()->whereSlug($slug)->first();
    }

    /**
     * @param array $input
     * @param bool  $confirmed
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return \App\Models\User
     */
    public function store(array $input, $confirmed = false)
    {
        /** @var User $user */
        if (isset($input['department']) && isset($input['department']['id'])) {
            $input['department_id'] = $input['department']['id'];
        } else {
            $input['department_id'] = null;
        }

        $user = $this->make(Arr::only($input, ['name', 'email', 'active', 'department_id']));

        if (isset($input['password'])) {
            $user->password = Hash::make($input['password']);
        }
        $user->confirmed = $confirmed;

        if (empty($user->locale)) {
            $user->locale = 'en';
        }

        if (empty($user->timezone)) {
            $user->timezone = $this->config->get('app.timezone');
        }

        if (! $this->save($user, $input)) {
            throw new GeneralException(__('exceptions.backend.users.create'));
        }

        event(new UserCreated($user));

        return $user;
    }

    /**
     * @param User  $user
     * @param array $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\User
     */
    public function update(User $user, array $input)
    {
        if (isset($input['department']) && isset($input['department']['id'])) {
            $input['department_id'] = $input['department']['id'];
        } else {
            $input['department_id'] = null;
        }

        if (! $user->can_edit) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_edited'));
        }

        $user->fill(Arr::except($input, 'password'));

        if ($user->is_super_admin && ! $user->active) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_disabled'));
        }

        if (! $this->save($user, $input)) {
            throw new GeneralException(__('exceptions.backend.users.update'));
        }

        event(new UserUpdated($user));

        return $user;
    }

    /**
     * @param array $input
     * @param Commentable $commentable
     *
     * @return mixed
     */
    public function addComment(array $input, Commentable $commentable)
    {
        /** @var User $user */
        $user = auth()->user();

        $comment = $user->comment($commentable, $input['comment']);

        event(new CommentAdded($comment));

        return $comment;
    }

    /**
     * @param \App\Models\User $user
     * @param array            $input
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return bool
     */
    private function save(User $user, array $input)
    {
        if (isset($input['password']) && ! empty($input['password'])) {
            $user->password = Hash::make($input['password']);
        }

        if (! $user->save()) {
            return false;
        }

        if (isset($input['roles'])) {
            $roles = $input['roles'];

            if (! empty($roles) && (! isset($input['force']) || ! $input['force'])) {
                $allowedRoles = $this->roles->getAllowedRoles()->keyBy('id');

                foreach ($roles as $id) {
                    if (! $allowedRoles->has($id)) {
                        throw new GeneralException(__('exceptions.backend.users.cannot_set_superior_roles'));
                    }
                }
            }

            $user->roles()->sync($roles);
        }

        return true;
    }

    /**
     * @param User $user
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(User $user)
    {
        if (! $user->can_delete) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_destroyed'));
        }

        if (! $user->delete()) {
            throw new GeneralException(__('exceptions.backend.users.delete'));
        }

        //event(new UserDeleted($user));

        return true;
    }

    /**
     * @param User $user
     *
     * @throws Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(User $user)
    {
        if ($user->is_super_admin) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_impersonated'));
        }

        $authenticatedUser = auth()->user();

        if ($authenticatedUser->id === $user->id
            || session()->get('admin_user_id') === $user->id
        ) {
            return redirect()->route('home');
        }

        if (! session()->get('admin_user_id')) {
            session(['admin_user_id' => $authenticatedUser->id]);
            session(['admin_user_name' => $authenticatedUser->name]);
            session(['temp_user_id' => $user->id]);
        }

        //Login user
        auth()->loginUsingId($user->id);

        return redirect(home_route());
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchDestroy(array $ids)
    {
        DB::transaction(function () use ($ids) {
            // This wont call eloquent events, change to destroy if needed
            if ($this->query()->whereIn('id', $ids)->delete()) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.users.delete'));
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchEnable(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)
                ->update(['active' => true])
            ) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.users.update'));
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchDisable(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)
                ->update(['active' => false])
            ) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.users.update'));
        });

        return true;
    }
}
