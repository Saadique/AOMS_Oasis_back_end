<?php

namespace App\Http\Controllers\User;
use App\Admin;
use App\AdministrativeStaff;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordCode;
use App\Services\ServiceGateway;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public $successStatus = 200;

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function menu($roleId) {
        $user = Auth::user();
        return $this->serviceGateway->loginService->findAuthorizedViews($roleId);
    }

    public function login(Request $request)
    {
        return $this->serviceGateway->loginService->login($request);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string|max:255|unique:users',
            'role_id'   => 'required',
            'role_name' => 'required',
            'password'  => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);

        $user = User::create($request->toArray());

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token, 'user'=> $user];
        return response($response, 200);

    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function getAllUserInformationByRole($role) {
        $user = Auth::user();
        return $this->serviceGateway->userService->findAllUserInformationByRole($role);
    }

    public function suspendOrActivateAccount($status, $userId) {
        $user = Auth::user();
        return $this->serviceGateway->userService->suspendOrActivateAccount($status, $userId);
    }

    public function mailResetPasswordCode($username) {
        $user = User::where('username', $username)->first();
        if ($user) {
            $email=null;
            switch ($user->role_id){
                case 1:
                    $admin = Admin::where('user_id', $user->id)->first();
                    $email = $admin->email;
                    break;
                case 2:
                    $student = Student::where('user_id', $user->id)->first();
                    $email = $student->email;
                    break;
                case 3:
                    $teacher = Teacher::where('user_id', $user->id)->first();
                    $email = $teacher->email;
                    break;
                case 4:
                    $admin_staff = AdministrativeStaff::where('user_id', $user->id)->first();
                    $email = $admin_staff->email;
                    break;
//                    case 5:
//                        $response["manager"] = Manager::where('user_id', $user->id)->first();
//                        break;
            }


            $code = rand(10000 , 99999);
            Mail::to($email)->send(new ResetPasswordCode($code));

            if(Mail::failures()){
                $response = ["message" => 'The code was not send. Please contact administration to reset your password'];
                return response($response, 400);
            }

            $user->reset_password_code = $code;
            $response = ["code" => $code];
            return response($response, 200);
        }else {
            $response = ["message" => 'User does not exist'];
            return response($response, 400);
        }
    }

    public function validateCode(Request $request) {
        $request = $request->all();
        $username = $request['username'];
        $code = $request['code'];
        $user = User::where('username', $username)->first();
        if ($user) {
            if ($user->reset_password_code == $code){
                $response = ["message" => 'Successful'];
                return response($response, 200);
            }
        }else{
            $response = ["message" => 'User does not exist'];
            return response($response, 400);
        }
    }

    public function resetPassword(Request $request) {
        $request = $request->all();
        $user = User::where('username', $request['username'])->first();
        if ($user) {
            $request['password'] = Hash::make($request['password']);
            $request['remember_token'] = Str::random(10);
            $user->password = $request['password'];
            $user->remember_token = $request['remember_token'];
            $user->save();
            $response = ["message" => 'Successfully Changed Password'];
            return response($response, 200);
        }else{
            $response = ["message" => 'User does not exist'];
            return response($response, 400);
        }
    }




    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(User $user)
    {
        //
    }


    public function update(Request $request, User $user)
    {
        //
    }


    public function destroy(User $user)
    {
        //
    }
}
