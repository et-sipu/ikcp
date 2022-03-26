<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class EmployeeQualificationInfo extends Model implements Auditable, HasMedia
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $fillable = [
        'authority',
        'degree',
        'year',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'academic_qualifications'     => 'array',
        'professional_qualifications' => 'array',
    ];

    protected $appends = ['degree_photocopy_path'];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (self $qualification) {
            $qualification->media()->delete();
        });
    }

    public function getDegreePhotocopyPathAttribute()
    {
        $photocopy = $this->getMedia('photocopy')->first();

        return isset($photocopy) ? config('app.url').$photocopy->getUrl() : null;
    }
}
