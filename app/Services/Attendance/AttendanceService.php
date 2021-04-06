<?php


namespace App\Services\Attendance;


use App\Attendance;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

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

    public function findStudentAttendancesOfLecture($lecture_id, $date) {
        DB::statement("CREATE OR REPLACE VIEW students_attendances AS
                            SELECT * FROM attendances WHERE daily_schedule_id IN
                            (SELECT id from daily_schedules WHERE date='$date' AND lecture_id=$lecture_id)");

        $student_attendances =  DB::select("SELECT students.name, students.registration_no, daily_schedules.date,
                                            students_attendances.attendance_status FROM students_attendances INNER JOIN
                                                students ON students.id=students_attendances.student_id
                                             INNER JOIN daily_schedules ON daily_schedules.id=students_attendances.daily_schedule_id");

        return $student_attendances;
    }
}
