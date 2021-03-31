<?php


namespace App\Services\Attendance;


use App\Attendance;
use App\Services\Service;

class AttendanceService extends Service
{
    public function markAttendance($request) {
        $attendanceExists = Attendance::where([
            ['student_id', $request['student_id']],
            ['daily_schedule_id', $request['daily_schedule_id']]
        ])->first();

        if ($attendanceExists==null) {
            $attendance = Attendance::create($request);
        } else {
            $attendance = $attendanceExists;
            $attendance->attendance_status = $request['attendance_status'];
            $attendance->save();
        }
        return $attendance;
    }
}
