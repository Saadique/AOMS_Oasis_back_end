<?php

namespace App\Http\Requests\DailySchedule;

use Illuminate\Foundation\Http\FormRequest;

class DailyScheduleStoreRequest extends FormRequest
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
            'date' => 'required',
            'end_time' => 'required',
            'start_time' => 'required',
            'day' => 'required',
            'room_id' => 'required',
            'lecture_id' => 'required'
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
