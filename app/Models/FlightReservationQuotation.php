<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class FlightReservationQuotation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'flight_time',
        'trip_item_id',
        'airlines',
        'website',
        'price',
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
    public function trip_item()
    {
        return $this->belongsTo(TripItem::class);
    }

    // Scopes

    // Methods
}
