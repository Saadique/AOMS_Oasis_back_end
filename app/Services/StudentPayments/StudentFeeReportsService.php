<?php

namespace App\Services\StudentPayments;
use App\MonthlyPayment;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class StudentFeeReportsService extends Service
{

    //all time
    public function totalStudentFees()
    {
        $totalRecords = MonthlyPayment::where('status', 'paid')
            ->with('student', 'studentPayment.payment', 'studentPayment.paymentScheme')->get();

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
        }
        $sumOfAll = DB::select("SELECT SUM(amount) as total FROM monthly_payments WHERE status='paid'");

        $result = [
            "records"      => $recordResult,
            "total_amount" => $sumOfAll[0]->total
        ];

        return $result;
    }



    public function totalStudentFeesByMonth($year, $month) {
            $totalRecords = MonthlyPayment::where([
                ['status', 'paid'],
                ['year', $year],
                ['month', $month]
            ])->with('student','studentPayment.payment','studentPayment.paymentScheme')->get();

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
            }

        $sumOfAll = DB::select("SELECT SUM(amount) as total FROM monthly_payments WHERE status='paid'");

        $result = [
            "records"      => $recordResult,
            "total_amount" => $sumOfAll[0]->total
        ];

        return $result;
    }

    public function totalStudentFeesByDate($fromDate, $toDate)
    {
        $totalRecords = MonthlyPayment::where('status', 'paid')
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->with('student', 'studentPayment.payment', 'studentPayment.paymentScheme')->get();

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
            "total_amount" => $sumOfAll[0]->total
        ];

        return $result;
    }




    //course
    public function totalStudentFeeForCourse($courseId){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id IN
                            (SELECT id FROM lectures WHERE course_medium_id=$courseId)))");


        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");


        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = $normalPayment;

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];

        return $result;
    }

    public function totalStudentFeeForCourseByMonth($courseId, $year, $month){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND year='$year' AND month='$month' AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id IN
                            (SELECT id FROM lectures WHERE course_medium_id=$courseId)))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");


        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = $normalPayment;

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }

    public function totalStudentFeeForCourseByDate($courseId, $from_date, $to_date){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND (payment_date BETWEEN '$from_date' AND '$to_date') AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id IN
                            (SELECT id FROM lectures WHERE course_medium_id=$courseId)))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }




    //teacher
    public function totalStudentFeeForTeacher($teacherId){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND
                              student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id IN
                            (SELECT id from lectures WHERE teacher_id=$teacherId)))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }

    public function totalStudentFeeForTeacherByMonth($teacherId, $year, $month){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND year='$year' AND month='$month' AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id IN
                            (SELECT id from lectures WHERE teacher_id=$teacherId)))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }

    public function totalStudentFeeForTeacherByDate($teacherId, $from_date, $to_date){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND (payment_date BETWEEN '$from_date' AND '$to_date') AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id IN
                            (SELECT id from lectures WHERE teacher_id=$teacherId)))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }



    //lecture
    public function totalStudentFeeForLecture($lectureId){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE monthly_payments.status='paid' AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id=$lectureId))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }

    public function totalStudentFeeForLectureByMonth($lectureId, $year, $month){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND year='$year' AND month='$month' AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id=$lectureId))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }

    public function totalStudentFeeForLectureByDate($lectureId, $from_date, $to_date){
        DB::statement("CREATE OR REPLACE VIEW fee_records AS
                            SELECT * FROM monthly_payments WHERE status='paid' AND (payment_date BETWEEN '$from_date' AND '$to_date') AND
                             student_payment_id IN
                            (SELECT id FROM student__payments WHERE payment_id IN
                            (SELECT id FROM payments WHERE lecture_id=$lectureId))");

        $normalPayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                                          payments.name as payment_name, fee_records.amount,
                                          CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                                          fee_records.payment_date as date FROM fee_records
                                          INNER JOIN students ON fee_records.student_id=students.id
                                          INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
                                          INNER JOIN payments ON student__payments.payment_id=payments.id
                                          ORDER BY fee_records.payment_date");

        $schemePayment = DB::select("SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
                        payment__schemes.scheme_name as payment_name, fee_records.amount,
                        CONCAT(fee_records.year, ' ', fee_records.month) AS month,
                        fee_records.payment_date as date FROM fee_records INNER JOIN students
                        ON fee_records.student_id=students.id INNER JOIN student__payments ON
                        fee_records.student_payment_id=student__payments.id INNER JOIN payment__schemes ON
                        student__payments.payment_scheme_id=payment__schemes.id ORDER BY fee_records.payment_date");

        $total_amount = DB::select("SELECT SUM(amount) as total FROM fee_records WHERE status='paid'");

        $records = array_merge($normalPayment, $schemePayment);

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total
        ];
        return $result;
    }


//SELECT students.registration_no, students.name as student_name, student__payments.payment_type as mode,
//payments.name as payment_name, fee_records.amount, teacher_institute_shares.teacher_amount, teachers.name as teacher_name, teacher_institute_shares.institute_amount, payments.fixed_institute_amount,
//CONCAT(fee_records.year, ' ', fee_records.month) AS month,
//fee_records.payment_date as date FROM fee_records
//INNER JOIN students ON fee_records.student_id=students.id
//INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
//INNER JOIN payments ON student__payments.payment_id=payments.id INNER JOIN teacher_institute_shares ON teacher_institute_shares.monthly_payment_id=fee_records.id INNER JOIN teachers ON teachers.id=teacher_institute_shares.teacher_id
//ORDER BY fee_records.payment_date


//=====main======

//CREATE or REPLACE view test_fee AS SELECT fee_records.id as monthly_payment_id,students.registration_no, students.name as student_name, student__payments.payment_type as mode,
//payments.name as payment_name, fee_records.amount, teacher_institute_shares.teacher_amount, teachers.name as teacher_name, teacher_institute_shares.institute_amount, payments.fixed_institute_amount,
//CONCAT(fee_records.year, ' ', fee_records.month) AS month, fee_records.month as only_month, fee_records.year as only_year, fee_records.payment_date as date FROM fee_records
//INNER JOIN students ON fee_records.student_id=students.id
//INNER JOIN student__payments ON fee_records.student_payment_id=student__payments.id
//INNER JOIN payments ON student__payments.payment_id=payments.id INNER JOIN teacher_institute_shares ON teacher_institute_shares.monthly_payment_id=fee_records.id INNER JOIN teachers ON teachers.id=teacher_institute_shares.teacher_id
//ORDER BY fee_records.payment_date

//SELECT SUM(teacher_amount), SUM(institute_amount), SUM(fixed_institute_amount), SUM(test_fee.amount) FROM test_fee INNER JOIN monthly_payments ON monthly_payments.id=test_fee.monthly_payment_id WHERE monthly_payments.month="April"

//==============
}
