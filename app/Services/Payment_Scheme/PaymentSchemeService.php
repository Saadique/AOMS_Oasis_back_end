<?php
namespace App\Services\Payment_Scheme;

use App\Lecture;
use App\Payment;
use App\Payment_Scheme;
use App\Schedule;
use App\Services\Service;

class PaymentSchemeService extends Service
{
    public function createPaymentScheme($requestBody) {
        $paymentScheme = Payment_Scheme::create($requestBody);
        return $paymentScheme;
    }

    public function findRelevantSchema($requestBody) {
        $paymentScheme = Payment_Scheme::where([
            ['no_of_subjects', $requestBody['no_of_lectures']],
            ['class_level', $requestBody['class_level']]
        ])->first();

        if ($paymentScheme == null or $requestBody['class_level'] == "ordinary_level"){
            $payments = [];
            $lectureIds = $requestBody['lecture_ids'];

            foreach ($lectureIds as $id){
                $payment  = Payment::where('lecture_id', $id)->get()->first();
                $schedule = Schedule::where('lecture_id', $id)->get()->first();
                $paymentEndDate = $schedule->schedule_end_date;
                $payment->{'payment_end_date'} = $paymentEndDate;
                array_push($payments, $payment);
            }

            return response()->json([
                "message" => "Payments",
                "scheme" => null,
                "payments" => $payments
            ]);
        }

        return response()->json([
            "message" => "Payment Scheme Available",
            "scheme" => $paymentScheme,
            "payments" => null,
            "lecture_ids" => $requestBody['lecture_ids']
        ]);
    }
}
