<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'part_no',
		'name',
		'description',
		'category_id',
		'unit',
		'usage',
		'variants',
    ];

    protected $casts = [
        'variants' => 'array'
    ];

    // protected $appends = ['can_edit', 'can_delete'];

    // Properties
    public function getCanEditAttribute()
    {
        return Gate::check('edit inventory items');
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete inventory items');
    }

    // Relations
    public function category()
    {
        return $this->belongsTo(InventoryItemCategory::class);
    }

    // Scopes

    // Methods
}