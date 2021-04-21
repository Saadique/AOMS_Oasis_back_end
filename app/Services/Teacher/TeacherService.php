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

    public function findAllActiveTeachers() {
        $teachers = Teacher::where('status', 'active')->get();
        return $teachers;
    }


    public function createTeacher($requestBody) {

        $nicExists = User::where('username', $requestBody['nic'])->first();
        if ($nicExists){
            return response()->json(['message'=>"NIC Already Exists"], 400);
        }
        $emailExists = Teacher::where('email', $requestBody['email'])->first();
        if ($emailExists){
            return response()->json(['message'=>"Email Already Exists"], 400);
        }

        $randomPassword = Str::random(8);

        $registerData = [
            'username'  => $requestBody['nic'],
            'role_id'   => 3,
            'role_name' => 'Teacher',
            'password'  => $randomPassword,
            'password_confirmation' => $randomPassword
        ];

        $registerData['password'] = Hash::make($registerData['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $user->initial_password = $randomPassword;
        $this->sendPasswordMail($requestBody['email'], $user->username, $randomPassword);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;
        $teacher = Teacher::create($requestBody);

        $user->initial_password = $randomPassword;
        $user->save();
        $this->sendPasswordMail($requestBody['email'], $user->username, $randomPassword);
        return $this->showOne($teacher);
    }

    public function updateTeacher($requestBody, $teacher) {
        $emailExists = Teacher::where([
            ['email', $requestBody['email']],
            ['id', '!=', $teacher->id]
        ])->first();
        if ($emailExists){
            return response()->json(['message'=>"Email Already Exists"], 400);
        }

        $teacher->email = $requestBody['email'];
        $teacher->mobile_no = $requestBody['mobile_no'];
        $teacher->address = $requestBody['address'];
        $teacher->name = $requestBody['name'];
        $teacher->save();

        return $teacher;
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
                                            (select id from student__payments
                                            where payment_id =
                                            (Select id from payments
                                            where lecture_id=$lectureId))");

        $lec_stud_assc3 = DB::select("select name, registration_no, teacher_monthly_payments.status, student__payments.payment_type, year, month
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
                             AND status='paid' AND student_payment_id IN
                            (select id from student__payments
                            where payment_id =
                            (Select id from payments
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

    public function findTeacherTotalMonthlyIncome($teacherId) {
        $result = DB::select("SELECT monthly_payments.month, monthly_payments.year,  SUM(teacher_institute_shares.teacher_amount) as total_amount FROM teacher_institute_shares
                            INNER JOIN monthly_payments ON teacher_institute_shares.monthly_payment_id=monthly_payments.id
                            WHERE monthly_payments.status='paid' AND teacher_institute_shares.teacher_id=$teacherId
                            GROUP BY monthly_payments.month, monthly_payments.year;");

        return $result;
    }

    public function findTeacherTotalMonthlyIncomeForLecture($lectureId, $teacherId) {
        $result = DB::select("SELECT monthly_payments.month, monthly_payments.year, SUM(teacher_institute_shares.teacher_amount) as total_amount FROM teacher_institute_shares
                            INNER JOIN monthly_payments ON teacher_institute_shares.monthly_payment_id=monthly_payments.id
                            WHERE monthly_payments.status='paid' AND teacher_institute_shares.teacher_id=$teacherId AND
                                  teacher_institute_shares.lecture_id=$lectureId GROUP BY monthly_payments.month, monthly_payments.year;");

        return $result;
    }

    public function findTeacherScheduleTimetable($teacherId) {

        DB::statement("CREATE OR REPLACE VIEW teacher_timetables AS
                            SELECT * from schedules where lecture_id IN
                            (SELECT id FROM lectures WHERE teacher_id=$teacherId)");

        $teacherSchedules =
            DB::select("SELECT lectures.name as lecture_name, day, start_time, end_time, rooms.name as room_name
                             FROM teacher_timetables INNER JOIN lectures ON teacher_timetables.lecture_id=lectures.id
                             INNER JOIN rooms ON teacher_timetables.room_id=rooms.id");

        return $teacherSchedules;
    }
}
