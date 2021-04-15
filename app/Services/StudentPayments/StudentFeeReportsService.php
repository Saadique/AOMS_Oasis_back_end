<?php


namespace App\Services\StudentPayments;


use App\MonthlyPayment;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class StudentFeeReportsService extends Service
{

    public function totalStudentFees() {
        $totalRecords = MonthlyPayment::where('status', 'paid')
            ->with('student','studentPayment.payment','studentPayment.paymentScheme')->get();

        $recordResult = [];
        foreach ($totalRecords as $record) {
            if ($record->studentPayment->payment_type == 'normal') {
                $data = [
                    "registration_no" => $record->student->registration_no,
                    "student_name" => $record->student->name,
                    "mode" => $record->studentPayment->payment_type,
                    "payment_name" => $record->studentPayment->payment->name,
                    "amount" => $record->amount,
                    "month" => "$record->year $record->month",
                    "date" => $record->payment_date
                ];
                array_push($recordResult, $data);
            }
            if ($record->studentPayment->payment_type == 'scheme') {
                $data = [
                    "registration_no" => $record->student->registration_no,
                    "student_name" => $record->student->name,
                    "mode" => $record->studentPayment->payment_type,
                    "payment_name" => $record->studentPayment->paymentScheme->name,
                    "amount" => $record->amount,
                    "month" => "$record->year $record->month",
                    "date" => $record->payment_date
                ];
                array_push($recordResult, $data);
            }
        }

        $sumOfAll = DB::select("SELECT SUM(amount) as total FROM monthly_payments WHERE status='paid'");

        $result = [
            "records"      => $recordResult,
            "total_amount" => $sumOfAll[0]
        ];

        return $result;
    }

    public function totalStudentFeeForCourse($courseId){
        DB::statement("CREATE OR REPLACE VIEW fee_records_by_course AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND student_id IN
                            (SELECT student_id FROM lecture_student WHERE lecture_id IN
                            (SELECT id FROM lectures WHERE course_medium_id=$courseId))");


        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                            payments.name as payment_name, fee_records_by_course.amount,
                                            CONCAT(fee_records_by_course.year, ' ', fee_records_by_course.month) AS month,
                                            fee_records_by_course.payment_date as date FROM fee_records_by_course
                                            INNER JOIN students ON fee_records_by_course.student_id=students.id
                                            INNER JOIN student__payments ON fee_records_by_course.student_payment_id=student__payments.id
                                            INNER JOIN payments ON student__payments.payment_id=payments.id
                                            ORDER BY fee_records_by_course.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records_by_course.amount,
                        CONCAT(fee_records_by_course.year, ' ', fee_records_by_course.month) AS month,
                        fee_records_by_course.payment_date as date FROM fee_records_by_course INNER JOIN students
                        ON fee_records_by_course.student_id=students.id INNER JOIN student__payments ON
                        fee_records_by_course.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records_by_course.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records_by_course WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]
        ];

        return $result;
    }



}
