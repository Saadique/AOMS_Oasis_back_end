<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Student\StudentStoreRequest;
use App\Services\ServiceGateway;
use App\Student;
use Illuminate\Http\Request;
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
        $students = Student::all();
        return $students;
    }

    public function store(StudentStoreRequest $request){
        $requestBody = $request->all();
        return $this->serviceGateway->studentService->createStudent($requestBody);
    }

    public function addLecture(Request  $request){
        $requestBody = $request->all();
        return $this->serviceGateway->studentService->addLecture($requestBody);
    }

    public function getStudentLectures($studentId){
        return $this->serviceGateway->studentService->findStudentLectures($studentId);
    }

    public function deactivateStudent($studentId){
        $student = Student::findOrFail($studentId);
        $student->status = "deleted";

        DB::update("UPDATE lecture_student SET status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE student__payments SET status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE monthly_payments SET status='deleted' WHERE student_id=$student->id");
        DB::update("UPDATE users SET status='deleted' WHERE user_id=$student->user_id");
        DB::update("UPDATE student_scheme_lectures SET status='deleted' WHERE student_id=$student->id");
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
        //
    }


    public function destroy(Student $student)
    {
        //
    }
}
