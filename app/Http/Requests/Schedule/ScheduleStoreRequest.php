<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'schedule_start_date' => 'required',
            'schedule_end_date' => 'required',
            'room_id' => 'required',
            'lecture_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'start_time.required' => 'START_TIME_NOT_FOUND',
            'end_time.required' => 'END_TIME_NOT_FOUND',
            'schedule_start_date.required' => 'START_DATE_NOT_FOUND',
            'schedule_end_date.required' => 'END_DATE_NOT_FOUND',
            'room_id.required' => 'ROOM_ID_NOT_FOUND',
            'lecture_id.required' => 'LECTURE_ID_NOT_FOUND',
        ];
    }
}
