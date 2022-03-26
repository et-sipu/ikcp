<?php

namespace App\Transformers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param DatabaseNotification $notification
     *
     * @return array
     */
    public function transform(DatabaseNotification $notification)
    {
        return [
            'id'              => $notification->id,
            'read'            => (bool) $notification->read_at,
            'created_at'      => $notification->created_at->format('Y-m-d') == Carbon::now()->format('Y-m-d') ? $notification->created_at->format('h:i A') : $notification->created_at->format('l M d, h:i A'),
            'type'            => (string) $notification->type,
            'data'            => $notification->data,
//            'email'           => $user->email,
//            'active'          => (bool) $user->active,
//            'confirmed'       => (bool) $user->confirmed,
//            'last_access_at'  => (string) $user->last_access_at,
//            'created_at'      => (string) $user->created_at,
//            'updated_at'      => (string) $user->updated_at,
//            'avatar'          => $user->avatar,
//            'can_edit'        => (bool) $user->can_edit,
//            'can_delete'      => (bool) $user->can_delete,
//            'can_impersonate' => (bool) $user->can_impersonate,
//            'is_chargeable'   => (bool) $user->is_chargeable,
//            'balance'         => $user->balance,
//            'department'      => $user->department ? [
//                'id'   => $user->department_id,
//                'name' => $user->department->name,
//            ] : null,
//            'user' => [
//                'id'      => $user->id,
//                'name'    => $user->name,
//                'balance' => $user->balance,
//            ],
        ];
    }
}
