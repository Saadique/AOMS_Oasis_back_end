<?php

namespace App\Services\Teacher;

use App\Schedule;
use App\Services\Service;
use App\Student_Payment;
use App\Teacher;
use App\User;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherService extends Service
{
    public function createTeacher($requestBody) {
        $randomPassword = Str::random(8);

        $registerData = [
            'username'  => $requestBody['nic'],
            'role_id'   => 1,
            'role_name' => 'Teacher',
            'password'  => 12345,
            'password_confirmation' => 12345
        ];

        $registerData['password'] = Hash::make($registerData['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;
        $teacher = Teacher::create($requestBody);
        return $this->showOne($teacher);
    }

    public function findAllLecturesByTeacher($teacherId){
        $teacher = Teacher::findOrFail($teacherId);
        return $teacher->lectures;
    }

    public function findLectureMonths($lectureId) {
        $schedule = Schedule::where('lecture_id', $lectureId)->get()->first();
        $start    = (new DateTime($schedule->schedule_start_date))->modify('first day of this month');
        $end      = (new DateTime($schedule->schedule_end_date))->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        $years = [];
        $oneTime = true;
        $schedules = [];
        foreach ($period as $date) {
//            echo $date->format("Y-m") . "<br>\n";
            if ($oneTime==true) {
                array_push($years, $date->format("Y"));
                $oneTime=false;
            }else {
                foreach ($years as $year) {
                    if ($year != $date->format("Y")) {
                        array_push($years, $date->format("Y"));
                    }
                }
            }
        }

        $scheduleDuration = [];
        foreach ($years as $yearKey) {
            $scheduleDuration[$yearKey] = [];
        }

        foreach ($period as $date) {
            $year = $date->format("Y");
            foreach ($scheduleDuration as $yearKey=>$yr) {
                if ($yearKey == $year) {
                    array_push($scheduleDuration[$yearKey], $date->format("m"));
                }
            }
        }

        return $scheduleDuration;
    }

    public function findMonthlyRemunerations($lectureId, $year, $month) {
        $lec_stud_assc2 = DB::statement("Create or replace view teacher_monthly_payments AS
                                            select * from monthly_payments where year='$year' AND month='$month' AND student_payment_id IN
                                            (select student_payment_id from payment_lec_associations
                                            where lec_student_ass_id IN
                                            (Select lecture_student_id from lecture_student
                                            where lecture_id=$lectureId))");

        $lec_stud_assc3 = DB::select("select name, registration_no, status, student__payments.payment_type, year, month
                                           FROM teacher_monthly_payments inner join students ON
                                               teacher_monthly_payments.student_id=students.id inner join student__payments ON
                                               student__payments.id=teacher_monthly_payments.student_payment_id");


        return $lec_stud_assc3;
    }

    public function findMonthlyRemunerationsPaid($teacherId, $lectureId, $year, $month){
        DB::statement("Create or replace view teacher_paid_payments AS
                            SELECT * from teacher_institute_shares
                            where teacher_id=$teacherId AND monthly_payment_id IN
                            (select id from monthly_payments where year='$year' AND month='$month'
                             AND status='payed' AND student_payment_id IN
                            (select student_payment_id from payment_lec_associations
                            where lec_student_ass_id IN
                            (Select lecture_student_id from lecture_student
                            where lecture_id=$lectureId)))");

        $paid_students =
        DB::select("SELECT name, registration_no, monthly_payments.status, student__payments.payment_type, year,
                       month, teacher_institute_shares.teacher_amount, student__payments.id as student_payment_id,
                       monthly_payments.payment_date
                       FROM teacher_paid_payments inner join student__payments ON
                       student__payments.id=teacher_paid_payments.student_payment_id
                       inner join teacher_institute_shares ON
                       teacher_institute_shares.monthly_payment_id=teacher_paid_payments.monthly_payment_id
                       inner join monthly_payments ON
                       monthly_payments.id=teacher_paid_payments.monthly_payment_id inner join students ON
                       monthly_payments.student_id=students.id");

//        $withPaymentInfo = [];
//
//        foreach ($paid_students as $paid_student) {
//           if ($paid_student->payment_type == "normal"){
//                $studentPayment = Student_Payment::findOrFail($paid_student->payment_id);
//
//           }
//        }

        return $paid_students;
    }
}
