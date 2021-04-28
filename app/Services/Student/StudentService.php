<?php

namespace App\Services\Student;

use App\Payment;
use App\Schedule;
use App\Services\Registration\RegistrationService;
use App\Services\Service;
use App\Services\StudentPayments\StudentPaymentsService;
use App\Student;
use App\Student_Payment;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentService extends Service
{
    public $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function createStudent($requestBody) {
        $emailExists = Student::where('email', $requestBody['email'])->first();
        if ($emailExists) {
            return response("Email Already Exists", 400);
        }

        if (empty($requestBody['lectures'])){
            return response("Student Cannot Be Registered Without Lectures", 400);
        }

        $randomPassword = Str::random(8);

        $registerData = [
            'username'  => $requestBody['email'].uniqid(),
            'role_id'   => 2,
            'role_name' => 'Student',
            'password'  => $randomPassword,
            'password_confirmation' => $randomPassword
        ];

        $registerData['password'] = Hash::make($registerData['password']);
        $registerData['remember_token'] = Str::random(10);

        $user = User::create($registerData);
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $requestBody['user_id'] = $user->id;

        $student = Student::create($requestBody);
        $student->lectures()->attach($requestBody['lectures']);
        $student = $this->registrationService->createRegistration($student,$requestBody);


        $user->initial_password = $randomPassword;
        $user->username = $student->registration_no;
        $this->sendPasswordMail($student->email, $student->registration_no, $randomPassword);

        return $this->showOne($student);
    }

    public function updateStudent($requestBody, Student $student) {
        $emailExists = Student::where([
            ['email', $requestBody['email']],
            ['id','!=',$student->id]
        ])->first();
        if ($emailExists) {
            return response()->json("Email Already Exists", 400);
        }
        $student->name = $requestBody['name'];
        $student->mobile_no = $requestBody['mobile_no'];
        $student->school_name = $requestBody['school_name'];
        $student->email = $requestBody['email'];
        $student->save();
        return $student;
    }

    public function addLecture($requestBody) {
        $student = Student::findOrFail($requestBody['student_id']);
        try {
            $todayPHP = date("Y-m-d");
            $today = date('Y-m-d',strtotime($todayPHP));
            $schedule = Schedule::where('lecture_id', $requestBody['lecture_id'])->first();
            $student->lectures()->attach($requestBody['lecture_id']);

            $payment = Payment::where('lecture_id', $requestBody['lecture_id'])->first();
            $lecture_ids = [];
            array_push($lecture_ids, $requestBody['lecture_id']);
            $studentPayment = [
                "student_id" => $requestBody['student_id'],
                "payment_type" => "normal",
                "payment_id" => $payment->id,
                "payment_amount" => $payment->student_fee,
                "payment_start_date" => $today,
                "payment_end_date" => $schedule->schedule_end_date,
                "lecture_ids" => $lecture_ids
            ];
            $studentPaymentService = new StudentPaymentsService();
            $studentPaymentService->storeStudentPayments($studentPayment);
        } catch (QueryException $exception){
            return response()->json(['error' => "This lecture is already added to this student", 'message' => $exception->getMessage()],400);
        }
        return response()->json(['message' => "Lecture Successfully Added"],200);
    }

    public function removeLecture($requestBody) {
        $student = Student::findOrFail($requestBody['student_id']);
        $studentId = $requestBody['student_id'];
        $lectureId = $requestBody['lecture_id'];
        $payment   = Payment::where('lecture_id',$lectureId)->first();

        $studentPayment = Student_Payment::where([
            ['student_id', $studentId],
            ['payment_id', $payment->id]
        ])->first();

        if ($studentPayment) {
            DB::update("UPDATE student__payments SET status='deleted' WHERE
                        student_id=$studentId AND payment_id=$payment->id");


            DB::update("UPDATE monthly_payments SET delete_status='deleted' WHERE
                        student_payment_id=$studentPayment->id AND status='active'");

            $lec_stud_assc = DB::table('lecture_student')->where([
                ['lecture_id', $lectureId],
                ['student_id', $studentId]
            ])->get()->first();

            DB::update("UPDATE payment_lec_associations SET status='deleted' WHERE
                         student_payment_id=$studentPayment->id AND lec_student_ass_id=$lec_stud_assc->lecture_student_id;");
        }

        $student->lectures()->detach($requestBody['lecture_id']);

        return response()->json(['message' => "Lecture Removed From Student Successfully"],200);
    }

    public function findStudentLectures($studentId) {
        $student = Student::findOrFail($studentId);
        $lectures = $student->lectures()->with('courseMedium','teacher')->get();
        return $lectures;
    }
}
