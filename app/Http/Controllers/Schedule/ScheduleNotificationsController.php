<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\ScheduleNotifications;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleNotificationsController extends Controller
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function getTeacherUptoDateNotifications($teacherId) {
        $user = Auth::user();
        return $this->serviceGateway->scheduleNotificationService->findTeacherUptoDateNotifications($teacherId);
    }

    public function getStudentUptoDateNotifications($studentId) {
        $user = Auth::user();
        return $this->serviceGateway->scheduleNotificationService->findStudentUptoDateNotifications($studentId);
    }

    public function index()
    {
        //
    }



    public function store(Request $request)
    {
        //
    }


    public function show(ScheduleNotifications $scheduleNotifications)
    {
        //
    }





    public function update(Request $request, ScheduleNotifications $scheduleNotifications)
    {
        //
    }


    public function destroy(ScheduleNotifications $scheduleNotifications)
    {
        //
    }
}
