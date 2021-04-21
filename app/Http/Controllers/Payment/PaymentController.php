<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\ApiController;
use App\Payment;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class PaymentController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function getPaymentOfLecture($lectureId) {
        return $this->serviceGateway->paymentService->findPaymentOfLecture($lectureId);
    }


    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }


    public function update(Request $request, Payment $payment)
    {
        $request = $request->all();
        $payment->student_fee = $request['student_fee'];
        $payment->fixed_institute_amount = $request['fixed_institute_amount'];
        $payment->teacher_percentage = $request['teacher_percentage'];
        $payment->save();
        return $payment;
    }


    public function destroy(Payment $payment)
    {
        //
    }
}
