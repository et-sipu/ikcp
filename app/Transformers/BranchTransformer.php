<?php

namespace App\Transformers;

use App\Models\Branch;
use League\Fractal\TransformerAbstract;

class BranchTransformer extends TransformerAbstract
{
    public function transform(Branch $branch)
    {
        return [
            'id'         => $branch->id,
            'name'       => $branch->name,
            'can_edit'   => $branch->can_edit,
            'can_delete' => $branch->can_delete,
        ];
    }
}
