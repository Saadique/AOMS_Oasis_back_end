<?php

namespace App\Http\Controllers\Lecture;

use App\Course;
use App\Http\Controllers\Controller;
use App\LectureLessons;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureLessonsController extends Controller
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->lectureLessonService->createLesson($requestBody);
    }

    public function getLessonsByLecture($lecture_id) {
        $user = Auth::user();
        return $this->serviceGateway->lectureLessonService->getLessonsByLecture($lecture_id);
    }


    public function show(LectureLessons $lectureLessons)
    {
        //
    }


    public function edit(LectureLessons $lectureLessons)
    {
        //
    }


    public function update(Request $request, LectureLessons $lesson)
    {
        $user = Auth::user();
        $existingLesson = LectureLessons::where([
            ['lecture_id', $request['lecture_id']],
            ['name',$request['name']],
            ['id','!=',$lesson->id]
        ])->first();

        if ($existingLesson) {
            return response("LESSON_EXISTS_WITH_SAME_NAME", 400);
        }
        $lesson->name = $request['name'];
        $lesson->description = $request['description'];
        $lesson->save();

        return $lesson;
    }

    public function changeDeleteStatus($lesson_id, $status) {
        $user = Auth::user();
        $lesson = LectureLessons::findOrFail($lesson_id);
        $lesson->status = $status;
        $lesson->save();
        return $lesson;
    }


    public function destroy(LectureLessons $lectureLessons)
    {
        //
    }
}
