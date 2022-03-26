<?php

namespace App\Transformers;

use App\Models\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Permission $permission
     *
     * @return array
     */
    public function transform(Permission $permission)
    {
        return [
            'id'   => $permission->id,
            'name' => $permission->name,
        ];
    }
}
