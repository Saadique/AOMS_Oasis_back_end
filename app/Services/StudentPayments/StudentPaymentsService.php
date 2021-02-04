<?php
namespace App\Services\StudentPayments;

use App\Services\Service;
use App\Student_Payment;

class StudentPaymentsService extends Service
{
    public function storeStudentPayments($requestBody) {
        $studentPayment = Student_Payment::create($requestBody);
        if ($studentPayment) {

        }
        return $studentPayment;
    }
}
