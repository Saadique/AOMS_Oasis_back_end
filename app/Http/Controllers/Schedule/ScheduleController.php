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
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $schedules = $this->serviceGateway->scheduleService->getAllSchedules();
        return $this->showAll($schedules);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validatedRequest = $request->all();
        return $this->serviceGateway->scheduleService->createSchedule($validatedRequest);
    }

    public function show(Schedule $schedule)
    {
        $user = Auth::user();
        return $this->showOne($schedule);
    }

    public function getAllMatchingSchedules(Request $request) {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->scheduleService->findAllMatchingSchedules($requestBody);
    }

    public function getSchedulesByLecture($lectureId) {
        return $this->serviceGateway->scheduleService->getAllSchedulesOfOneLecture($lectureId);
    }


    public function findSchedule(Request $request) {
        $user = Auth::user();
        $date = $request->input('date');
        $startTime = $request->input('startTime');
        $endTime = $request->input('endTime');
        $roomId = $request->input('roomId');

        return $this->serviceGateway->dailyScheduleService->findScheduleForDay($date, $startTime, $endTime, $roomId);
    }

    public function update(Request $request, Schedule $schedule)
    {

    }

    public function destroy(Schedule $schedule)
    {
        //
    }
}
