<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SendConfirmation;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Repositories\Contracts\AccountRepository;

/**
 * Class EloquentAccountRepository.
 */
class EloquentAccountRepository extends EloquentBaseRepository implements AccountRepository
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * EloquentUserRepository constructor.
     *
     * @param User                                       $user
     * @param \App\Repositories\Contracts\UserRepository $users
     *
     * @internal param \Illuminate\Contracts\Config\Repository $config
     */
    public function __construct(User $user, UserRepository $users)
    {
        parent::__construct($user);
        $this->users = $users;
    }

    /**
     * @param array $input
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return \App\Models\User
     */
    public function register(array $input)
    {
        $user = $this->users->store(Arr::only($input, ['name', 'email', 'password']));

        $this->sendConfirmationToUser($user);

        return $user;
    }

    /**
     * @param Authenticatable $user
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return \App\Models\User
     */
    public function login(Authenticatable $user)
    {
        /** @var User $user */
        $user->last_access_at = Carbon::now();

        if (! $user->save()) {
            throw new GeneralException(__('exceptions.backend.users.update'));
        }

        session(['permissions' => $user->getPermissions()]);

        return $user;
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param                                            $name
     *
     * @return bool
     */
    public function hasPermission(Authenticatable $user, $name)
    {
        /** @var User $user */
        // First user is always super admin and cannot be deleted
        if ($user->is_super_admin) {
            return true;
        }

        /** @var \Illuminate\Support\Collection $permissions */
        $permissions = session()->get('permissions');

        if ($permissions->isEmpty()) {
            return false;
        }

        return $permissions->contains($name);
    }

    /**
     * @param $input
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \App\Exceptions\GeneralException
     *
     * @return mixed
     */
    public function update(array $input)
    {
        if (! config('account.updating_enabled')) {
            throw new GeneralException(__('exceptions.frontend.user.updating_disabled'));
        }

        /** @var User $user */
        $user = auth()->user();

        $user->fill(Arr::only($input, ['name', 'email', 'locale', 'timezone']));

        if ($user->isDirty('email')) {
            $user->confirmed = false;
            $this->sendConfirmationToUser($user);
        }

        return $user->save();
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return mixed
     */
    public function changePassword($oldPassword, $newPassword)
    {
        if (! config('account.updating_enabled')) {
            throw new GeneralException(__('exceptions.frontend.user.updating_disabled'));
        }

        /** @var User $user */
        $user = auth()->user();

        if (empty($user->password) || Hash::check($oldPassword, $user->password)) {
            $user->password = Hash::make($newPassword);

            return $user->save();
        }

        throw new GeneralException(__('exceptions.frontend.user.password_mismatch'));
    }

    /**
     * Send mail confirmation.
     */
    public function sendConfirmation()
    {
        /** @var User $user */
        $user = auth()->user();

        $this->sendConfirmationToUser($user);
    }

    /**
     * @param \App\Models\User $user
     */
    private function sendConfirmationToUser(User $user)
    {
        $user->confirmation_token = Str::random(60);
        $user->save();

        $user->notify(new SendConfirmation($user->confirmation_token));
    }

    /**
     * Send mail confirmation.
     *
     * @param $token
     *
     * @return string|void
     */
    public function confirmEmail($token)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->confirmation_token === $token) {
            $user->confirmed = true;
            $user->save();
        }
    }

    /**
     * @throws \App\Exceptions\GeneralException|Exception
     *
     * @return mixed
     */
    public function delete()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->is_super_admin) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_destroyed'));
        }

        if (! $user->delete()) {
            throw new GeneralException(__('exceptions.frontend.user.delete_account'));
        }

        return true;
    }
}
