<?php
namespace App\Services\StudentPayments;

use App\MonthlyPayment;
use App\Services\Service;
use App\Student;
use App\Student_Payment;

class StudentPaymentsService extends Service
{
    public function storeStudentPayments($requestBody) {
        $studentPayment = Student_Payment::create($requestBody);
        if ($studentPayment) {
            $payment_start_date = $studentPayment->payment_start_date;
            $payment_end_date = $studentPayment->payment_end_date;
            $count_month = $payment_start_date;
            while($count_month<=$payment_end_date){
                $start_date_totime = strtotime($count_month);
                $lastdate = date("Y-m-t", $start_date_totime);
                $month = date("F",$start_date_totime);
                $year = date("Y",$start_date_totime);
                $monthlyPayment = new MonthlyPayment();
                $monthlyPayment->student_payment_id = $studentPayment->id;
                $monthlyPayment->student_id = $studentPayment->student_id;
                $monthlyPayment->amount = $studentPayment->amount;
                $monthlyPayment->month = $month;
                $monthlyPayment->year = $year;
                $monthlyPayment->due_date = $lastdate;
                $monthlyPayment->status = "active";
                $monthlyPayment->teacher_remuneration_status = "none";
                $monthlyPayment->save();
                $count_month = date("Y-m-d",strtotime("+1 month",$start_date_totime));
            }
        }
        return $studentPayment;
    }

    public function findMonthlyPayments($studentPaymentId) {
        $activeMonthlyPayments = MonthlyPayment::where([
            ['student_payment_id', $studentPaymentId],
            ['status', 'active']
        ])->get();
        return $activeMonthlyPayments;
    }

    public function findPaymentsOfStudent($studentId) {
        $student = Student::findOrFail($studentId);
        return $student->studentPayments;
    }

}
