<?php


namespace App\Services\Lecture;
use App\Lecture;
use App\Payment;
use App\Schedule;
use App\Services\Payment\PaymentService;
use App\Services\Service;
use App\Services\ServiceGateway;
use Illuminate\Support\Facades\DB;

class LectureService extends Service
{

    public $paymentService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    public function createLecture($requestBody) {
        $nameExists = Lecture::where([
            ['name',$requestBody['name']],
            ['course_medium_id',$requestBody['course_medium_id']],
            ['subject_id',$requestBody['subject_id']]
        ])->first();

        if ($nameExists){
            return response("A Lecture With the same name already exists for the selected type,course and subject", 400);
        }
        $lecture = Lecture::create($requestBody);
        $this->paymentService->createPayment($lecture,$requestBody);
        return $this->showOne($lecture);
    }

    public function updateLecture($request, Lecture $lecture) {
        $nameExists = Lecture::where([
            ['id','!=',$lecture->id],
            ['name',$request['name']],
            ['course_medium_id',$request['course_medium_id']],
            ['subject_id',$request['subject_id']]
        ])->first();

        if ($nameExists){
            return response("A Lecture With the same name already exists for the selected type,course and subject", 400);
        }

        $lecture->name = $request['name'];
        $lecture->subject_id = $request['subject_id'];
        $lecture->type = $request['type'];
        $lecture->teacher_id = $request['teacher_id'];
        $lecture->update();
        return $lecture;
    }

    public function getAllLecturesOfOneCourseMedium($courseMediumId){
        return Lecture::where([
            ['course_medium_id', $courseMediumId],
            ['status', 'active']
        ])->get();

    }

    public function getAllLecByCourseMedium($courseMediumId) {
        return Lecture::where('course_medium_id', $courseMediumId)->get();
    }

    public function getAllLecturesBySubject($subjectId){
        return Lecture::where([
            ['subject_id', $subjectId],
            ['status', 'active']
        ])->get();
    }

    public function findAllStudentsByLecture($lectureId) {
        $lecture = Lecture::findOrFail($lectureId);
        return $lecture->students;
    }
}
