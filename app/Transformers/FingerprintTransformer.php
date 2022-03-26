<?php

namespace App\Transformers;

use App\Models\Fingerprint;
use League\Fractal\TransformerAbstract;

class FingerprintTransformer extends TransformerAbstract
{
    public function transform(Fingerprint $fingerprint)
    {
        return [
            'id'          => $fingerprint->id,
            'staff_id'    => $fingerprint->staff_id,
            'staff_name'  => $fingerprint->staff_name,
            'clocking'    => $fingerprint->clocking,
            'terminal'    => $fingerprint->terminal,
            'branch_name' => $fingerprint->branch_name,
        ];
    }
}
