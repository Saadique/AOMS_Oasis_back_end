<?php

namespace App\Services\Student;

use App\Services\Registration\RegistrationService;
use App\Services\Service;
use App\Student;
use Illuminate\Database\QueryException;

class StudentService extends Service
{
    public $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function createStudent($requestBody) {
        $student = Student::create($requestBody);
        $student->lectures()->attach($requestBody['lectures']);
        $this->registrationService->createRegistration($student,$requestBody);
        return $this->showOne($student);
    }

    public function addLecture($requestBody) {
        $student = Student::findOrFail($requestBody['student_id']);
        try {
            $student->lectures()->attach($requestBody['lecture_id']);
        } catch (QueryException $exception){
            return response()->json(['error' => "This lecture is already added to this student", 'message' => $exception->getMessage()],400);
        }
        return response()->json(['message' => "Lectures Successfully Added"],200);
    }

    public function findStudentLectures($studentId) {
        $student = Student::findOrFail($studentId);
        $lectures = $student->lectures()->with('courseMedium','teacher')->get();
        return $lectures;
    }
}
