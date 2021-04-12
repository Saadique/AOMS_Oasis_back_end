<?php


namespace App\Services\User;


use App\Admin;
use App\AdministrativeStaff;
use App\Lecture;
use App\Services\Service;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends Service
{
    public function createAdmin($requestBody) {
        $randomPassword = Str::random(8);

        $registerData = [
            'username'  => $requestBody['user_name'],
            'role_id'   => 1,
            'role_name' => 'Admin',
            'password'  => $requestBody['password']
        ];

        $registerData['password'] = Hash::make($requestBody['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;

        $admin = Admin::create($requestBody);
        return $this->showOne($admin);
    }

    public function createAdministrativeStaff($requestBody) {

        $registerData = [
            'username'  => $requestBody['user_name'],
            'role_id'   => 4,
            'role_name' => 'Administrative Staff',
            'password'  => $requestBody['password']
        ];

        $registerData['password'] = Hash::make($requestBody['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;

        $admin = Admin::create($requestBody);
        return $this->showOne($admin);
    }


    public function findAllUserInformationByRole($role) {
        switch ($role) {
            case 'admin':
                $admins = Admin::where('status','active')->with('user')->get();
                return $admins;
            case 'admin_staff':
                $admin_staffs = AdministrativeStaff::where('status','active')->with('user')->get();
                return $admin_staffs;
            case 'teacher':
                $teachers = Teacher::where('status','active')->with('user')->get();
                return $teachers;
            case 'student':
                $students = Student::where('status','active')->with('user')->get();
                return $students;
        }
    }
}
