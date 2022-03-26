<?php

namespace App\Transformers;

use App\Models\FlightReservation;
use League\Fractal\TransformerAbstract;

class FlightReservationTransformer extends TransformerAbstract
{
    public function transform(FlightReservation $flight_reservation)
    {
        $deduction_value = rtrim($flight_reservation->deduction , '%') == $flight_reservation->deduction ? rtrim($flight_reservation->deduction , '%') : (($flight_reservation->price) ? ($flight_reservation->price * rtrim($flight_reservation->deduction , '%') / 100) : null);
        $deduction_value = $deduction_value ? $deduction_value * $flight_reservation->passengers_count : $deduction_value;

        $passengers = [];
        foreach ($flight_reservation->getMedia('passengers') as $media) {
            $passenger = [];
            $passenger['id'] = $media->id;
            $passenger['file'] = null;
            $passenger['name'] = $media->getCustomProperty('name');
            $passenger['passport'] = $media->getCustomProperty('passport');
            $passenger['expiry'] = $media->getCustomProperty('expiry');
            $passenger['dob'] = $media->getCustomProperty('dob');
            $passenger['nationality'] = $media->getCustomProperty('nationality');
            $passenger['designation'] = $media->getCustomProperty('designation');
            $passenger['hp'] = $media->getCustomProperty('hp');
            $passenger['url'] = config('app.url').$media->getUrl();
            array_push($passengers, $passenger);
        }

        $transitions = [];
        foreach ($flight_reservation->workflow_transitions() as $transition) {
            array_push($transitions, $transition->getName());
        }
        $trips = [];
        foreach ($flight_reservation->trip_item as $item) {
            $trip = [];
            $trip['id'] = $item->id;
            $trip['trip_type'] = $item->trip_type;
            $trip['trip_attributes'] = $item->trip_attributes;
            array_push($trips, $trip);
        }

        return [
            'id'           => $flight_reservation->id,
            'requester_id' => $flight_reservation->requester_id,
//            'requester_name' => $flight_reservation->requester ? $flight_reservation->requester->name : '',
//            'department_name' => $flight_reservation->requester->department ? $flight_reservation->requester->department->name : '',
            'requester_name'                    => $flight_reservation->employee ? $flight_reservation->employee->full_name : '',
            'department_name'                   => $flight_reservation->employee && $flight_reservation->employee->department ? $flight_reservation->employee->department->name : '',
            'form_type'                         => $flight_reservation->form_type,
            'form_target'                       => 'SITE' === $flight_reservation->form_type ? $flight_reservation->form_target : ['id' => $flight_reservation->vessel->id, 'name' => $flight_reservation->vessel->name],
            'flight_type'                       => $flight_reservation->flight_type,
            'departure_date'                    => $flight_reservation->departure_date,
            'departure_period'                  => $flight_reservation->departure_period,
            'departure_from'                    => $flight_reservation->departure_from,
            'departure_to'                      => $flight_reservation->departure_to,
            'departure_luggage'                 => $flight_reservation->departure_luggage,
            'return_date'                       => $flight_reservation->return_date,
            'return_period'                     => $flight_reservation->return_period,
            'transport_type'                    => $flight_reservation->transport_type,
            'transport_from'                    => $flight_reservation->transport_from,
            'transport_to'                      => $flight_reservation->transport_to,
            'date'                              => $flight_reservation->created_at->format('Y-m-d'),
            'transportation_cost'               => $flight_reservation->transportation_cost,
            'passengers'                        => $passengers,
            'passengers_names'                  => array_pluck($passengers, 'name'),
            'trips'                             => $trips,
            //'trip_type' => array_pluck($trips,'trip_type'),
            //'trip_attributes' => array_pluck($trips,'trip_attributes'),
            'purpose'                           => $flight_reservation->purpose,
            'status'                            => $flight_reservation->status,
            'available_transitions'             => $transitions,
            'receipts'                          => $flight_reservation->receipts_info,
//            'quotations_count'                => $flight_reservation->quotations->count(),
//            'price'                             => $flight_reservation->price,
            'price'                             => $flight_reservation->total_price,
            'deduction'                         => rtrim($flight_reservation->deduction , '%'),
            'deduction_value'                   => $deduction_value,
            'deduction_type'                    => rtrim($flight_reservation->deduction , '%') == $flight_reservation->deduction ? 'MYR' : '%',
            'budget'                            => $flight_reservation->budgeted_price,
            'prf'                               => $flight_reservation->prf ? $flight_reservation->prf->id : null,
            'can_edit'                          => $flight_reservation->can_edit,
            'can_pass'                          => $flight_reservation->can_pass,
            'can_view'                          => $flight_reservation->can_view,
            'can_delete'                        => $flight_reservation->can_delete,
            'can_print'                         => $flight_reservation->can_print,
            'transitions'                       => $flight_reservation->transitions->sortBy('id')->pluck('in_date', 'status')->toArray(),
        ];
    }
}
