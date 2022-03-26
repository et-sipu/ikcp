<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Holiday extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'occasion',
        'start_date',
        'end_date',
        'description',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return true; //Gate::check('edit holidays');
    }

    public function getCanDeleteAttribute()
    {
        return true; //Gate::check('delete holidays');
    }

    // Relations

    // Scopes

    // Methods
}
