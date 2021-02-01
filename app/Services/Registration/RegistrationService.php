<?php


namespace App\Services\Registration;
use App\Registration;
use App\Services\Service;

class RegistrationService extends Service
{
    public function createRegistration($student,$requestBody) {
        $registration = new Registration();
        $registration->registration_no = "2021" . Registration::generateRegNo();
        $registration->registration_fee = 1000;
        $registration->registration_fee = 1000;
        $registration->student_id = $student->id;
        $registration->save();

        $student->registration_no = $registration->registration_no;
        $student->save();
    }
}
