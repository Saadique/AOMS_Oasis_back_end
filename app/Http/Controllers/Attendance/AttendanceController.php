<?php

namespace App\Http\Controllers\Attendance;

use App\Attendance;
use App\Http\Controllers\ApiController;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class AttendanceController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(Attendance $attendance)
    {

    }

    public function update(Request $request, Attendance $attendance)
    {

    }


    public function destroy(Attendance $attendance)
    {

    }
}
