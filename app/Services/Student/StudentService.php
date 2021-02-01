<?php

namespace App\Services\Student;

use App\Services\Registration\RegistrationService;
use App\Services\Service;
use App\Student;

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

    public function findStudentLectures($studentId) {
        $student = Student::findOrFail($studentId);
        $lectures = $student->lectures()->with('courseMedium','teacher')->get();
        return $lectures;
    }
}
