<?php

namespace App\Http\Controllers\Lecture;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Lecture\LectureStoreRequest;
use App\Lecture;
use App\Room;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class LectureController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }


    public function index()
    {
        $lectures = Lecture::all();
        return $this->showAll($lectures);
    }

    public function getActiveLectures() {
        $lectures = Lecture::where('status','active')->get();
        return $lectures;
    }

    public function store(LectureStoreRequest $request)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->lectureService->createLecture($requestBody);
    }


    public function show(Lecture $lecture)
    {
        return $this->showOne($lecture);
    }


    public function getLecturesByCourseMedium($courseMediumId){

        return $this->serviceGateway->lectureService->getAllLecturesOfOneCourseMedium($courseMediumId);
    }

    public function getAllLecByCourseMedium($courseMediumId) {
        return $this->serviceGateway->lectureService->getAllLecByCourseMedium($courseMediumId);
    }

    public function getLecturesBySubject($subjectId){
        return $this->serviceGateway->lectureService->getAllLecturesBySubject($subjectId);
    }

    public function getAllStudentsByLecture($lectureId) {
        return $this->serviceGateway->lectureService->findAllStudentsByLecture($lectureId);
    }


    public function update(Request $request, Lecture $lecture)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->lectureService->updateLecture($requestBody, $lecture);
    }

    public function changeDeleteStatus($lectureId,$status) {
        $lecture = Lecture::findOrFail($lectureId);
        $lecture->status = $status;
        $lecture->save();
        return $lecture;
    }

    public function destroy(Lecture $lecture)
    {

    }
}
