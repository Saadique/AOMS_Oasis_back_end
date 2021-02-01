<?php

namespace App\Http\Controllers\StudentSchemeLecture;

use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use App\StudentSchemeLecture;
use Illuminate\Http\Request;

class StudentSchemeLectureController extends Controller
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
        $requestBody = $request->all();
        return $this->serviceGateway->studentSchemeLectureService->storeStudentSchemeLecture($requestBody);
    }


    public function show(StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }




    public function update(Request $request, StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }


    public function destroy(StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }
}
