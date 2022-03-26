<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlightReservationRequest extends FormRequest
{
    /**
     * Determine if the meta is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'form_type'       => 'required',
            'form_target'       => 'VESSEL' === $this->get('form_type') ? 'required|array' : 'required',
            'trips.*.trip_type' => 'required',
//            'trips.*.trip_attributes.departure_date' => 'required_if:trips.*.trip_type,BUS|required_if:trips.*.trip_type,CAB|required_if:trips.*.trip_type,FERRY|required_if:trips.*.trip_type,TRAIN|required_if:trips.*.trip_type,FLIGHT|nullable|date_format:Y-m-d|after:' . date('Y-m-d'),
            'trips.*.trip_attributes.departure_date'    => 'required_if:trips.*.trip_type,BUS|required_if:trips.*.trip_type,CAB|required_if:trips.*.trip_type,FERRY|required_if:trips.*.trip_type,TRAIN|required_if:trips.*.trip_type,FLIGHT|nullable|date_format:Y-m-d',
            'trips.*.trip_attributes.transport_from'    => 'required_if:trips.*.trip_type,BUS|required_if:trips.*.trip_type,CAB|required_if:trips.*.trip_type,FERRY|required_if:trips.*.trip_type,TRAIN',
            'trips.*.trip_attributes.transport_to'      => 'required_if:trips.*.trip_type,BUS|required_if:trips.*.trip_type,CAB|required_if:trips.*.trip_type,FERRY|required_if:trips.*.trip_type,TRAIN',
            'trips.*.trip_attributes.nights'            => 'required_if:trips.*.trip_type,HOTEL',
            'trips.*.trip_attributes.location'          => 'required_if:trips.*.trip_type,HOTEL',
            'trips.*.trip_attributes.hotel'             => 'required_if:trips.*.trip_type,HOTEL',
            'trips.*.trip_attributes.check_in'          => 'required_if:trips.*.trip_type,HOTEL',
            'trips.*.trip_attributes.flight_type'       => 'required_if:trips.*.trip_type,FLIGHT',
            'trips.*.trip_attributes.departure_period'  => 'nullable|in:AM,PM',
            'trips.*.trip_attributes.departure_from'    => 'required_if:trips.*.trip_type,FLIGHT',
            'trips.*.trip_attributes.departure_to'      => 'required_if:trips.*.trip_type,FLIGHT',
            'trips.*.trip_attributes.departure_luggage' => 'nullable|numeric|min:0',
            'trips.*.trip_attributes.return_date'       => 'required_if:trips.*.trip_attributes.flight_type,RETURN|nullable|date_format:Y-m-d|after_or_equal:departure_date|after:'.date('Y-m-d'),
            'trips.*.trip_attributes.return_period'     => 'nullable|in:AM,PM',
            'trips.*.trip_attributes.price'     => 'required|numeric',
//            'trips.*.trip_attributes.return_from' => 'required_if:trips.*.trip_attributes.flight_type,RETURN',
//            'trips.*.trip_attributes.return_to' => 'required_if:trips.*.trip_attributes.flight_type,RETURN',
            'trips'          => 'required',
            'passengers'          => 'required',
            'purpose'             => 'required',
            'deduction'             => 'required|numeric',
            'passengers.*.file'   => 'required|nullable|mimes:jpeg,jpg,png,gif,pdf',
//            'passengers.*.file'   => 'nullable|mimes:jpeg,jpg,png,gif,pdf',
            'passengers.*.name'          => 'required',
            'passengers.*.designation'   => 'required',
            'passengers.*.nationality'   => 'required',
            'passengers.*.dob'           => 'nullable|date_format:Y-m-d',
            'passengers.*.expiry'        => 'nullable|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'passengers.*.file.mimes'           => 'The File attribute must be a file of type: jpeg, jpg, png, gif, pdf.',
            'passengers.*.file.uploaded'        => 'The file can\'t be uploaded',
            'passengers.*.file.required'        => 'The file can\'t be empty',
            'passengers.*.file.required_if'     => 'The file can\'t be empty',
            'passengers.*.name.required'        => 'Name is required',
            'passengers.*.designation.required' => 'Designation is required',
            'passengers.*.nationality.required' => 'Nationality is required',
            'passengers.*.dob.required'         => 'Date Of Birth date is required',
            'passengers.*.hp.required'          => 'Hand phone is required',
            'passengers.*.passport.required'    => 'Passport is required',
            'passengers.*.expiry.required'      => 'Expiry date is required',
            'passengers.*.dob.date_format'      => 'Date Of Birth does not match the format Y-m-d.',
            'passengers.*.expiry.date_format'   => 'Expiry date does not match the format Y-m-d.',
        ];
    }
}
