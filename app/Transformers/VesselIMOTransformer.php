<?php
/**
 * Created by PhpStorm.
 * User: ahmadof
 * Date: 04/07/2018
 * Time: 9:07.
 */

namespace App\Transformers;

use Spatie\MediaLibrary\Models\Media;
use League\Fractal\TransformerAbstract;

class VesselIMOTransformer extends TransformerAbstract
{
    public function transform(Media $imo)
    {
        return [
            'id'                => $imo->id,
            'name'              => $imo->name,
            'url'               => $imo->getFullUrl(),
            'date'              => (string) $imo->created_at,
        ];
    }
}
