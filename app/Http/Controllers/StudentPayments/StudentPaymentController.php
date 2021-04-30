<?php

namespace App\Http\Controllers\StudentPayments;

use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use App\Student_Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->studentPaymentsService->storeStudentPayments($requestBody);
    }

    public function getPaymentsOfStudent($studentId){
        $user = Auth::user();
        return $this->serviceGateway->studentPaymentsService->findPaymentsOfStudent($studentId);
    }

    public function getAllStudentPayments($studentId){
        $user = Auth::user();
        return $this->serviceGateway->studentPaymentsService->findAllPaymentsOfStudents($studentId);
    }

    public function show(Student_Payment $student_Payment){

    }

    public function update(Request $request, Student_Payment $student_Payment)
    {

    }


    public function destroy(Student_Payment $student_Payment){

    }
}
