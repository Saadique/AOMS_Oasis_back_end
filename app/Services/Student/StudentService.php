<?php

namespace App\Services\Student;

use App\Services\Registration\RegistrationService;
use App\Services\Service;
use App\Student;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentService extends Service
{
    public $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function createStudent($requestBody) {

//        $nicExists = Student::where('nic', $requestBody['nic'])->first();
//        if ($nicExists) {
//            return response()->json("NIC Already Exists", 400);
//        }
        $emailExists = Student::where('email', $requestBody['email'])->first();
        if ($emailExists) {
            return response()->json("Email Already Exists", 400);
        }

        $registerData = [
            'username'  => $requestBody['email'],
            'role_id'   => 2,
            'role_name' => 'Student',
            'password'  => 12345,
            'password_confirmation' => 12345
        ];

        $registerData['password'] = Hash::make($registerData['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;

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
