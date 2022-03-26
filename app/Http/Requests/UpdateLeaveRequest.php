<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
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
        $days_count = 0;
        if ($this->get('start_date') && $this->get('end_date')) {
            $days_count = days_count($this->get('start_date'), $this->get('end_date'));
        }
//            $days_count =  Carbon::createFromFormat('Y-m-d',  $this->get('end_date'))->addDay(1)->diffInDaysFiltered(function(Carbon $date) {
//                return !$date->isWeekend();
//            },Carbon::createFromFormat('Y-m-d',  $this->get('start_date')));

        return [
            'leave_type' => 'required',
            'start_date' => 'required|date_format:Y-m-d|before_or_equal:'.$this->get('end_date'),
            'end_date'   => 'required|date_format:Y-m-d|after_or_equal:'.$this->get('start_date'),
            'period'     => [
                Rule::requiredIf(function () use ($days_count) {
                    return 1 === $days_count;
                }),
                'nullable',
                'in:M,AN,F',
            ],
            'reason' => 'required',
        ];
    }
}
