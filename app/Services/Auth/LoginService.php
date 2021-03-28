<?php

namespace App\Services\Auth;
use App\Admin;
use App\FrontOfficeStaff;
use App\Manager;
use App\Role;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Support\Facades\Hash;

class LoginService extends AuthService
{
    public function login($request) {
        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $userRole = Role::findOrFail($user->role_id);
                $authorizedViews = $this->getAuthorizedViews($userRole->id);
                $response = [
                    'token'    => $token,
                    'userId'       => $user->id,
                    'username' => $user->username,
                    'userRole'     => $user->role_name,
                    'userRoleId'  => $user->role_id,
                    'userViews'    => $authorizedViews
                ];
                switch ($user->role_id){
//                    case 1:
//                        $response["admin"] = Admin::where('user_id', $user->id)->first();
//                        break;
                    case 1:
                        $response["teacher"] = Teacher::where('user_id', $user->id)->first();
                        break;
                    case 2:
                        $response["student"] = Student::where('user_id', $user->id)->first();
                        break;
//                    case 4:
//                        $response["frontOfficeStaff"] = FrontOfficeStaff::where('user_id', $user->id)->first();
//                        break;
//                    case 5:
//                        $response["manager"] = Manager::where('user_id', $user->id)->first();
//                        break;
                }

                return response($response, 200);

            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 401);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 404);
        }
    }

    public function findAuthorizedViews($roleId) {
        return $this->getAuthorizedViews($roleId);
    }



//    previosly existed
//    switch ($user->role_id){
//    case 1:
//    $response["teacher"] = Teacher::where('user_id', $user->id)->first();
//    break;
//    case 2:
//    $response["student"] = Student::where('user_id', $user->id)->first();
//    break;
//    }

}
