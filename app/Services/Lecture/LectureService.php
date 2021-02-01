<?php


namespace App\Services\Lecture;
use App\Lecture;
use App\Services\Payment\PaymentService;
use App\Services\Service;
use App\Services\ServiceGateway;

class LectureService extends Service
{

    public $paymentService;



    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    public function createLecture($requestBody) {
        $lecture = Lecture::create($requestBody);
        $this->paymentService->createPayment($lecture,$requestBody);
        return $this->showOne($lecture);
    }

    public function getAllLecturesOfOneCourseMedium($courseMediumId){
        return Lecture::where('course_medium_id', $courseMediumId)->get();
    }

    public function getAllLecturesBySubject($subjectId){
        return Lecture::where('subject_id', $subjectId)->get();
    }

    public function findAllStudentsByLecture($lectureId) {
        $lecture = Lecture::findOrFail($lectureId);
        return $lecture->students;
    }
}
