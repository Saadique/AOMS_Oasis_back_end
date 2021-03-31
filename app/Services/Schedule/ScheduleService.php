<?php

namespace App\Services\Schedule;
use App\DailySchedule;
use App\Schedule;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class ScheduleService extends Service
{
    public function createSchedule($requestBody)
    {
        //checks whether a schedule previously exists in the day and room the user requested
        $similarSchedules = DB::table('schedules')
            ->where([
                ['day',$requestBody['day']],
                ['room_id',$requestBody['room_id']]
            ])->get();

        $startTimeMatch = false;
        $endTimeMatch = false;
        //if schedules exists previously, then checks whether it clashes with the requested time by user
        if ($similarSchedules != null) {
            foreach ($similarSchedules as $similarSchedule)
            {
                $startTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['start_time']);

                $endTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['end_time']);
            }
        }
        //if no clashes, then creates schedule. And dailySchedules by adding 7 days from start date.
        //stops the loop when it reaches end date.
        if (!($startTimeMatch or $endTimeMatch)) {
            $schedule = Schedule::create($requestBody);
            $schedule_start_date = strtotime($schedule->schedule_start_date);
            $schedule_end_date = strtotime($schedule->schedule_end_date);
            $end_date_compare_format = date('Y-m-d',$schedule_end_date);
            $endDateObj = new \DateTime($end_date_compare_format);
            $date_plus_conv = null;
            $plusSevenFromDate = $schedule_start_date;
            $nextDate = $schedule->schedule_start_date;
            $lastUpdatedObj = null;

            while($endDateObj >= $lastUpdatedObj)
            {
                $dailySchedule = new DailySchedule();
                $dailySchedule->day = $schedule->day;
                $dailySchedule->date = $nextDate;
                $dailySchedule->start_time = $schedule->start_time;
                $dailySchedule->end_time = $schedule->end_time;
                $dailySchedule->room_id = $schedule->room_id;
                $dailySchedule->schedule_id = $schedule->id;
                $dailySchedule->lecture_id = $schedule->lecture_id;
                $dailySchedule->save();
                $date_plus = date('m/d/Y', strtotime('+ 7 days', $plusSevenFromDate));
                $date_plus_conv = strtotime($date_plus);
                $nextDate = date('Y-m-d',$date_plus_conv);
                $lastUpdatedObj = new \DateTime($nextDate);
                $plusSevenFromDate = $date_plus_conv;
            }
            return $this->showOne($schedule);
        }
        return $this->errorResponse("THIS_TIME_IS_NOT_FREE",400);
    }

    public function getAllSchedulesOfOneLecture($lectureId) {
        return Schedule::where('lecture_id', $lectureId)->get()->first();
    }

    public function getAllSchedules() {
        return Schedule::all();
    }

    public function findAllMatchingSchedules($requestBody) {
        $similarSchedules = DB::table('schedules')
            ->where([
                ['schedule_start_date',$requestBody['schedule_start_date']],
                ['room_id',$requestBody['room_id']]
            ])->get();

        $startTimeMatch = false;
        $endTimeMatch = false;

        if ($similarSchedules != null) {
            foreach ($similarSchedules as $similarSchedule)
            {
                $startTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['start_time']);

                $endTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
                    $similarSchedule->end_time, $requestBody['end_time']);
            }
            if (($startTimeMatch or $endTimeMatch)){
                return response()->json($similarSchedules,200);
            } else {
                return response()->json(null,200);
            }
        } else {
            return response()->json(null,200);
        }

    }





}
