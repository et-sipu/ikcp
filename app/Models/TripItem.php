<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripItem extends Model
{
    protected $fillable = [
        'trip_type',
        'trip_attributes',
        'flight_reservation_id',
    ];

    protected $casts = [
        'trip_attributes' => 'array',
    ];

    public function flight_reservation()
    {
        $this->belongsTo(FlightReservation::class);
    }

//    public function quotations()
//    {
//        return $this->hasMany(FlightReservationQuotation::class);
//    }
}
