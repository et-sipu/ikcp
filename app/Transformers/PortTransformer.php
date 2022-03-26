<?php
/**
 * Created by PhpStorm.
 * User: ahmadof
 * Date: 04/07/2018
 * Time: 9:07.
 */

namespace App\Transformers;

use App\Models\Port;
use League\Fractal\TransformerAbstract;

class PortTransformer extends TransformerAbstract
{
    public function transform(Port $port)
    {
        return [
            'id'                => $port->id,
            'name'              => $port->name,
            'country'           => $port->country,
            'created_at'        => (string) $port->created_at,
            'updated_at'        => (string) $port->updated_at,

            'can_edit'            => $port->can_edit,
            'can_delete'          => $port->can_delete,
        ];
    }
}
