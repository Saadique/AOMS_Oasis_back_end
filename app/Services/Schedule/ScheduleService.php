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
        $day = $requestBody['day'];
        $teacher_id = $requestBody['teacher_id'];


        $existingTeacherSchedules = DB::select("SELECT * FROM schedules WHERE day='$day'
                             AND lecture_id IN (SELECT id FROM lectures WHERE teacher_id=$teacher_id)");

        $teacherStartTimeMatch = false;
        $teacherEndTimeMatch = false;
        if (count($existingTeacherSchedules)!=0){
            foreach ($existingTeacherSchedules as $teacherSchedule)
            {
                $teacherStartTimeMatch = $this->TimeIsBetweenTwoTimes($teacherSchedule->start_time,
                    $teacherSchedule->end_time, $requestBody['start_time']);

                $teacherEndTimeMatch = $this->TimeIsBetweenTwoTimes($teacherSchedule->start_time,
                    $teacherSchedule->end_time, $requestBody['end_time']);
            }
        }

        if ($teacherStartTimeMatch or $teacherEndTimeMatch) {
            return $this->errorResponse("This teacher Has Another lecture at this schedule slot",400);
        }

        //checks whether a schedule previously exists in the day and room the user requested
        $similarSchedules = DB::table('schedules')
            ->where([
                ['day',$requestBody['day']],
                ['room_id',$requestBody['room_id']],
                ['schedule_end_date','>',$requestBody['schedule_start_date']]
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
            $day = date('l',$schedule_start_date);
            $schedule->day = $day;
            $schedule->save();
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


//    public function createSchedule($requestBody)
//    {
//        //checks whether a schedule previously exists in the day and room the user requested
//        $similarSchedules = DB::table('schedules')
//            ->where([
//                ['day',$requestBody['day']],
//                ['room_id',$requestBody['room_id']],
////                ['schedule_end_date','<',$requestBody['schedule_start_date']]
//            ])->get();
//
//        $startTimeMatch = false;
//        $endTimeMatch = false;
//        //if schedules exists previously, then checks whether it clashes with the requested time by user
//        if ($similarSchedules != null) {
//            foreach ($similarSchedules as $similarSchedule)
//            {
//                $startTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
//                    $similarSchedule->end_time, $requestBody['start_time']);
//
//                $endTimeMatch = $this->TimeIsBetweenTwoTimes($similarSchedule->start_time,
//                    $similarSchedule->end_time, $requestBody['end_time']);
//            }
//        }
//        //if no clashes, then creates schedule. And dailySchedules by adding 7 days from start date.
//        //stops the loop when it reaches end date.
//        if (!($startTimeMatch or $endTimeMatch)) {
//            $schedule = Schedule::create($requestBody);
//            $schedule_start_date = strtotime($schedule->schedule_start_date);
//            $schedule_end_date = strtotime($schedule->schedule_end_date);
//            $end_date_compare_format = date('Y-m-d',$schedule_end_date);
//            $endDateObj = new \DateTime($end_date_compare_format);
//            $date_plus_conv = null;
//            $plusSevenFromDate = $schedule_start_date;
//            $nextDate = $schedule->schedule_start_date;
//            $lastUpdatedObj = null;
//
//            while($endDateObj >= $lastUpdatedObj)
//            {
//                $dailySchedule = new DailySchedule();
//                $dailySchedule->day = $schedule->day;
//                $dailySchedule->date = $nextDate;
//                $dailySchedule->start_time = $schedule->start_time;
//                $dailySchedule->end_time = $schedule->end_time;
//                $dailySchedule->room_id = $schedule->room_id;
//                $dailySchedule->schedule_id = $schedule->id;
//                $dailySchedule->lecture_id = $schedule->lecture_id;
//                $dailySchedule->save();
//                $date_plus = date('m/d/Y', strtotime('+ 7 days', $plusSevenFromDate));
//                $date_plus_conv = strtotime($date_plus);
//                $nextDate = date('Y-m-d',$date_plus_conv);
//                $lastUpdatedObj = new \DateTime($nextDate);
//                $plusSevenFromDate = $date_plus_conv;
//            }
//            return $this->showOne($schedule);
//        }
//        return $this->errorResponse("THIS_TIME_IS_NOT_FREE",400);
//    }

    public function getAllSchedulesOfOneLecture($lectureId) {
        return Schedule::where('lecture_id', $lectureId)->with('room')->get()->first();
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

    public function update($request, DailySchedule $schedule){
        $result = DB::select("SELECT * FROM daily_schedules HAVING
                                    date=(SELECT MAX(date) from daily_schedules
                                    WHERE schedule_id=$schedule->schedule_id)");

        $start_date = $result[0]->date;
        echo $start_date;

        if ($request['all_change']== true) {

        }

        if ($request->has('schedule_end_date')) {
            $schedule->schedule_end_date = $request->schedule_end_date;
            $schedule->start_time = $request->start_time;
            $schedule->end_time = $request->end_time;
            $schedule->room_id = $request->room_id;
            $schedule->update();

            $schedule_start_date = strtotime($schedule->schedule_start_date);

            $schedule_end_date = strtotime($schedule->schedule_end_date);

            $end_date_compare_format = date('Y-m-d',$schedule_end_date);
            $endDateObj = new \DateTime($end_date_compare_format);


            $date_plus_conv = null;
            $plusSevenFromDate = $schedule_start_date;
            $lastupdated = null;
            $lastUpdatedObj = null;

            while($endDateObj > $lastUpdatedObj) {
                $date_plus = date('m/d/Y', strtotime('+ 7 days', $plusSevenFromDate));
                $date_plus_conv = strtotime($date_plus);
                $schedule_starting_month = date("m",$date_plus_conv);
                $lastupdated = date('Y-m-d',$date_plus_conv);
                $dailySchedule = new DailySchedule();
                $dailySchedule->day = $schedule->day;
                $dailySchedule->date = $lastupdated;
                $dailySchedule->start_time = $schedule->start_time;
                $dailySchedule->end_time = $schedule->end_time;
                $dailySchedule->room_id = $schedule->room_id;
                $dailySchedule->schedule_id =$schedule->id;
                $dailySchedule->save();
                $lastUpdatedObj = new \DateTime($lastupdated);
                $plusSevenFromDate = $date_plus_conv;
            }
        } else {
            if ($request->has('start_time') and $request->has('end_time')){
                $schedule->start_time = $request->start_time;
                $schedule->end_time = $request->end_time;
                $schedule->update();
                DB::update(
                    'update daily_schedules
                          set start_time = ?,
                          end_time = ?
                          where schedule_id = ?',[$request->start_time, $request->end_time, $schedule->id]);
            }

            if ($request->has('room_id')) {
                $schedule->room_id = $request->room_id;
                $schedule->update();
                DB::update('update daily_schedules set room_id = ?
                where schedule_id = ?',[$request->room_id, $schedule->id]);
            }
        }
        return $this->showOne($schedule);
    }





}
