<?php

namespace App\Http\Controllers;

use App\Role;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class RoleController extends Controller
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


    public function store(Request $request)
    {
        //
    }


    public function show(Role $role)
    {
        //
    }


    public function edit(Role $role)
    {
        //
    }


    public function update(Request $request, Role $role)
    {

    }

    public function destroy(Role $role)
    {
        //
    }
}
