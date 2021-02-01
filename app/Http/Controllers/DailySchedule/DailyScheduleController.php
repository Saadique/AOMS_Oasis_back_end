<?php

namespace App\Http\Controllers\DailySchedule;

use App\DailySchedule;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DailySchedule\DailyScheduleStoreRequest;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class DailyScheduleController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        return $this->showAll(DailySchedule::all());
    }

    public function store(DailyScheduleStoreRequest $request)
    {
        $validatedRequest = $request->validated();
        return $this->serviceGateway->dailyScheduleService->createOneTimeSchedule($validatedRequest);
    }

    public function show(DailySchedule $dailySchedule)
    {

    }

    public function showByDate($date)
    {
        return $this->serviceGateway->dailyScheduleService->findByDate($date);
    }

    public function getStudentScheduleByDate($date, $studentId) {
        return $this->serviceGateway->dailyScheduleService->findByDateAndStudent($date, $studentId);
    }

    public function update(Request $request, DailySchedule $dailySchedule)
    {
        return $this->serviceGateway->dailyScheduleService->updateSchedule($request, $dailySchedule);
    }

    public function destroy(DailySchedule $dailySchedule)
    {
        $dailySchedule->delete();
        return $this->showOne($dailySchedule);
    }
}
