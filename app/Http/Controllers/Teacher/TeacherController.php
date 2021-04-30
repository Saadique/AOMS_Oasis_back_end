<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\ApiController;
use App\Services\ServiceGateway;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        return Teacher::all();
    }

    public function getAllActiveTeachers() {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findAllActiveTeachers();
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->teacherService->createTeacher($requestBody);
    }

    public function show(Teacher $teacher)
    {
        $user = Auth::user();
        return $teacher;
    }

    public function getAllLecturesOfTeacher($teacherId) {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findAllLecturesByTeacher($teacherId);
    }

    public function getLectureMonths($lectureId){
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findLectureMonths($lectureId);
    }

    public function getMonthlyRemuneration($lectureId, $year, $month) {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findMonthlyRemunerations($lectureId, $year, $month);
    }

    public function getMonthlyRemunerationPaid($teacherId, $lectureId, $year, $month) {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findMonthlyRemunerationsPaid($teacherId, $lectureId, $year, $month);
    }

    public function getTeacherSchedulesTimetable($teacherId) {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findTeacherScheduleTimetable($teacherId);
    }

    public function getTeacherTotalMonthlyIncome($teacherId) {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findTeacherTotalMonthlyIncome($teacherId);
    }

    public function getTeacherTotalMonthlyIncomeForLecture($lectureId, $teacherId) {
        $user = Auth::user();
        return $this->serviceGateway->teacherService->findTeacherTotalMonthlyIncomeForLecture($lectureId, $teacherId);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->teacherService->updateTeacher($requestBody,$teacher);
    }



    public function destroy(Teacher $teacher)
    {
        //
    }
}
