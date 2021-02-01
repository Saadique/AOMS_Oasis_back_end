<?php


namespace App\Services\StudentSchemeLecture;


use App\Services\Service;
use App\StudentSchemeLecture;

class StudentSchemeLectureService extends Service
{
    public function storeStudentSchemeLecture($requestBody) {
        $data = [];
        $lectureIds = $requestBody['lectureIds'];
        foreach ($lectureIds as $lectureId){
            $obj                     = new StudentSchemeLecture();
            $obj->student_id         = $requestBody['student_id'];
            $obj->payment_scheme_id  = $requestBody['payment_scheme_id'];
            $obj->lecture_id         = $lectureId;
            $obj->save();
            array_push($data,$obj);
        }

        return $data;
    }
}
