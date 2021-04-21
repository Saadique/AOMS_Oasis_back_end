<?php

namespace App\Services\StudentSchemeLecture;

use App\Lecture;
use App\PaymentLecAssociation;
use App\Services\Service;
use App\StudentSchemeLecture;
use Illuminate\Support\Facades\DB;

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
            $obj->student_payment_id = $requestBody['student_payment_id'];
            $obj->save();
            array_push($data,$obj);

            $lec_stud_assc = DB::table('lecture_student')->where([
                ['lecture_id', $lectureId],
                ['student_id', $requestBody['student_id']]
            ])->get()->first();

            $teacher_id = Lecture::findOrFail($lectureId)->teacher->id;

            $lecAssc = new PaymentLecAssociation();
            $lecAssc->student_payment_id = $requestBody['student_payment_id'];
            $lecAssc->lec_student_ass_id = $lec_stud_assc->lecture_student_id;
            $lecAssc->teacher_id = $teacher_id;
            $lecAssc->save();
        }
        return $data;
    }

    public function updateStudentSchemeLecture($requestBody) {
        $studentId = $requestBody['student_id'];
        DB::update("UPDATE student_scheme_lectures SET status='deleted' WHERE student_id=$studentId");

        $data = [];
        $lectureIds = $requestBody['lectureIds'];
        foreach ($lectureIds as $lectureId){
            $obj                     = new StudentSchemeLecture();
            $obj->student_id         = $requestBody['student_id'];
            $obj->payment_scheme_id  = $requestBody['payment_scheme_id'];
            $obj->lecture_id         = $lectureId;
            $obj->student_payment_id = $requestBody['student_payment_id'];
            $obj->save();
            array_push($data,$obj);

            $lec_stud_assc = DB::table('lecture_student')->where([
                ['lecture_id', $lectureId],
                ['student_id', $requestBody['student_id']]
            ])->get()->first();

            $teacher_id = Lecture::findOrFail($lectureId)->teacher->id;

            $lecAssc = new PaymentLecAssociation();
            $lecAssc->student_payment_id = $requestBody['student_payment_id'];
            $lecAssc->lec_student_ass_id = $lec_stud_assc->lecture_student_id;
            $lecAssc->teacher_id = $teacher_id;
            $lecAssc->save();
        }
        return $data;
    }
}
