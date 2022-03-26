<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class DocAudit extends Model implements HasMedia, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $fillable = [
		'audit_date',
		'nc',
		'obs',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit doc audits');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete doc audits');
    }

    public function getAuditFileUrlAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('audit_file')->first()) {
            return config('app.url').$media->getUrl();
        }

        return null;
    }

    public function getAuditFileNameAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('audit_file')->first()) {
            return $media->name;
        }

        return null;
    }

    // Relations

    // Scopes

    // Methods
}