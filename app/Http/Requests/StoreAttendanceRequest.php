<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
            // 'name' => 'required|unique:attendances',
            'user_id'         => 'required',
            'attendance_date' => 'required',
            'dept_movement'   => 'required',
            'logged_in_time'  => 'required',
            'logged_out_time' => 'required',
            'hr_review'       => 'required',
            'ey_review'       => 'required',
            'remarks'         => 'required',
        ];
    }
}
