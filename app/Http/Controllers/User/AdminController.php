<?php

namespace App\Http\Controllers\User;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class AdminController extends Controller
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
        $requestBody = $request->all();
        return $this->serviceGateway->userService->createAdmin($requestBody);
    }


    public function show(Admin $admin)
    {
        //
    }


    public function edit(Admin $admin)
    {
        //
    }


    public function update(Request $request, Admin $admin)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->userService->updateAdmin($requestBody, $admin);
    }


    public function destroy(Admin $admin)
    {
        //
    }
}
