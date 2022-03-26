<?php

namespace App\Models;

use App\Models\Traits\GetAttachmentsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class VesselInsurance extends Model implements HasMedia, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use GetAttachmentsTrait;
    use HasMediaTrait;



    protected $fillable = [
		'name',
		'type',
		'vessels',
		'expiry_date',
    ];
    protected $casts = [
        'vessels' => 'array'
    ];


    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return true;//Gate::check('edit vessel insurances');
    }

    public function getCanDeleteAttribute()
    {
        return true;//Gate::check('delete vessel insurances');
    }

    // Relations

    // Scopes

    // Methods
}