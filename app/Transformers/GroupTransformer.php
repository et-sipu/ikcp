<?php
namespace App\Transformers;

use App\Models\Group;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{

    public function transform(Group $group)
    {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'members' => $group->users->toArray(),
//            'members' => ['ahmad al khoudary'],
//            'members' => ['43' => 'asdasdasd', '54' => 'xcvxcvxc'],
            'can_edit' => $group->can_edit,
            'can_delete' => $group->can_delete,
        ];
    }
}