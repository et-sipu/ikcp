<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'hod_id',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return true; //Gate::check('edit {{modelNamePluralLowerCase}}');
    }

    public function getCanDeleteAttribute()
    {
        return true; //Gate::check('delete {{modelNamePluralLowerCase}}');
    }

    // Relations
    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function employees()
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobInfo::class, 'department_id', 'id', 'id', 'employee_id');
    }

    // Scopes

    // Methods
}
