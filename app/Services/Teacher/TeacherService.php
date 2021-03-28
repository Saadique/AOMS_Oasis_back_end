<?php

namespace App\Services\Teacher;

use App\Services\Service;
use App\Teacher;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherService extends Service
{
    public function createTeacher($requestBody) {
        $randomPassword = Str::random(8);

        $registerData = [
            'username'  => $requestBody['nic'],
            'role_id'   => 1,
            'role_name' => 'Teacher',
            'password'  => 12345,
            'password_confirmation' => 12345
        ];

        $registerData['password'] = Hash::make($registerData['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;
        $teacher = Teacher::create($requestBody);
        return $this->showOne($teacher);
    }

    public function findAllLecturesByTeacher($teacherId){
        $teacher = Teacher::findOrFail($teacherId);
        return $teacher->lectures;
    }

    public function findMonthlyLecRemuneration($teacherId, $lectureId, $month) {

    }
}
