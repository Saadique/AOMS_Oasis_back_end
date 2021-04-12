<?php

namespace App\Services\Payment;
use App\Lecture;
use App\Payment;
use App\Services\Service;

class PaymentService extends Service
{
    public function createPayment($lecture, $requestBody)
    {
        $paymentName = $lecture->name . " Payment";
        $payment = new Payment();
        $payment->name = $paymentName;
        $payment->lecture_id = $lecture->id;
        $payment->student_fee = $requestBody['student_fee'];
        $payment->fixed_institute_amount = $requestBody['fixed_institute_amount'];
        $payment->teacher_percentage = $requestBody['teacher_percentage'];
        $payment->save();
    }

    public function findPaymentOfLecture($lectureId) {
        $payment = Payment::where('lecture_id', $lectureId)->first();
        return $payment;
    }

    public function getAllPayedStudents($month, $lecture_id){
        $lecture = Lecture::find($lecture_id);
        $payment = $lecture->payment;
    }
}
