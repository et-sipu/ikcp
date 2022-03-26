<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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
            // 'name' => 'required|unique:announcements',
			'subject' => 'required',
			'content' => 'required',
            'destination.to' => 'required_if:destination.destination_type,DEPARTMENT|required_if:destination.destination_type,LOCATION',
            'destination.destination_type' => 'required',
        ];
    }
}
