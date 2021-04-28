<?php


namespace App\Http\Controllers;


use App\Services\ServiceGateway;

class ReportController extends Controller
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    //=========================Student Reports=============================//


    //all time
    public function getAllStudentFeeRecords() {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFees();
        return $result;
    }

    public function getAllStudentFeeRecordsByMonth($year, $month) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeesByMonth($year, $month);
        return $result;
    }

    public function getAllStudentFeeRecordsByDate($fromDate, $toDate) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeesByDate($fromDate, $toDate);
        return $result;
    }


    //course
    public function getAllStudentFeeRecordByCourse($courseId) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForCourse($courseId);
        return $result;
    }

    public function getAllStudentFeeRecordForCourseByMonth($courseId, $year, $month) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForCourseByMonth($courseId, $year, $month);
        return $result;
    }

    public function getAllStudentFeeRecordForCourseByDate($courseId, $from_date, $to_date) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForCourseByDate($courseId,  $from_date, $to_date);
        return $result;
    }



    //teacher
    public function getAllStudentFeeRecordByTeacher($teacherId) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForTeacher($teacherId);
        return $result;
    }

    public function getAllStudentFeeRecordForTeacherByMonth($teacherId, $year, $month) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForTeacherByMonth($teacherId, $year, $month);
        return $result;
    }

    public function getAllStudentFeeRecordForTeacherByDate($teacherId, $from_date, $to_date) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForTeacherByDate($teacherId,  $from_date, $to_date);
        return $result;
    }


    //lecture
    public function getAllStudentFeeRecordByLecture($lectureId) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForLecture($lectureId);
        return $result;
    }

    public function getAllStudentFeeRecordForLectureByMonth($lectureId, $year, $month) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForLectureByMonth($lectureId, $year, $month);
        return $result;
    }

    public function getAllStudentFeeRecordForLectureByDate($lectureId, $from_date, $to_date) {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFeeForLectureByDate($lectureId,  $from_date, $to_date);
        return $result;
    }


    //=========================Teacher Remuneration Reports=============================//


    //all
    public function getRemunerationsPaidForTeachers(){
        return $this->serviceGateway->teacherRemunReportService->findRemunerationsPaidForTeachers();
    }


    //by teacher
    public function getRemunerationsPaidForTeachersByTeacher($teacherId){
        return $this->serviceGateway->teacherRemunReportService->findRemunerationsPaidForTeachersByTeacher($teacherId);
    }


    //by lecture
    public function getRemunerationsPaidForTeachersByLecture($lectureId){
        return $this->serviceGateway->teacherRemunReportService->findRemunerationsPaidForTeachersByLecture($lectureId);
    }


    //by course
    public function getRemunerationsPaidForTeachersByCourse($courseId){
        return $this->serviceGateway->teacherRemunReportService->findRemunerationsPaidForTeachersByCourse($courseId);
    }

    //=========================Teacher Institute Share Report=============================//


    //all time
    public function getAllShareRecords() {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFees();
        return $result;
    }

    public function getAllShareRecordsByMonth($year, $month) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeesByMonth($year, $month);
        return $result;
    }

    public function getAllShareRecordsByDate($fromDate, $toDate) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeesByDate($fromDate, $toDate);
        return $result;
    }


    //course
    public function getAllShareRecordsByCourse($courseId) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForCourse($courseId);
        return $result;
    }

    public function getAllShareRecordsForCourseByMonth($courseId, $year, $month) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForCourseByMonth($courseId, $year, $month);
        return $result;
    }

    public function getAllShareRecordsForCourseByDate($courseId, $from_date, $to_date) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForCourseByDate($courseId,  $from_date, $to_date);
        return $result;
    }



    //teacher
    public function getAllShareRecordsByTeacher($teacherId) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForTeacher($teacherId);
        return $result;
    }

    public function getAllShareRecordsForTeacherByMonth($teacherId, $year, $month) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForTeacherByMonth($teacherId, $year, $month);
        return $result;
    }

    public function getAllShareRecordsForTeacherByDate($teacherId, $from_date, $to_date) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForTeacherByDate($teacherId,  $from_date, $to_date);
        return $result;
    }


    //lecture
    public function getAllShareRecordsByLecture($lectureId) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForLecture($lectureId);
        return $result;
    }

    public function getAllShareRecordsForLectureByMonth($lectureId, $year, $month) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForLectureByMonth($lectureId, $year, $month);
        return $result;
    }

    public function getAllShareRecordsForLectureByDate($lectureId, $from_date, $to_date) {
        $result = $this->serviceGateway->teacherInstituteShareReportService->totalStudentFeeForLectureByDate($lectureId,  $from_date, $to_date);
        return $result;
    }
}
