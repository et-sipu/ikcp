<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContactInfo extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'personal_hp',
        'personal_email',
        'next_of_kin_name',
        'next_of_kin_hp',
        'next_of_kin_address',
        'next_of_kin_relationship',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
