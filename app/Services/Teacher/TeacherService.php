<?php

namespace App\Services\Teacher;

use App\Schedule;
use App\Services\Service;
use App\Teacher;
use App\User;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

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

    public function findMonthlyRemunerations($teacherId, $lectureId, $year, $month) {
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
}
