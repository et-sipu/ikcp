<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;

class Inventory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'name',
		'vessel_id',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete', $this);
    }

    public function getInventoryNameAttribute()
    {
        return $this->name.'@'.$this->vessel->name;
    }

    // Relations
    public function Vessel()
	{
		return $this->belongsTo(Vessel::class, 'vessel_id');
	}

    // Scopes

    // Methods
}