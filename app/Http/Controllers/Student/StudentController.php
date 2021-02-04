<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Student\StudentStoreRequest;
use App\Services\ServiceGateway;
use App\Student;
use Illuminate\Http\Request;

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

    public function store(StudentStoreRequest $request)
    {
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
