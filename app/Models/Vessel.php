<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Vessel extends Model implements Auditable, HasMedia
{
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $fillable = [
        'name',
        'code',
        'imo_number',
        'call_sign',
        'official_number',
        'port_of_registry_id',
        'flag',
        'piloted_by',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Attributes
    public function getCanEditAttribute()
    {
        return Gate::check('edit', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete vessels');
    }

    public function getStampPathAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('stamp')->first()) {
            return config('app.url').$media->getUrl();
        }

        return '';
    }

    public function getVesselSpecsPathAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('vessel_specs')->first()) {
            return config('app.url').$media->getUrl();
        }

        return '';
    }

    public function getCertificatesPathsAttribute()
    {
        $paths = [];

        foreach ($this->documents as $certificate) {
            if ($media = $this->getMedia($certificate->document_type)->first()) {
                $paths[$certificate->document_type] = config('app.url').$media->getUrl();
            }
        }

        return $paths;
    }

    public function getGaPlansAttribute()
    {
        $attachments = [];

        $medias = $this->getMedia('GA_Plans')->all();

        foreach ($medias as $media) {
            array_push($attachments,
                [
                    'id'   => isset($media) ? $media->id : 0,
                    'file' => null,
                    'url'  => isset($media) ? config('app.url').$media->getUrl() : null,
                ]);
        }

        return $attachments;
    }

    // Relations
    public function port_of_registry()
    {
        return $this->belongsTo(Port::class);
    }

    public function pilot()
    {
        return $this->belongsTo(User::class, 'piloted_by');
    }

    public function ongoing_contracts()
    {
        return $this->hasMany(SeafarerContract::class)->whereStatus(SeafarerContract::$approved);
    }

    public function onboard_seafarers()
    {
        return $this->belongsToMany(Seafarer::class, 'seafarer_contracts')
                ->as('contract')
                ->wherePivot('deleted_at', null)
                ->wherePivot('sign_on_date', '!=', null)
                ->wherePivot('status', SeafarerContract::$approved);
    }

    public function current_master()
    {
        return $this->onboard_seafarers()->whereHas('capabilitiesInfo',function ($q) {
            $q->whereHas('rank', function ($q) {
                $q->where('name', 'Master');
            });
        })->first();
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
