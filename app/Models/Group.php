<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;

class Group extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'name',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return true;//Gate::check('edit groups');
    }

    public function getCanDeleteAttribute()
    {
        return true;//Gate::check('delete groups');
    }

    // Relations
    public function users()

    {
        return $this->belongsToMany(User::class);
    }

    // Scopes

    // Methods
}