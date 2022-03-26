<?php

namespace App\Transformers;

use OwenIt\Auditing\Models\Audit;
use League\Fractal\TransformerAbstract;

class AuditTransformer extends TransformerAbstract
{
    public function transform(Audit $audit)
    {
        return [
            'id'             => $audit->id,
            'user_type'      => $audit->user_type,
            'user_id'        => $audit->user_id,
            'user_name'      => $audit->user ? $audit->user->name : '',
            'event'          => $audit->event,
            'auditable_type' => $audit->auditable_type,
            'auditable_id'   => $audit->auditable_id,
            'old_values'     => $audit->old_values,
            'new_values'     => $audit->new_values,
            'url'            => $audit->url,
            'ip_address'     => $audit->ip_address,
            'user_agent'     => $audit->user_agent,
            'created_at'     => (string) $audit->created_at,
            'updated_at'     => (string) $audit->updated_at,
        ];
    }
}
