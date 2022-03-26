<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlightReservationQuotationRequest extends FormRequest
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
            'flight_time' => 'required|date_format:H:i',
            'heading'     => 'required',
            'airlines'    => 'required',
            'website'     => 'required',
            'price'       => 'numeric',
        ];
    }
}
