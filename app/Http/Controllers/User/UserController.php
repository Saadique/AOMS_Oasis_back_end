<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return $this->serviceGateway->userService->findAllUserInformationByRole($role);
    }

    public function suspendOrActivateAccount($status, $userId) {
        return $this->serviceGateway->userService->suspendOrActivateAccount($status, $userId);
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
