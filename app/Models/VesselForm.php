<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class VesselForm extends Model implements HasMedia,Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $fillable = [
		'doc_template_id',
		'vessel_id',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit vessel forms');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete vessel forms');
    }

    public function getFormUrlAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('form')->first()) {
            return config('app.url').$media->getUrl();
        }

        return null;
    }

    public function getFormFileNameAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('form')->first()) {
            return $media->name;
        }

        return null;
    }

    // Relations
    public function doc_template()
    {
        return $this->belongsTo(DocTemplate::class);
    }

    // Scopes

    // Methods
}