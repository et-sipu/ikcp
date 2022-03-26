<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use App\Models\Scopes\IsSeafarerScope;
use Illuminate\Database\Eloquent\Builder;

class Seafarer extends Employee
{
    protected $table = 'employees';

    public function getScope()
    {
        return new IsSeafarerScope();
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->is_seafarer = 1;
        });

        self::deleting(function (self $seafarer) {
            $seafarer->capabilitiesInfo()->delete();
        });
    }

    // Virtual Attributes
    public function getCanEditAttribute()
    {
//        return Gate::check('edit seafarers');
        return Gate::check('update', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete seafarers');
    }

    public function getPhotoPathAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('photo')->first()) {
            return config('app.url').$media->getUrl();
        }

        return config('app.url').'/images/seafarer_default.png';
    }

    public function getSignaturePathAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('signature')->first()) {
            return config('app.url').$media->getUrl();
        }

        return '';
    }

    // Relations
    public function ongoing_contract()
    {
        return $this->hasOne(SeafarerContract::class)->whereStatus(SeafarerContract::$approved);
    }

    public function pending_contract()
    {
        return $this->hasOne(SeafarerContract::class)->whereStatus(SeafarerContract::$pending);
    }

    public function archived_contracts()
    {
        return $this->hasMany(SeafarerContract::class)->whereStatus(SeafarerContract::$archived);
    }

    public function contracts()
    {
        return $this->hasMany(SeafarerContract::class);
    }

    public function last_contract()
    {
        return $this->contracts()->orderByDesc('id')->first();
    }

    // Scopes
    public function scopeHasRank(Builder $query, $ranks = [])
    {
        return $query->whereHas('capabilitiesInfo', function (Builder $q) use ($ranks) {
            return $q->whereIn('rank_id', $ranks);
        });
    }

    public function scopeHasCOC(Builder $query, $certificates = [])
    {
        return $query->whereHas('capabilitiesInfo', function (Builder $q) use ($certificates) {
            return $q->whereIn('coc_certificate_type', $certificates);
        });
    }

    public function scopeHasSecurityCources(Builder $query, $courses = [])
    {
        return $query->whereHas('capabilitiesInfo', function (Builder $q) use ($courses) {
            return $q->whereJsonContains('security_course', $courses);
        });
    }

    public function scopeHasCources(Builder $query, $courses = [])
    {
        return $query->whereHas('capabilitiesInfo', function (Builder $q) use ($courses) {
            return $q->whereJsonContains('other_courses', $courses);
        });
    }

    public function scopeWithSMC(Builder $query, $smc_reg_no)
    {
        return $query->whereHas('documents', function (Builder $q) use ($smc_reg_no) {
            return $q->where('document_type', 'SMC')->where('document_no', 'like', "%$smc_reg_no%");
        });
    }

    public function scopeAvailableForContracting(Builder $query, $starting_from = null)
    {
        $query->doesntHave('pending_contract');

        $query->where(function ($q) use ($starting_from) {
            $q->doesntHave('ongoing_contract');

            if ($starting_from) {
                $q->orWhereHas('ongoing_contract', function (Builder $q) use ($starting_from) {
                    return $q->whereRaw('
                        CASE duration_of_service_unit
                            WHEN "Week" THEN
                                ADDDATE(ADDDATE(scheduled_sign_on_date,INTERVAL duration_of_service WEEK), INTERVAL -1 DAY) < ?
                            WHEN "Month" THEN
                                ADDDATE(ADDDATE(scheduled_sign_on_date,INTERVAL duration_of_service MONTH), INTERVAL -1 DAY) < ?
                        END
                    ')->addBinding($starting_from)->addBinding($starting_from);
                });
            }
        });

        return $query;
    }

    // Methods
    public function available_for_contracting($starting_from = null)
    {
        return ! $this->pending_contract && (! $this->ongoing_contract || $this->ongoing_contract->scheduled_sign_off_date < $starting_from);
    }

    public function get_editted_url()
    {
        return config('app.url').'/Crew/seafarers/'.$this->id.'/edit';
    }
}
