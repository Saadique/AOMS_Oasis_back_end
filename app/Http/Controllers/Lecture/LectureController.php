<?php

namespace App\Http\Controllers\Lecture;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Lecture\LectureStoreRequest;
use App\Lecture;
use App\Room;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $user = Auth::user();
        $this->serviceGateway = $serviceGateway;
    }


    public function index()
    {
//        $user = Auth::user();
        $lectures = Lecture::all();
        return $this->showAll($lectures);
    }

    public function getActiveLectures() {
        $user = Auth::user();
        $lectures = Lecture::where('status','active')->get();
        return $lectures;
    }

    public function store(LectureStoreRequest $request)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->lectureService->createLecture($requestBody);
    }


    public function show(Lecture $lecture)
    {
        $user = Auth::user();
        return $this->showOne($lecture);
    }


    public function getLecturesByCourseMedium($courseMediumId){
        $user = Auth::user();
        return $this->serviceGateway->lectureService->getAllLecturesOfOneCourseMedium($courseMediumId);
    }

    public function getAllLecByCourseMedium($courseMediumId) {
        $user = Auth::user();
        return $this->serviceGateway->lectureService->getAllLecByCourseMedium($courseMediumId);
    }

    public function getLecturesBySubject($subjectId){
        $user = Auth::user();
        return $this->serviceGateway->lectureService->getAllLecturesBySubject($subjectId);
    }

    public function getAllStudentsByLecture($lectureId) {
        $user = Auth::user();
        return $this->serviceGateway->lectureService->findAllStudentsByLecture($lectureId);
    }


    public function update(Request $request, Lecture $lecture)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->lectureService->updateLecture($requestBody, $lecture);
    }

    public function changeDeleteStatus($lectureId,$status) {
        $user = Auth::user();
        $lecture = Lecture::findOrFail($lectureId);
        $lecture->status = $status;
        $lecture->save();
        return $lecture;
    }

    public function destroy(Lecture $lecture)
    {

    }
}
