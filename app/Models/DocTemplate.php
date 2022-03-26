<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class DocTemplate extends Model implements HasMedia, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $fillable = [
		'template_type',
		'code',
		'title',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit doc templates');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete doc templates');
    }

    public function getTemplateUrlAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('template')->first()) {
            return config('app.url').$media->getUrl();
        }

        return null;
    }

    public function getTemplateFileNameAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('template')->first()) {
            return $media->name;
        }

        return null;
    }

    // Relations

    // Scopes

    // Methods
}