<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\CourseMedium;
use App\Http\Controllers\ApiController;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseMediumController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
//        $courses = CourseMedium::with('course','medium')->get();
        $user = Auth::user();
        $courses = CourseMedium::all();
        return $this->showAll($courses);
    }



    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $user = Auth::user();
        return $this->serviceGateway->courseMediumService->getCourse($id);
    }

    public function getAllByCourse($id)
    {
        $user = Auth::user();
        $courseMediums = CourseMedium::where('course_id', $id)->get();
        return $this->showAll($courseMediums);
    }

    public  function getAllCoursesWithCourseMediums() {
        return $this->serviceGateway->courseService->retrieveAllCoursesWithTheirCourseMediums();
    }

    public  function getOneCoursesWithMedium($id) {
        $user = Auth::user();
        $course = Course::findOrFail($id);
        $courseMediums = CourseMedium::where('course_id', $id)->get();

        $withCourse[] = [
            'course' => $course,
            'course_mediums' => $courseMediums
        ];
        return response()->json($withCourse,200);
    }

    public function changeDeleteStatus($status, $courseMediumId) {
        $user = Auth::user();
        $courseMedium = CourseMedium::findOrFail($courseMediumId);
        $courseMedium->status = $status;
        $courseMedium->save();
        return $courseMedium;
    }


    public function update(Request $request, CourseMedium $courseMedium)
    {

    }


    public function destroy(CourseMedium $courseMedium)
    {
        //
    }
}
