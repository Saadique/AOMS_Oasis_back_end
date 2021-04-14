<?php
namespace App\Services\StudentPayments;

use App\Lecture;
use App\MonthlyPayment;
use App\PaymentLecAssociation;
use App\Services\Service;
use App\Student;
use App\Student_Payment;
use App\StudentSchemeLecture;
use App\TeacherInstituteShare;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StudentPaymentsService extends Service
{
    public function storeStudentPayments($requestBody) {
        $studentPayment = Student_Payment::create($requestBody);
        if ($requestBody['payment_type'] == "normal"){
            foreach ($requestBody['lecture_ids'] as $lectureId) {

                $lec_stud_assc = DB::table('lecture_student')->where([
                    ['lecture_id', $lectureId],
                    ['student_id', $requestBody['student_id']]
                ])->get()->first();

                $teacher_id = Lecture::findOrFail($lectureId)->teacher->id;

                $lecAssc = new PaymentLecAssociation();
                $lecAssc->student_payment_id = $studentPayment->id;
                $lecAssc->lec_student_ass_id = $lec_stud_assc->lecture_student_id;
                $lecAssc->teacher_id = $teacher_id;
                $lecAssc->save();
            }
        }
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
        ])->take(2)->get();
        return $activeMonthlyPayments;
    }

    public function findPaidPayments($studentId) {
        $activeMonthlyPayments = MonthlyPayment::where([
            ['student_id', $studentId],
            ['status', 'payed']
        ])->with('studentPayment.payment','studentPayment.paymentScheme')->get();
        return $activeMonthlyPayments;
    }

    public function findDuePayments($studentId) {
        $activeMonthlyPayments = MonthlyPayment::where([
            ['student_id', $studentId],
            ['status', 'due']
        ])->with('studentPayment.payment','studentPayment.paymentScheme')->get();
        return $activeMonthlyPayments;
    }

    public function findPaymentsOfStudent($studentId) {
        $student = Student::findOrFail($studentId);
        return $student->studentPayments()->with('payment','paymentScheme')->get();
    }

    public function findAllPaymentsOfStudents($studentId) {
        $student = Student::findOrFail($studentId);
        $studentPayments =  $student->studentPayments;
        $result = [];
        foreach ($studentPayments as $payment){
            if ($payment->payment_type == "normal"){
                $obj = [
                    "name"=>$payment->payment->name,
                    "student_payment"=>$payment,
                    "payment"=>$payment->payment
                ];
                array_push($result,$obj);
            }

            if ($payment->payment_type == "scheme"){
                $lectures = StudentSchemeLecture::where([
                    ['student_id', $studentId],
                    ['payment_scheme_id', $payment->payment_scheme_id]
                ])->with('lecture')->get();

                $obj = [
                    "name"=>$payment->paymentScheme->scheme_name,
                    "payment"=>$payment->paymentScheme,
                    "student_payment"=>$payment,
                    "lectures"=>$lectures
                ];
                array_push($result,$obj);
            }
        }
        return $result;
    }

    public function updatePayedStatus($requestBody, MonthlyPayment $monthlyPayment) {
        $monthlyPayment->status = $requestBody['status'];
        $formattedDate = Carbon::parse($requestBody['payment_date'])->format('Y-m-d');
        $monthlyPayment->payment_date = $formattedDate;
        $monthlyPayment->save();
        $studentPayment = $monthlyPayment->studentPayment;
        $payedAmount = $studentPayment->payment_amount;

        if ($studentPayment->payment_type == "normal"){
            $payment = $studentPayment->payment;
            $lecture = $payment->lecture;
            $teacher = $lecture->teacher;

            $shareAmount = $payedAmount - $payment->fixed_institute_amount;
            $teacherAmount = $shareAmount * ($payment->teacher_percentage/100);
            $teacherInstituteShare = new TeacherInstituteShare();
            $teacherInstituteShare->monthly_payment_id = $monthlyPayment->id;
            $teacherInstituteShare->teacher_id = $teacher->id;
            $teacherInstituteShare->student_payment_id = $studentPayment->id;
            $teacherInstituteShare->lecture_id = $lecture->id;
            $teacherInstituteShare->status = "to_settle";
            $teacherInstituteShare->teacher_amount = $teacherAmount;
            $teacherInstituteShare->institute_amount = $shareAmount - $teacherAmount;
            $teacherInstituteShare->save();
        }

        if ($studentPayment->payment_type == "scheme") {
            $paymentScheme = $studentPayment->paymentScheme;
            $stuLecSchemes = StudentSchemeLecture::where('student_id', $monthlyPayment->student_id)->get();
            foreach ($stuLecSchemes as $stuLecScheme) {
                $lecture = $stuLecScheme->lecture;
                $payment = $lecture->payment;
                $teacher = $lecture->teacher;

                $shareAmount = $payedAmount - $paymentScheme->fixed_institute_amount;
                $singleShare = $shareAmount / $paymentScheme->no_of_subjects;
                $teacherAmount = $singleShare * ($payment->teacher_percentage/100);
                $teacherInstituteShare = new TeacherInstituteShare();
                $teacherInstituteShare->monthly_payment_id = $monthlyPayment->id;
                $teacherInstituteShare->teacher_id = $teacher->id;
                $teacherInstituteShare->student_payment_id = $studentPayment->id;
                $teacherInstituteShare->lecture_id = $lecture->id;
                $teacherInstituteShare->status = "to_settle";
                $teacherInstituteShare->teacher_amount = $teacherAmount;
                $teacherInstituteShare->institute_amount = $singleShare - $teacherAmount;
                $teacherInstituteShare->save();
            }
        }

        return $monthlyPayment;
    }

    public function changeStatusInDue(){
        $today = Carbon::now();
        echo $today;
        $activeMonthlyPayments = MonthlyPayment::all();
        foreach ($activeMonthlyPayments as $monthlyPayment){
            if ($today<$monthlyPayment->due_date){
                $monthlyPayment->status = "active";
                $monthlyPayment->save();
                //alert student
            }
        }
    }



}
