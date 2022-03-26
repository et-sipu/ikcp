<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class CompanyRegistrations extends Model implements HasMedia, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $fillable = [
		'registration',
		'registration_no',
		'company',
		'type',
		'grade',
		'validity_from',
		'validity_to',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit company registrations');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete company registrations');
    }

    public function getRegistrationFileUrlAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('registration_file')->first()) {
            return config('app.url').$media->getUrl();
        }

        return null;
    }

    public function getRegistrationFileNameAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('registration_file')->first()) {
            return $media->name;
        }

        return null;
    }

    // Relations

    // Scopes

    // Methods
}