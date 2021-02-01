<?php

namespace App\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class LectureStoreRequest extends FormRequest
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
            'name' => 'required',
            'course_medium_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'course_medium_id.required' => 'COURSE_MEDIUM_MISSING',
            'subject_id.required' => 'SUBJECT_MISSING',
            'teacher_id.required' => 'TEACHER_MISSING'
        ];
    }
}
