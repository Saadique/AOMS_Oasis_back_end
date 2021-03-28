<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\ApiController;
use App\Services\ServiceGateway;
use App\Teacher;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->teacherService->createTeacher($requestBody);
    }

    public function show(Teacher $teacher)
    {
        //
    }

    public function getAllLecturesOfTeacher($teacherId) {
        return $this->serviceGateway->teacherService->findAllLecturesByTeacher($teacherId);
    }

    public function getMonthlyLecRemuneration($teacherId, $lectureId, $month){
        return $this->serviceGateway->teacherService->findMonthlyLecRemuneration($teacherId, $lectureId, $month);
    }

    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    public function destroy(Teacher $teacher)
    {
        //
    }
}
