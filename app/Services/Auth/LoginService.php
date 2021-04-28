<?php

namespace App\Services\Auth;
use App\Admin;
use App\AdministrativeStaff;
use App\MonthlyPayment;
use App\Role;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class LoginService extends AuthService
{
    public function login($request) {
        $user = User::where('username', $request->username)->first();
        if ($user) {
            if ($user->status == 'deleted') {
                $response = ["message" => 'Sorry! Your Account is Deactivated.'];
                return response($response, 400);
            }
            if ($user->status == 'suspended') {
                $response = ["message" => 'Sorry! Your Account is Suspended temporarily. Please Contact Oasis Administration to Activate Your Account.'];
                return response($response, 400);
            }
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $userRole = Role::findOrFail($user->role_id);
//                $authorizedViews = $this->getAuthorizedViews($userRole->id);
                $response = [
                    'token'      => $token,
                    'userId'     => $user->id,
                    'username'   => $user->username,
                    'userRole'   => $user->role_name,
                    'userRoleId' => $user->role_id,
                    'userViews'  => null
                ];
                $this->changeStatusInDue();
                switch ($user->role_id){
                    case 1:
                        $response["admin"] = Admin::where('user_id', $user->id)->first();
                        $this->changeStatusInDue();
                        break;
                    case 2:
                        $response["student"] = Student::where('user_id', $user->id)->first();
                        break;
                    case 3:
                        $response["teacher"] = Teacher::where('user_id', $user->id)->first();
                        break;
                    case 4:
                        $response["administrative_staff"] = AdministrativeStaff::where('user_id', $user->id)->first();
                        $this->changeStatusInDue();
                        break;
                    case 5:
                        $response["manager"] = Manager::where('user_id', $user->id)->first();
                        break;
                }

                return response($response, 200);

            } else {
                $response = ["message" => "Sorry! Incorrect Password!"];
                return response($response, 400);
            }
        } else {
            $response = ["message" => 'Sorry! User Does Not Exist!'];
            return response($response, 400);
        }
    }

    public function findAuthorizedViews($roleId) {
        return $this->getAuthorizedViews($roleId);
    }

    public function changeStatusInDue(){
        $today = Carbon::now();
        $activeMonthlyPayments = MonthlyPayment::where([
            ['delete_status','active'],
            ['status', 'active']
        ]);
        foreach ($activeMonthlyPayments as $monthlyPayment){
            if ($today<$monthlyPayment->due_date){
                $monthlyPayment->status = "due";
                $monthlyPayment->save();
            }
        }

    }

}
