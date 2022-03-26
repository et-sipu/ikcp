<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can update seafarer contract.
     *
     * @param User     $authenticatedUser
     * @param Employee $employee
     *
     * @return bool
     */
    public function edit(User $authenticatedUser, Employee $employee)
    {
        if ($authenticatedUser->can('edit employees') || ! $employee->user) {
            return true;
        }

        if ($authenticatedUser->can('edit own employees')) {
            return $authenticatedUser->id === $employee->user->id;
        }

        return false;
    }
}
