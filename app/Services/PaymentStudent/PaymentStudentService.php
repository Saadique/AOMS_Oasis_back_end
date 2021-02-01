<?php
namespace App\Services\PaymentStudent;
use App\Services\Service;
use App\Student;
use Illuminate\Support\Facades\DB;

class PaymentStudentService extends Service
{
    public function createPaymentForStudent($requestBody) {
        $student = Student::findOrFail($requestBody['student_id']);

        $alreadyExists = DB::table('payment_student')
            ->where([
                ['student_id'   , $requestBody['student_id']],
                ['payment_id'   , $requestBody['payments']],
                ['payment_month', $requestBody['payment_month']]
            ])->get();

        if (!$alreadyExists->isEmpty()) {
            return $this->errorResponse("The fee is already paid", 400);
        }

        $student->payments()->attach($requestBody['payments'], [
            'payment_amount' => $requestBody['payment_amount'],
            'payment_month'  => $requestBody['payment_month'],
            'payment_date'   => $requestBody['payment_date']
            ]);

        return $this->successResponse(['status'=>"SUCCESS"], 200);
    }

    public function findPaidPayments($studentId) {
        $student  = Student::findOrFail($studentId);
        $payments = $student->payments;
        return $this->showAll($payments);
    }

    public function findAllNonPayedStudents() {
        $result = DB::select("SELECT * FROM students
                                   WHERE id IN
                                    (SELECT student_id
                                       FROM lecture_student
                                       WHERE  lecture_student.lecture_id=21
                                       AND student_id NOT IN
                                            (SELECT student_id
                                             FROM payment_student
                                             WHERE payment_month='march' AND payment_id=19))");

        return $result;
    }


    public function findAllPayedStudents() {
        $result = DB::select("SELECT * FROM students
                                   WHERE id IN
                                    (SELECT student_id
                                       FROM lecture_student
                                       WHERE  lecture_student.lecture_id=21
                                       AND student_id IN
                                            (SELECT student_id
                                             FROM payment_student
                                             WHERE payment_month='march' AND payment_id=19))");

        return $result;
    }

    public function findPaymentsOfStudent($studentId) {
        $result = DB::select("SELECT * FROM payments
                                    WHERE lecture_id IN
                                    (SELECT lecture_id FROM lecture_student
                                      WHERE student_id=$studentId)");

        return $result;
    }

    public function findDuePaymentsOfStudent($studentId) {

    }
}
