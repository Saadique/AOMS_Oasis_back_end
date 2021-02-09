<?php

namespace App\Http\Controllers\StudentPayments;

use App\Http\Controllers\Controller;
use App\MonthlyPayment;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

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
        return $this->serviceGateway->studentPaymentsService->findMonthlyPayments($studentPaymentId);
    }

    public function show(MonthlyPayment $monthlyPayment)
    {

    }


    public function edit(MonthlyPayment $monthlyPayment)
    {
        //
    }


    public function update(Request $request, MonthlyPayment $monthlyPayment)
    {
        //
    }


    public function destroy(MonthlyPayment $monthlyPayment)
    {
        //
    }
}
