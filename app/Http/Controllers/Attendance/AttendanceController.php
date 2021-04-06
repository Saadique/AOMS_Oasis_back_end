<?php

namespace App\Http\Controllers\Attendance;

use App\Attendance;
use App\Http\Controllers\ApiController;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class AttendanceController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {

    }

    public function store(Request $request)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->attendanceService->markAttendance($requestBody);
    }

    public function getStudentsAttendancesOfLecture($lecture_id, $date) {
        return $this->serviceGateway->attendanceService->findStudentAttendancesOfLecture($lecture_id, $date);
    }

    public function show(Attendance $attendance)
    {

    }

    public function update(Request $request, Attendance $attendance)
    {

    }


    public function destroy(Attendance $attendance)
    {

    }
}
