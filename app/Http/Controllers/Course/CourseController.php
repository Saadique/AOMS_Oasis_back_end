<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Course\CourseStoreRequest;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class CourseController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $courses = $this->serviceGateway->courseService->getAllActiveCourses();
        return $this->showAll($courses);
    }

    public function getAllCourses() {
        $courses = Course::all();
        return $this->showAll($courses);
    }


    public function store(CourseStoreRequest $request)
    {
        $validatedRequest = $request->all();
        return $this->serviceGateway->courseService->createCourse($validatedRequest);
    }

    public function getCourseByType($courseType) {
        return $this->serviceGateway->courseService->findCoursesByType($courseType);
    }

    public function show(Course $course)
    {
        return $this->showOne($course);
    }

    public function update(Request $request, Course $course)
    {
        $request = $request->all();
        return $this->serviceGateway->courseService->updateCourse($request,$course);
    }


    public function destroy(Course $course)
    {

    }

    public function changeDeleteStatus($status, $courseId) {
        $course = Course::findOrFail($courseId);
        $course->status = $status;
        $course->save();
        return $course;
    }
}
