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

        $admin = AdministrativeStaff::create($requestBody);
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

    public function updateAdmin($requestBody, Admin $admin) {
        $admin->first_name = $requestBody['first_name'];
        $admin->last_name = $requestBody['last_name'];
        $admin->contact_number = $requestBody['contact_number'];
        $admin->nic = $requestBody['nic'];
        $admin->email = $requestBody['email'];
        $admin->update();
        return $admin;
    }

    public function updateAdminStaff($requestBody, AdministrativeStaff $admin_staff) {
        $admin_staff->first_name = $requestBody['first_name'];
        $admin_staff->last_name = $requestBody['last_name'];
        $admin_staff->contact_number = $requestBody['contact_number'];
        $admin_staff->nic = $requestBody['nic'];
        $admin_staff->email = $requestBody['email'];
        $admin_staff->update();
        return $admin_staff;
    }

    public function suspendOrActivateAccount($status, $userId) {
        $user = User::findOrFail($userId);
        if ($user->role_name == 'Admin' and $status=='suspended'){
            $activeAdmins = User::where([
                ['status', 'active'],
                ['role_name','Admin']
            ])->get();
            if ($activeAdmins->count()==1){
                $response = ["message" => 'Only 1 Admin Account Exists. Therefore It cannot be SUSPENDED'];
                return response($response, 400);
            }
        }
        $user->status = $status;
        $user->update();
        return $user;
    }


}
