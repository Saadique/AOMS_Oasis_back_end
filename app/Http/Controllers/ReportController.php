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

}
