<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryItemCategory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'name',
		'parent_id',
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit inventory item categories');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete inventory item categories');
    }

    // Relations
    public function parent()
    {
        return $this->belongsTo(InventoryItemCategory::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(InventoryItemCategory::class,'parent_id');
    }

    // Scopes

    // Methods
}