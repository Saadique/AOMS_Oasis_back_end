<?php


namespace App\Services;
use App\Services\Auth\LoginService;
use App\Services\Course\CourseService;
use App\Services\CourseMedium\CourseMediumService;
use App\Services\DailySchedule\DailyScheduleService;
use App\Services\Lecture\LectureService;
use App\Services\Payment\PaymentService;
use App\Services\Payment_Scheme\PaymentSchemeService;
use App\Services\PaymentStudent\PaymentStudentService;
use App\Services\Registration\RegistrationService;
use App\Services\Room\RoomService;
use App\Services\Schedule\ScheduleService;
use App\Services\Student\StudentService;
use App\Services\StudentPayments\StudentPaymentsService;
use App\Services\StudentSchemeLecture\StudentSchemeLectureService;
use App\Services\Subject\SubjectService;
use App\Services\Teacher\TeacherService;

class ServiceGateway
{
    public $courseService;
    public $scheduleService;
    public $dailyScheduleService;
    public $subjectService;
    public $lectureService;
    public $studentService;
    public $paymentService;
    public $paymentStudentService;
    public $registrationService;
    public $courseMediumService;
    public $teacherService;
    public $loginService;
    public $paymentSchemeService;
    public $studentPaymentsService;
    public $studentSchemeLectureService;
    public $roomService;

    public function __construct(
        CourseService               $courseService,
        ScheduleService             $scheduleService,
        DailyScheduleService        $dailyScheduleService,
        SubjectService              $subjectService,
        LectureService              $lectureService,
        StudentService              $studentService,
        PaymentService              $paymentService,
        PaymentStudentService       $paymentStudentService,
        RegistrationService         $registrationService,
        CourseMediumService         $courseMediumService,
        TeacherService              $teacherService,
        LoginService                $loginService,
        PaymentSchemeService        $paymentSchemeService,
        StudentPaymentsService      $studentPaymentsService,
        StudentSchemeLectureService $studentSchemeLectureService,
        RoomService                 $roomService)
    {
        $this->courseService                 = $courseService;
        $this->scheduleService               = $scheduleService;
        $this->dailyScheduleService          = $dailyScheduleService;
        $this->subjectService                = $subjectService;
        $this->lectureService                = $lectureService;
        $this->studentService                = $studentService;
        $this->paymentService                = $paymentService;
        $this->paymentStudentService         = $paymentStudentService;
        $this->registrationService           = $registrationService;
        $this->courseMediumService           = $courseMediumService;
        $this->teacherService                = $teacherService;
        $this->loginService                  = $loginService;
        $this->paymentSchemeService          = $paymentSchemeService;
        $this->studentPaymentsService        = $studentPaymentsService;
        $this->studentSchemeLectureService   = $studentSchemeLectureService;
        $this->roomService                   = $roomService;
    }

}
