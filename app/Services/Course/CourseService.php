<?php
namespace App\Services\Course;

use App\Course;
use App\CourseMedium;
use App\Services\Service;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;

class CourseService extends Service
{

    public function findCoursesByType($courseType) {
        $courses = Course::where('course_type', $courseType)->get();
        return $courses;
    }

    public function createCourse($requestBody)
    {
        $course = Course::where('name',$requestBody['name'])->first();
        if ($course) {
            return $this->errorResponse("This course already exists", 409);
        }

        $course = Course::create($requestBody);
        $course->save();
        $course->mediums()->attach($requestBody['mediums']);
        foreach ($course->mediums as $medium) {
            $medium->pivot->name = $course->name ." ". $medium->short_name;
            $medium->pivot->course_medium_type = $course->course_type;
            $medium->pivot->save();
        }
        return $this->showOne($course);
    }

    public function updateCourse($request, Course $course)
    {
        if ($request->has('name')) {
            $course->name = $request->name;
        }
        if ($request->has('description')) {
            $course->description = $request->description;
        }
        if ($request->has('mediums')) {
            $course->mediums()->sync($request['mediums']);
            $courseMedium = CourseMedium::all()->where('course_id', $course->id);
            foreach ($courseMedium as $cm) {
                $mediumShortName = DB::table('mediums')->where('id', $cm->medium_id)->value('short_name');
                $cm->name = $request->name . " " . $mediumShortName;
                $cm->update();
            }
        }
        return $this->showOne($course);
    }

    public function retrieveAllCoursesWithTheirCourseMediums() {
        $courses = Course::all();
        $allCoursesWithMediums =[];
        foreach ($courses as $course) {
            $courseMediums = CourseMedium::where('course_id', $course->id)->get();
            $withCourse = [
                'course' => $course,
                'course_mediums' => $courseMediums
            ];
            array_push($allCoursesWithMediums,$withCourse);
        }
        return response()->json($allCoursesWithMediums,200);
    }


}
