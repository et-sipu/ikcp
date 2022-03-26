<?php

namespace App\Models;

use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        self::deleting(function (self $document) {
            if ($document->media) {
                $document->media->delete();
            }
        });
    }

    /**
     * Get all of the owning documentable models.
     */
    public function documentable()
    {
        return $this->morphTo();
    }

    public function scopeIsPassport(Builder $query)
    {
        return $query->where('documentable_type', 'Seafarer')->where('document_type', 'PASSPORT');
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'model_id', 'documentable_id')->where('model_type', $this->documentable_type)->where('collection_name', $this->document_type);
    }
}
