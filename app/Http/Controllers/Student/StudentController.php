<?php

namespace App\Http\Controllers\Student;

use App\CourseMedium;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Student\StudentStoreRequest;
use App\Services\ServiceGateway;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $user = Auth::user();
        $students = Student::all();
        return $students;
    }

    public function store(StudentStoreRequest $request){
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->studentService->createStudent($requestBody);
    }

    public function addLecture(Request  $request){
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->studentService->addLecture($requestBody);
    }

    public function removeLecture(Request  $request){
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->studentService->removeLecture($requestBody);
    }


    public function getStudentLectures($studentId){
        $user = Auth::user();
        return $this->serviceGateway->studentService->findStudentLectures($studentId);
    }

    public function deactivateStudent($studentId){
        $user = Auth::user();
        $student = Student::findOrFail($studentId);
        $student->status = "deleted";
        $student->save();

        DB::update("UPDATE lecture_student SET status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE student__payments SET status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE monthly_payments SET delete_status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE monthly_payments SET status='deleted' WHERE student_id=$student->id and status='active'");
        DB::update("UPDATE users SET status='deleted' WHERE id=$student->user_id");
//        DB::update("UPDATE student_scheme_lectures SET status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE payment_lec_associations SET status='deleted' WHERE lec_student_ass_id
                        IN(SELECT lecture_student_id FROM lecture_student WHERE student_id=$student->id)");

        return $student;
    }


    public function show(Student $student)
    {
        return $student;
    }


    public function update(Request $request, Student $student)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->studentService->updateStudent($requestBody, $student);
    }


    public function getAllStudentsByCourseMedium($courseMediumId) {
        $students = Student::where('course_medium_id',$courseMediumId)->get();
        return $students;
    }

    public function getAllStudentsByLevel($level) {
        $students = Student::where('student_type',$level)->get();
        return $students;
    }

    public function destroy(Student $student)
    {
        //
    }
}
