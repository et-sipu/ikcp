<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCapabilitiesInfo extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'rank_id',
        'other_courses',
        'security_course',
        'coc_certificate_type',
        'coc_certificate_class',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'other_courses'   => 'array',
        'security_course' => 'array',
    ];

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
}
