<?php

namespace App\Http\Controllers\DailySchedule;

use App\DailySchedule;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DailySchedule\DailyScheduleStoreRequest;
use App\ScheduleNotifications;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyScheduleController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $user = Auth::user();
        return $this->showAll(DailySchedule::all());
    }

    public function store(DailyScheduleStoreRequest $request)
    {
        $user = Auth::user();
        $validatedRequest = $request->validated();
        return $this->serviceGateway->dailyScheduleService->createOneTimeSchedule($validatedRequest);
    }

    public function show(DailySchedule $dailySchedule)
    {

    }

    public function showByDate($date)
    {
        $user = Auth::user();
        return $this->serviceGateway->dailyScheduleService->findByDate($date);
    }

    public function getStudentScheduleByDate($date, $studentId) {
        $user = Auth::user();
        return $this->serviceGateway->dailyScheduleService->findByDateAndStudent($date, $studentId);
    }

    public function getScheduleByLectureAndDate($date, $lectureId, $studentId) {
        $user = Auth::user();
        return $this->serviceGateway->dailyScheduleService->findByDateAndLecture($date, $lectureId, $studentId);
    }

    public function update(Request $request, DailySchedule $dailySchedule)
    {
        $user = Auth::user();
        return $this->serviceGateway->dailyScheduleService->updateSchedule($request, $dailySchedule);
    }

    public function destroy(DailySchedule $dailySchedule)
    {
        $user = Auth::user();
        if ($dailySchedule->status != 'completed') {
            $notifications = ScheduleNotifications::where('daily_schedule_id', $dailySchedule->id)->get();
            foreach ($notifications as $notification) {
                $notification->delete();
            }
            $dailySchedule->delete();
//        $notification = new ScheduleNotifications();
//        $notification->daily_schedule_id = $dailySchedule->id;
//        $notification->action = "delete";
//        $notification->save();
            return $this->showOne($dailySchedule);
        } else {
            return response()->json("Cannot Delete Completed Schedule", 400);
        }
    }
}
