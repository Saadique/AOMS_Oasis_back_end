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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $user = Auth::user();
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

        DB::statement("CREATE OR REPLACE VIEW student_lecture_detail AS SELECT lectures.id as student_id, lectures.name as lecture_name
         FROM lecture_student INNER JOIN students ON students.id=lecture_student.student_id
         INNER JOIN lectures ON lectures.id=lecture_student.lecture_id");

        $student_lecture_count = DB::select("SELECT COUNT(student_id) as student_count, lecture_name FROM student_lecture_detail GROUP BY lecture_name");

        $result = [
            "counts" => $countArray,
            "student_lecture_count" => $student_lecture_count
        ];

        return $result;
    }


//CREATE OR REPLACE VIEW student_lecture_detail AS SELECT lectures.id as student_id, lectures.name as lecture_name FROM lecture_student INNER JOIN students ON students.id=lecture_student.student_id INNER JOIN lectures ON lectures.id=lecture_student.lecture_id


    public function store(Request $request)
    {
        $user = Auth::user();
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
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->userService->updateAdmin($requestBody, $admin);
    }


    public function destroy(Admin $admin)
    {
        //
    }
}
