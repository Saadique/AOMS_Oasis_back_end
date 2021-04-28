<?php


namespace App\Services;
use App\Services\Attendance\AttendanceService;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterService;
use App\Services\Course\CourseService;
use App\Services\Course\MediumService;
use App\Services\CourseMedium\CourseMediumService;
use App\Services\DailySchedule\DailyScheduleService;
use App\Services\Lecture\LectureLessonsService;
use App\Services\Lecture\LectureService;
use App\Services\Payment\PaymentService;
use App\Services\Payment_Scheme\PaymentSchemeService;
use App\Services\PaymentStudent\PaymentStudentService;
use App\Services\Registration\RegistrationService;
use App\Services\Room\RoomService;
use App\Services\Schedule\ScheduleNotificationService;
use App\Services\Schedule\ScheduleService;
use App\Services\Student\StudentService;
use App\Services\StudentPayments\StudentFeeReportsService;
use App\Services\StudentPayments\StudentPaymentsService;
use App\Services\StudentPayments\TeacherInstituteShareReport;
use App\Services\StudentPayments\TeacherRemunerationReportService;
use App\Services\StudentSchemeLecture\StudentSchemeLectureService;
use App\Services\Subject\SubjectService;
use App\Services\Teacher\TeacherService;
use App\Services\User\UserService;

class ServiceGateway
{
    public $mediumService;
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
    public $registerService;
    public $attendanceService;
    public $scheduleNotificationService;
    public $lectureLessonService;
    public $userService;
    public $teacherRemunReportService;
    public $studentFeeReportService;
    public $teacherInstituteShareReportService;



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
        RoomService                 $roomService,
        RegisterService             $registerService,
        AttendanceService           $attendanceService,
        ScheduleNotificationService $scheduleNotificationService,
        LectureLessonsService       $lectureLessonService,
        MediumService               $mediumService,
        UserService                 $userService,
        TeacherRemunerationReportService  $teacherReportService,
        StudentFeeReportsService          $studentFeeReportService,
        TeacherInstituteShareReport       $teacherInstituteShareReportService)
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
        $this->registerService               = $registerService;
        $this->attendanceService             = $attendanceService;
        $this->scheduleNotificationService   = $scheduleNotificationService;
        $this->lectureLessonService          = $lectureLessonService;
        $this->mediumService                 = $mediumService;
        $this->userService                   = $userService;
        $this->teacherRemunReportService     = $teacherReportService;
        $this->studentFeeReportService       = $studentFeeReportService;
        $this->teacherInstituteShareReportService = $teacherInstituteShareReportService;
    }

}
