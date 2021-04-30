<?php

namespace App\Http\Controllers\StudentPayments;

use App\Http\Controllers\Controller;
use App\MonthlyPayment;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyPaymentController extends Controller
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index() {

    }

    public function store(Request $request){

    }

    public function getMonthlyPayments($studentPaymentId){
        $user = Auth::user();
        return $this->serviceGateway->studentPaymentsService->findMonthlyPayments($studentPaymentId);
    }

    public function getMonthlyPaidPayments($studentId){
        $user = Auth::user();
        return $this->serviceGateway->studentPaymentsService->findPaidPayments($studentId);
    }

    public function getMonthlyDuePayments($studentId){
        $user = Auth::user();
        return $this->serviceGateway->studentPaymentsService->findDuePayments($studentId);
    }

    public function update(Request $request, MonthlyPayment $monthlyPayment)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        $result = $this->serviceGateway->studentPaymentsService->updatePayedStatus($requestBody, $monthlyPayment);
        return response()->json($result,200);
    }

    public function changeStatusInDue(){
        $user = Auth::user();
        $this->serviceGateway->studentPaymentsService->changeStatusInDue();
    }


    public function destroy(MonthlyPayment $monthlyPayment)
    {
        //
    }
}
