<?php

namespace App\Http\Controllers\Payment_Scheme;

use App\Http\Controllers\Controller;
use App\Payment_Scheme;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentSchemeController extends Controller
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {

    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->paymentSchemeService->createPaymentScheme($requestBody);
    }

    public function getRelevantScheme(Request $request) {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->paymentSchemeService->findRelevantSchema($requestBody);
    }


    public function show(Payment_Scheme $payment_Scheme)
    {

    }


    public function edit(Payment_Scheme $payment_Scheme)
    {
        //
    }


    public function update(Request $request, Payment_Scheme $payment_Scheme)
    {
        //
    }


    public function destroy(Payment_Scheme $payment_Scheme)
    {
        //
    }
}
