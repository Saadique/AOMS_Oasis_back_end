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

    public function getAllStudentFeeRecords() {
        $result = $this->serviceGateway->studentFeeReportService->totalStudentFees();
        return $result;
    }

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
}
