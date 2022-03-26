<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrewRequest extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'vessel_id',
        'replacement_of_id',
        'suggested_seafarer_id',
        'rank_id',
        'to_sign_on',
        'remarks',
        'status',
    ];

    protected $appends = ['can_edit', 'can_delete'];

    // Virtual Attributes
    public function getCanEditAttribute()
    {
        return Gate::check('edit crew requests');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete crew requests');
    }

    // Relations
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function replacement_of()
    {
        return $this->belongsTo(Seafarer::class);
    }

    public function suggested_seafarer()
    {
        return $this->belongsTo(Seafarer::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
}
