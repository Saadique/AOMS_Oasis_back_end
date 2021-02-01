<?php

namespace App\Http\Controllers\StudentPayments;

use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use App\Student_Payment;
use Illuminate\Http\Request;

class StudentPaymentController extends Controller
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
        return $this->serviceGateway->studentPaymentsService->storeStudentPayments($requestBody);
    }


    public function show(Student_Payment $student_Payment)
    {

    }





    public function update(Request $request, Student_Payment $student_Payment)
    {

    }


    public function destroy(Student_Payment $student_Payment)
    {

    }
}
