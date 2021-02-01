<?php

namespace App\Services\Subject;
use App\DailySchedule;
use App\Services\Service;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubjectService extends Service
{

    public function createSubject($request) {
        $existingSubject = Subject::where([
            ['course_medium_id',$request['course_medium_id']],
            ['name',$request['name']]
        ])->first();

        if ($existingSubject) {
            return $this->errorResponse("The Subject With Name ".$request['name']." Already Exists", 400);
        }

        $subject = Subject::create($request);
        return $subject;
    }

    public function getAllSubjectsOfOneCourseMedium($courseMediumId) {
        $subjects = Subject::where('course_medium_id',$courseMediumId)->get();
        return $this->showAll($subjects);
    }

    public function updateSubject(Request $request, Subject $subject) {
        $existingSubject = Subject::where([
            ['id','!=', $subject->id],
            ['course_medium_id',$request['course_medium_id']],
            ['name',$request['name']]
        ])->first();

        if ($existingSubject) {
            return $this->errorResponse("The Subject With Name ".$request['name']." Already Exists", 400);
        }

        $updatedSubject              = Subject::findOrFail($subject->id);
        $updatedSubject->name        = $request->name;
        $updatedSubject->description = $request->description;
        $updatedSubject->type        = $request->type;

        $updatedSubject->update();
        return $this->showOne($updatedSubject);
    }
}
