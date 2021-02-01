<?php


namespace App\Http\Controllers\Payment;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Payment\PaymentStudentStoreRequest;
use App\Services\ServiceGateway;

class PaymentStudentController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function store(PaymentStudentStoreRequest $request) {
        $requestBody = $request->all();
        return $this->serviceGateway->paymentStudentService->createPaymentForStudent($requestBody);
    }

    public function getPaymentsByStudent($studentId) {
        return $this->serviceGateway->paymentStudentService->findPaidPayments($studentId);
    }

    public function findNonPayed() {
        return $this->serviceGateway->paymentStudentService->findAllNonPayedStudents();
    }

    public function findPayed() {
        return $this->serviceGateway->paymentStudentService->findAllPayedStudents();
    }

    public function getPaymentsOfStudent($studentId) {
        return $this->serviceGateway->paymentStudentService->findPaymentsOfStudent($studentId);
    }


}
