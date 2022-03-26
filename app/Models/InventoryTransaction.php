<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'transaction_type',
		'inventory_item_id',
		'variations',
		'quantity',
		'inventory_id',
		'description',
		'note',
        'expired_at',
        'transaction_date',
        'location',
    ];

    protected $casts = [
        'variations' => 'array'
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

    // Relations
	public function inventory()
	{
		return $this->belongsTo(Inventory::class, 'inventory_id');
	}

	public function inventory_item()
	{
		return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
	}


    // Scopes

    // Methods
}