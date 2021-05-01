<?php


namespace App\Services\Registration;
use App\Registration;
use App\Services\Service;

class RegistrationService extends Service
{
    public function createRegistration($student,$requestBody) {
        $registration = new Registration();
        $year = date("Y");
        $level = "";
        if ($student->student_type == 'ordinary_level'){
            $level = "OL";
        }else{
            $level = "AL";
        }

        $number = sprintf('%03d',$student->id);

        $registration->registration_no = $year.$number."-".$level;
        $registration->registration_fee = 1000;
        $registration->student_id = $student->id;
        $registration->save();

        $student->registration_no = $registration->registration_no;
        $student->save();
        return $student;
    }
}
