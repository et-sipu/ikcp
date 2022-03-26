<?php

namespace App\Transformers;

use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;
use App\Models\FlightReservationQuotation;

class FlightReservationQuotationTransformer extends TransformerAbstract
{
    public function transform(FlightReservationQuotation $flight_reservation_quotation)
    {
        return [
            'id' => $flight_reservation_quotation->id,
//			'flight_time' => $flight_reservation_quotation->flight_time,//
            'heading'               => ['id' => $flight_reservation_quotation->trip_item->id, 'name' => $flight_reservation_quotation->trip_item->trip_attributes['departure_from'].' -> '.$flight_reservation_quotation->trip_item->trip_attributes['departure_to']],
            'heading_name'          => $flight_reservation_quotation->trip_item->trip_attributes['departure_from'].' -> '.$flight_reservation_quotation->trip_item->trip_attributes['departure_to'],
            'flight_time'           => Carbon::createFromFormat('H:i:s', $flight_reservation_quotation->flight_time)->format('H:i'),
            'airlines'              => $flight_reservation_quotation->airlines,
            'website'               => $flight_reservation_quotation->website,
            'price'                 => $flight_reservation_quotation->price,
            'flight_reservation_id' => $flight_reservation_quotation->flight_reservation_id,
            'can_edit'              => $flight_reservation_quotation->can_edit,
            'can_delete'            => $flight_reservation_quotation->can_delete,
        ];
    }
}
