<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'roles',
    ];

    /**
     * A Fractal transformer.
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'active'          => (bool) $user->active,
            'confirmed'       => (bool) $user->confirmed,
            'last_access_at'  => (string) $user->last_access_at,
            'created_at'      => (string) $user->created_at,
            'updated_at'      => (string) $user->updated_at,
            'avatar'          => $user->avatar,
            'can_edit'        => (bool) $user->can_edit,
            'can_delete'      => (bool) $user->can_delete,
            'can_impersonate' => (bool) $user->can_impersonate,
            'is_chargeable'   => (bool) $user->is_chargeable,
            'balance'         => $user->balance,
            'department'      => $user->department ? [
                'id'   => $user->department_id,
                'name' => $user->department->name,
            ] : null,
            'user' => [
                'id'      => $user->id,
                'name'    => $user->name,
                'balance' => $user->balance,
            ],
        ];
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles;

        return $this->collection($roles, new RoleTransformer());
    }
}
