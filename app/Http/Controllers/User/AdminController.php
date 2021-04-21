<?php

namespace App\Http\Controllers\User;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Lecture;
use App\Services\ServiceGateway;
use App\Student;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

    public function getAdminDashboardData() {
        $activeStudentsCount = Student::where("status","active")->get()->count();
        $activeTeacherCount = Teacher::where("status","active")->get()->count();
        $userAccountsCount = User::where("status","active")->get()->count();
        $lectureCount = Lecture::where("status","active")->get()->count();

        $countArray = [
            "student_count"=>$activeStudentsCount,
            "teacher_count"=>$activeTeacherCount,
            "user_count"=>$userAccountsCount,
            "lecture_count"=>$lectureCount
        ];

        return $countArray;
    }


    public function store(Request $request)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->userService->createAdmin($requestBody);
    }


    public function show(Admin $admin)
    {
        //
    }


    public function edit(Admin $admin)
    {
        //
    }


    public function update(Request $request, Admin $admin)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->userService->updateAdmin($requestBody, $admin);
    }


    public function destroy(Admin $admin)
    {
        //
    }
}
