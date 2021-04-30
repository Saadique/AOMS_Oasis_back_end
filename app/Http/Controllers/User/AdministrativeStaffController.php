<?php

namespace App\Http\Controllers\User;

use App\AdministrativeStaff;
use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministrativeStaffController extends Controller
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->userService->createAdministrativeStaff($requestBody);
    }

    public function show(AdministrativeStaff $administrativeStaff)
    {
        //
    }

    public function edit(AdministrativeStaff $administrativeStaff)
    {
        //
    }

    public function update(Request $request, AdministrativeStaff $administrativeStaff)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->userService->updateAdminStaff($requestBody,  $administrativeStaff);
    }

    public function destroy(AdministrativeStaff $administrativeStaff)
    {
        //
    }
}
