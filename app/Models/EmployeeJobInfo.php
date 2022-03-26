<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeJobInfo extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'department_id',
        'designation_id',
        'branch_id',
        'employment_level_id',
        'staff_id',
        'report_to_id',
        'finger_print_ids',
        'email',
        'employment_status',
        'probationary_period',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'finger_print_ids' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function level()
    {
        return $this->belongsTo(EmploymentLevel::class, 'employment_level_id');
    }

    public function report_to()
    {
        return $this->belongsTo(Employee::class, 'report_to_id', 'id');
    }
}
