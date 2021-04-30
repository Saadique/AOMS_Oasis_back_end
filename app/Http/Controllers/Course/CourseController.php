<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Course\CourseStoreRequest;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $user = Auth::user();
        $courses = $this->serviceGateway->courseService->getAllActiveCourses();
        return $this->showAll($courses);
    }

    public function getAllCourses() {
        $user = Auth::user();
        $courses = Course::all();
        return $this->showAll($courses);
    }


    public function store(CourseStoreRequest $request)
    {
        $user = Auth::user();
        $validatedRequest = $request->all();
        return $this->serviceGateway->courseService->createCourse($validatedRequest);
    }

    public function getCourseByType($courseType) {
        $user = Auth::user();
        return $this->serviceGateway->courseService->findCoursesByType($courseType);
    }

    public function show(Course $course)
    {
        $user = Auth::user();
        return $this->showOne($course);
    }

    public function update(Request $request, Course $course)
    {
        $user = Auth::user();
        $request = $request->all();
        return $this->serviceGateway->courseService->updateCourse($request,$course);
    }


    public function destroy(Course $course)
    {

    }

    public function changeDeleteStatus($status, $courseId) {
//        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        $course->status = $status;
        $course->save();
        return $course;
    }
}
