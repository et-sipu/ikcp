<?php

namespace App\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['permissions'];

    /**
     * A Fractal transformer.
     *
     * @param Role $role
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id'           => $role->id,
            'name'         => $role->name,
            'display_name' => $role->display_name,
            'order'        => $role->order,
            'description'  => $role->description,
            'can_edit'     => (bool) $role->can_edit,
            'can_delete'   => (bool) $role->can_delete,
            'updated_at'   => (string) $role->updated_at,
            'created_at'   => (string) $role->created_at,
        ];
    }

    public function includePermissions(Role $role)
    {
        $permissions = $role->permissions()->get();

        return $this->collection($permissions, new PermissionTransformer());
    }
}
