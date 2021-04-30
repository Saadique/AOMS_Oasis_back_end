<?php


namespace App\Http\Controllers\Payment;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Payment\PaymentStudentStoreRequest;
use App\Services\ServiceGateway;
use Illuminate\Support\Facades\Auth;

class PaymentStudentController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function store(PaymentStudentStoreRequest $request) {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->paymentStudentService->createPaymentForStudent($requestBody);
    }

    public function getPaymentsByStudent($studentId) {
        $user = Auth::user();
        return $this->serviceGateway->paymentStudentService->findPaidPayments($studentId);
    }

    public function findNonPayed() {
        $user = Auth::user();
        return $this->serviceGateway->paymentStudentService->findAllNonPayedStudents();
    }

    public function findPayed() {
        $user = Auth::user();
        return $this->serviceGateway->paymentStudentService->findAllPayedStudents();
    }

    public function getPaymentsOfStudent($studentId) {
        $user = Auth::user();
        return $this->serviceGateway->paymentStudentService->findPaymentsOfStudent($studentId);
    }


}
