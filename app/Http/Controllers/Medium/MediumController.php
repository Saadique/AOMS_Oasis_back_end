<?php

namespace App\Http\Controllers\Medium;

use App\Http\Controllers\ApiController;
use App\Medium;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediumController extends ApiController
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }


    public function index()
    {
        $user = Auth::user();
        if ($user->role_name=="Admin" or $user->role_name=="Administrative Staff") {
            $mediums = Medium::where('status', 'active')->get();
            return $this->showAll($mediums);
        } else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }

    public function getAllMediums() {
        $user = Auth::user();
        if ($user->role_name=="Admin" or $user->role_name=="Administrative Staff") {
            $mediums = Medium::all();
            return $this->showAll($mediums);
        } else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }

    public function activateMedium($mediumId) {
        $user = Auth::user();
        if ($user->role_name=="Admin" or $user->role_name=="Administrative Staff") {
            return $this->serviceGateway->mediumService->activateMedium($mediumId);
        } else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }



    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role_name=="Admin" or $user->role_name=="Administrative Staff") {
            $requestData = $request->all();
            return $this->serviceGateway->mediumService->createMedium($requestData);
        } else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }


    public function show(Medium $medium)
    {
        $user = Auth::user();
        if ($user->role_name=="Admin" or $user->role_name=="Administrative Staff") {
            return $this->showOne($medium);
        }else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }



    public function update(Request $request, Medium $medium)
    {
        $user = Auth::user();
        if ($user->role_name=="Admin" or $user->role_name=="Administrative Staff") {
            $requestBody = $request->all();
            return $this->serviceGateway->mediumService->updateMedium($requestBody, $medium);
        }else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }


    public function destroy(Medium $medium)
    {
        $user = Auth::user();
        if ($user->role_name=="Admin") {
            return $this->serviceGateway->mediumService->deleteMedium($medium);
        }else{
            return $this->errorResponse("You are Unauthorized to perform this action",400);
        }
    }
}
