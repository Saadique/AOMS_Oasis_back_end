<?php


namespace App\Services\DailySchedule;
use App\Attendance;
use App\DailySchedule;
use App\Services\Service;
use App\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyScheduleService extends Service
{
    public function createOneTimeSchedule($requestBody)
    {
        $similarSchedules = DB::table('daily_schedules')
            ->where([
                ['date',$requestBody['date']],
                ['room_id',$requestBody['room_id']]
            ])->get();
        $startTimeMatch = false;
        $endTimeMatch = false;
        if ($similarSchedules != null) {
            foreach ($similarSchedules as $similarSchedule) {
                $startTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['start_time']);

                $endTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['end_time']);
            }
        }

        if (!($startTimeMatch or $endTimeMatch))
        {
            $dailySchedule = DailySchedule::create($requestBody);
            return $this->showOne($dailySchedule);
        } else {
            return $this->errorResponse("THIS_TIME_IS_NOT_FREE",400);
        }
    }

    public function findByDate($date)
    {
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        $dailySchedules = DailySchedule::where('date', $formattedDate)
            ->with('lecture.teacher')
            ->get();
        return $dailySchedules;
    }

    public function findByDateAndStudent($date, $studentId) {
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        $student = Student::findOrFail($studentId);
        $lecturesOfStudent = $student->lectures;
        $lectureIds = [];

        foreach ($lecturesOfStudent as $lecture) {
            array_push($lectureIds, $lecture->id);
        }

        $dailySchedules = DailySchedule::where('date', $formattedDate)
            ->whereIn('lecture_id', $lectureIds)
            ->with('lecture.teacher')
            ->get();

        return $dailySchedules;
    }

    public function findScheduleForDay($date, $startTime, $endTime, $roomId) {
        $allSchedules = DB::table('daily_schedules')
            ->where([
                ['date',$date],
                ['room_id',$roomId]
            ])->get();
    }

    public function updateSchedule($requestBody, DailySchedule $dailySchedule) {

        $similarSchedules = DB::table('daily_schedules')
            ->where([
                ['id','!=', $dailySchedule->id],
                ['date',$requestBody['date']],
                ['room_id',$requestBody['room_id']]
            ])->get();

        $startTimeMatch = false;
        $endTimeMatch = false;

        if ($similarSchedules != null) {
            foreach ($similarSchedules as $similarSchedule) {
                $startTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['start_time']);

                $endTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['end_time']);
            }
        }

        if (!($startTimeMatch or $endTimeMatch))
        {
            DB::update(
                'update daily_schedules
                      set date = ?,
                      start_time = ?,
                      end_time = ?,
                      room_id = ?
                      where id = ?',
                [$requestBody->date, $requestBody->start_time, $requestBody->end_time, $requestBody->room_id,
                $dailySchedule->id]);

            return $this->showOne($dailySchedule);
        } else {
            return $this->errorResponse("THIS_TIME_IS_NOT_FREE",400);
        }
    }


    public function findByDateAndLecture($date, $lectureId, $studentId) {
        $dailySchedules = DailySchedule::where([
            ['date', $date],
            ['lecture_id', $lectureId]
        ])->with('room')->get();

        foreach ($dailySchedules as $dailySchedule) {
            $dailySchedule->{"attendance"} = null;
        }
        foreach ($dailySchedules as $dailySchedule) {
            $attendance = Attendance::where([
                ['daily_schedule_id', $dailySchedule->id],
                ['student_id', $studentId]
            ])->get()->first();

            if ($attendance) {
                $dailySchedule->attendance = $attendance->attendance_status;
            }
        }

        return $dailySchedules;
    }

}
