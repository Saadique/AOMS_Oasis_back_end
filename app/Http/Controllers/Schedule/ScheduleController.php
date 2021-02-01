<?php

namespace App\Http\Controllers\Schedule;

use App\DailySchedule;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Schedule\ScheduleStoreRequest;
use App\Schedule;
use App\Services\Schedule\ScheduleService;
use App\Services\ServiceGateway;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Comparator\DateTimeComparator;

class ScheduleController extends ApiController
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $schedules = $this->serviceGateway->scheduleService->getAllSchedules();
        return $this->showAll($schedules);
    }

    public function store(Request $request)
    {
        $validatedRequest = $request->all();
        return $this->serviceGateway->scheduleService->createSchedule($validatedRequest);
    }

    public function show(Schedule $schedule)
    {
        return $this->showOne($schedule);
    }

    public function getAllMatchingSchedules(Request $request) {
        $requestBody = $request->all();
        return $this->serviceGateway->scheduleService->findAllMatchingSchedules($requestBody);
    }

    public function getSchedulesByLecture($lectureId) {
        return $this->serviceGateway->scheduleService->getAllSchedulesOfOneLecture($lectureId);
    }

    public function findSchedule(Request $request) {

        $date = $request->input('date');
        $startTime = $request->input('startTime');
        $endTime = $request->input('endTime');
        $roomId = $request->input('roomId');

        return $this->serviceGateway->dailyScheduleService->findScheduleForDay($date, $startTime, $endTime, $roomId);
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($request->has('schedule_start_date') and $request->has('schedule_end_date')) {
                $schedule->schedule_start_date = $request->schedule_start_date;
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

    public function destroy(Schedule $schedule)
    {
        //
    }
}
