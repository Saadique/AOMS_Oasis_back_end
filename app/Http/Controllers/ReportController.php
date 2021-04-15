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
}
