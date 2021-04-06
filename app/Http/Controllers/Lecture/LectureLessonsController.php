<?php

namespace App\Http\Controllers\Lecture;

use App\Http\Controllers\Controller;
use App\LectureLessons;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

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
        $requestBody = $request->all();
        return $this->serviceGateway->lectureLessonService->createLesson($requestBody);
    }

    public function getLessonsByLecture($lecture_id) {
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


    public function update(Request $request, LectureLessons $lectureLessons)
    {
        //
    }


    public function destroy(LectureLessons $lectureLessons)
    {
        //
    }
}
