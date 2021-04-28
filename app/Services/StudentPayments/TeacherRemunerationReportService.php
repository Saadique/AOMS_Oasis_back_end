<?php


namespace App\Services\StudentPayments;


use App\Services\Service;
use Illuminate\Support\Facades\DB;

class TeacherRemunerationReportService extends Service
{

    public function findRemunerationsPaidForTeachers() {
        DB::statement("CREATE OR REPLACE VIEW teacher_report AS SELECT SUM(teacher_institute_shares.teacher_amount) as total_remun,
         COUNT(teacher_institute_shares.id) as no_of_student_payments, teachers.name as teacher_name, lectures.name as lecture_name,
         course_medium.name as course_name FROM teacher_institute_shares INNER JOIN teachers ON teacher_institute_shares.teacher_id=teachers.id
          INNER JOIN lectures ON teacher_institute_shares.lecture_id=lectures.id INNER JOIN course_medium ON lectures.course_medium_id=course_medium.id
           GROUP BY teacher_institute_shares.teacher_id, teacher_institute_shares.lecture_id");

        $records =  DB::select("SELECT * FROM teacher_report ");

        $total_amount = DB::select("SELECT SUM(total_remun) as total_amount FROM teacher_report");

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total_amount
        ];
        return $result;
    }

    public function findRemunerationsPaidForTeachersByTeacher($teacherId) {
        DB::statement("CREATE OR REPLACE VIEW teacher_report AS SELECT SUM(teacher_institute_shares.teacher_amount) as total_remun,
         COUNT(teacher_institute_shares.id) as no_of_student_payments, teachers.name as teacher_name, lectures.name as lecture_name,
         course_medium.name as course_name FROM teacher_institute_shares INNER JOIN teachers ON teacher_institute_shares.teacher_id=teachers.id
          INNER JOIN lectures ON teacher_institute_shares.lecture_id=lectures.id INNER JOIN course_medium ON lectures.course_medium_id=course_medium.id
          where teachers.id=$teacherId GROUP BY teacher_institute_shares.teacher_id, teacher_institute_shares.lecture_id");

        $records =  DB::select("SELECT * FROM teacher_report ");

        $total_amount = DB::select("SELECT SUM(total_remun) as total_amount FROM teacher_report");

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total_amount
        ];
        return $result;
    }

    public function findRemunerationsPaidForTeachersByLecture($lectureId){
        DB::statement("CREATE OR REPLACE VIEW teacher_report AS SELECT SUM(teacher_institute_shares.teacher_amount) as total_remun,
         COUNT(teacher_institute_shares.id) as no_of_student_payments, teachers.name as teacher_name, lectures.name as lecture_name,
         course_medium.name as course_name FROM teacher_institute_shares INNER JOIN teachers ON teacher_institute_shares.teacher_id=teachers.id
          INNER JOIN lectures ON teacher_institute_shares.lecture_id=lectures.id INNER JOIN course_medium ON lectures.course_medium_id=course_medium.id
          where lectures.id=$lectureId GROUP BY teacher_institute_shares.teacher_id, teacher_institute_shares.lecture_id");

        $records =  DB::select("SELECT * FROM teacher_report ");

        $total_amount = DB::select("SELECT SUM(total_remun) as total_amount FROM teacher_report");

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total_amount
        ];
        return $result;
    }


    public function findRemunerationsPaidForTeachersByCourse($courseMediumId) {
        DB::statement("CREATE OR REPLACE VIEW teacher_report AS SELECT SUM(teacher_institute_shares.teacher_amount) as total_remun,
         COUNT(teacher_institute_shares.id) as no_of_student_payments, teachers.name as teacher_name, lectures.name as lecture_name,
         course_medium.name as course_name FROM teacher_institute_shares INNER JOIN teachers ON teacher_institute_shares.teacher_id=teachers.id
          INNER JOIN lectures ON teacher_institute_shares.lecture_id=lectures.id INNER JOIN course_medium ON lectures.course_medium_id=course_medium.id
          where course_medium.id=$courseMediumId GROUP BY teacher_institute_shares.teacher_id, teacher_institute_shares.lecture_id");

        $records =  DB::select("SELECT * FROM teacher_report ");

        $total_amount = DB::select("SELECT SUM(total_remun) as total_amount FROM teacher_report");

        $result = [
            "records"      => $records,
            "total_amount" => $total_amount[0]->total_amount
        ];
        return $result;
    }





}
