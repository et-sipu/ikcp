<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Port extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'country',
    ];

    protected $hidden = [
      'deleted_at',
      'created_at',
      'updated_at',
    ];

    protected $appends = ['can_edit', 'can_delete'];

    public function getCanEditAttribute()
    {
        return Gate::check('edit ports');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete ports');
    }
}
