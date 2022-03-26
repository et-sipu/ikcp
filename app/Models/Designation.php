<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Designation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'department_id',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit designations');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete designations');
    }

    // Relations

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Scopes

    // Methods
}
