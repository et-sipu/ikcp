<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Branch extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit branches');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete branches');
    }

    public function representative()
    {
        return $this->hasMany(AttendanceRepresentative::class);
    }

    // Relations
    public function employees()
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobInfo::class, 'branch_id', 'id', 'id', 'employee_id');
    }

    // Scopes

    // Methods
}
