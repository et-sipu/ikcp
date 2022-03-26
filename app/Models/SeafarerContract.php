<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class SeafarerContract extends Model implements HasMedia, Auditable
{
    use SoftDeletes, HasMediaTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'vessel_id',
        'duration_of_service',
        'duration_of_service_unit',
        'basic_salary',
        'currency',
        'pay_frequency',
        'scheduled_sign_on_date',
        'sign_on_port_id',
        'pay_leave',
        'tested_by',
        'insurance_company',
        'insurance_type',
        'insurance_expiry_date',
        'crew_request_id',
        'contract_rank_id',
        'remarks'
    ];

    //protected $appends = ['can_edit', 'can_add_signs'];

    // Contract statuses
    public static $pending = 'pending';
    public static $approved = 'approved';
    public static $archived = 'archived';
    public static $terminated = 'terminated';
    public static $canceled = 'canceled';

    // Virtual Attributes
    public function getCanEditAttribute()
    {
//        return  Gate::check('edit own seafarer contracts');
        return Gate::check('update', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete seafarer contracts');
    }

    public function getCanApproveAttribute()
    {
        return Gate::check('approve seafarer contracts');
    }

    public function getCanAddSignsAttribute()
    {
        return Gate::check('add_sign', $this);
    }

    public function getSignedContractPathAttribute()
    {
        /** @var Media $media */
        if ($media = $this->getMedia('signed_contract')->first()) {
            return config('app.url').$media->getUrl();
        }
    }

    public function getScheduledSignOffDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->scheduled_sign_on_date)->{'add'.$this->duration_of_service_unit.'s'}($this->duration_of_service)->subDay()->format('Y-m-d');
    }

    // Relations

    public function seafarer()
    {
        return $this->belongsTo(Seafarer::class);
    }

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function sign_on_port()
    {
        return $this->belongsTo(Port::class);
    }

    public function signons()
    {
        return $this->hasMany(Signon::class);
    }

    public function sign_on()
    {
        return $this->hasOne(Signon::class)->where('sign_type', 'ON');
    }

    public function sign_off()
    {
        return $this->hasOne(Signon::class)->where('sign_type', 'OFF');
    }

    public function previous_contract()
    {
        return $this->seafarer->contracts()->where('created_at', '<', $this->created_at ?: now())->orderByDesc('created_at')->first();
    }

    public function crew_request()
    {
        return $this->belongsTo(CrewRequest::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class, 'contract_rank_id', 'id');
    }

    // Scopes
    public function scopeHasRank(Builder $query, $ranks = [])
    {
        return $query->whereHas('seafarer', function (Builder $q) use ($ranks) {
            return $q->hasRank($ranks);
        });
    }

    public function contract_rank()
    {
        return $this->belongsTo(Rank::class, 'contract_rank_id');
    }
}
