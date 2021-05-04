<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('cors', 'json.response', 'auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::post('user/login', 'User\UserController@login');
//Route::post('user/register', 'User\UserController@register');

//Route::group(['middleware' => 'auth:api'], function () {
//    Route::post('user/details', 'User\UserController@details');
//    Route::post('user/logout', 'User\UserController@logout');
//});

Route::middleware(['cors', 'json.response', 'auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // public routes
    Route::post('user/login', 'User\UserController@login')->name('login.api');
    Route::post('user/register', 'User\UserController@register')->name('register.api');
    Route::get('users/reset_password/mail_code/{username}', 'User\UserController@mailResetPasswordCode');
    Route::post('users/reset_password/code', 'User\UserController@validateCode');
    Route::post('users/reset_password/reset', 'User\UserController@resetPassword');
});

Route::middleware('auth:api')->group(function () {
    //    Route::get('teacher/lectures/{teacherId}', 'Teacher\TeacherController@getAllLecturesOfTeacher')
//        ->middleware('api.admin');

    Route::post('user/logout', 'User\UserController@logout')->name('logout.api');
    Route::post('user/details', 'User\UserController@details');

    //medium apis
    Route::resource('mediums', 'Medium\MediumController', ['except' => ['create', 'edit']]);
    Route::get('mediums/all/mediums', 'Medium\MediumController@getAllMediums');
    Route::get('mediums/status/activate/{mediumId}', 'Medium\MediumController@activateMedium');

    Route::resource('rooms', 'Room\RoomController', ['except' => ['create', 'edit']]);
    Route::get('rooms/status/{roomId}/{status}', 'Room\RoomController@changeDeleteStatus');
    Route::get('rooms/all/rooms','Room\RoomController@getActiveRooms');

    Route::resource('lectures', 'Lecture\LectureController', ['except' => ['create', 'edit']]);
    Route::resource('schedules', 'Schedule\ScheduleController', ['except' => ['create', 'edit']]);
    Route::resource('attendances', 'Attendance\AttendanceController', ['except' => ['create', 'edit']]);


    //=======================Student Report Apis===========================//


//all records - all time
    Route::get('reports/student_fee/all', 'ReportController@getAllStudentFeeRecords');

//all records - month
    Route::get('reports/student_fee/all/year/{year}/month/{month}', 'ReportController@getAllStudentFeeRecordsByMonth');

//all records - date
    Route::get('reports/student_fee/all/from/{fromDate}/to/{toDate}', 'ReportController@getAllStudentFeeRecordsByDate');



//records by course - all_time
    Route::get('reports/student_fee/course/{courseId}', 'ReportController@getAllStudentFeeRecordByCourse');

//records by course - month
    Route::get('reports/student_fee/course/{courseId}/year/{year}/month/{month}', 'ReportController@getAllStudentFeeRecordForCourseByMonth');

//records by course - date
    Route::get('reports/student_fee/course/{courseId}/from/{from_date}/to/{to_date}', 'ReportController@getAllStudentFeeRecordForCourseByDate');



//records by teacher - all_time
    Route::get('reports/student_fee/teacher/{teacherId}', 'ReportController@getAllStudentFeeRecordByTeacher');

//records by teacher - month
    Route::get('reports/student_fee/teacher/{teacherId}/year/{year}/month/{month}', 'ReportController@getAllStudentFeeRecordForTeacherByMonth');

//records by teacher - date
    Route::get('reports/student_fee/teacher/{teacherId}/from/{from_date}/to/{to_date}', 'ReportController@getAllStudentFeeRecordForTeacherByDate');


//records by lecture - all_time
    Route::get('reports/student_fee/lecture/{lectureId}', 'ReportController@getAllStudentFeeRecordByLecture');

//records by lecture - month
    Route::get('reports/student_fee/lecture/{lectureId}/year/{year}/month/{month}', 'ReportController@getAllStudentFeeRecordForLectureByMonth');

//records by lecture - date
    Route::get('reports/student_fee/lecture/{lectureId}/from/{from_date}/to/{to_date}', 'ReportController@getAllStudentFeeRecordForLectureByDate');



//=========================Teacher Report Apis=============================//

//all
    Route::get('reports/teacher_remun/all', 'ReportController@getRemunerationsPaidForTeachers');


//by teacher
    Route::get('reports/teacher_remun/teacher/{teacherId}', 'ReportController@getRemunerationsPaidForTeachersByTeacher');

//by lecture
    Route::get('reports/teacher_remun/lecture/{lectureId}', 'ReportController@getRemunerationsPaidForTeachersByLecture');

//by teacher
    Route::get('reports/teacher_remun/course/{courseId}', 'ReportController@getRemunerationsPaidForTeachersByCourse');




//=======================Teacher Institute Share Report Apis===========================//


//all records - all time
    Route::get('reports/share/all', 'ReportController@getAllShareRecords');

//all records - month
    Route::get('reports/share/all/year/{year}/month/{month}', 'ReportController@getAllShareRecordsByMonth');

//all records - date
    Route::get('reports/share/all/from/{fromDate}/to/{toDate}', 'ReportController@getAllShareRecordsByDate');



//records by course - all_time
    Route::get('reports/share/course/{courseId}', 'ReportController@getAllShareRecordsByCourse');

//records by course - month
    Route::get('reports/share/course/{courseId}/year/{year}/month/{month}', 'ReportController@getAllShareRecordsForCourseByMonth');

//records by course - date
    Route::get('reports/share/course/{courseId}/from/{from_date}/to/{to_date}', 'ReportController@getAllShareRecordsForCourseByDate');



//records by teacher - all_time
    Route::get('reports/share/teacher/{teacherId}', 'ReportController@getAllShareRecordsByTeacher');

//records by teacher - month
    Route::get('reports/share/teacher/{teacherId}/year/{year}/month/{month}', 'ReportController@getAllShareRecordsForTeacherByMonth');

//records by teacher - date
    Route::get('reports/share/teacher/{teacherId}/from/{from_date}/to/{to_date}', 'ReportController@getAllShareRecordsForTeacherByDate');


//records by lecture - all_time
    Route::get('reports/share/lecture/{lectureId}', 'ReportController@getAllShareRecordsByLecture');

//records by lecture - month
    Route::get('reports/share/lecture/{lectureId}/year/{year}/month/{month}', 'ReportController@getAllShareRecordsForLectureByMonth');

//records by lecture - date
    Route::get('reports/share/lecture/{lectureId}/from/{from_date}/to/{to_date}', 'ReportController@getAllShareRecordsForLectureByDate');

});

Route::resource('courses', 'Course\CourseController', ['except' => ['create', 'edit']]);
Route::resource('subjects', 'Subject\SubjectController', ['except' => ['create', 'edit']]);


Route::resource('views', 'View\ViewController', ['except' => ['create', 'edit']]);
Route::resource('teachers', 'Teacher\TeacherController', ['except' => ['create', 'edit']]);
Route::resource('admins', 'User\AdminController', ['except' => ['create', 'edit']]);
Route::resource('administrative_staff', 'User\AdministrativeStaffController', ['except' => ['create', 'edit']]);

Route::resource('course_mediums', 'Course\CourseMediumController', ['except' => ['create', 'edit']]);
Route::resource('daily-schedules', 'DailySchedule\DailyScheduleController', ['except' => ['create', 'edit']]);
Route::resource('students', 'Student\StudentController', ['except' => ['create', 'edit']]);
Route::resource('payments', 'Payment\PaymentController', ['except' => ['create', 'edit']]);
Route::resource('registrations', 'Registration\RegistrationController', ['except' => ['create', 'edit']]);
Route::resource('payment-schemes', 'Payment_Scheme\PaymentSchemeController', ['except' => ['create', 'edit']]);
Route::resource('student-payments', 'StudentPayments\StudentPaymentController', ['except' => ['create', 'edit']]);
Route::resource('student-scheme-lecture', 'StudentSchemeLecture\StudentSchemeLectureController', ['except' => ['create', 'edit']]);
Route::resource('monthly-payments', 'StudentPayments\MonthlyPaymentController', ['except' => ['create', 'edit']]);
Route::resource('lessons', 'Lecture\LectureLessonsController', ['except' => ['create', 'edit']]);
Route::resource('lessons_materials', 'Lecture\LessonMaterialsController', ['except' => ['create', 'edit']]);





//courses delete
Route::get('courses/status/{status}/{courseId}', 'Course\CourseController@changeDeleteStatus');

//course medium delete
Route::get('course_mediums/status/{status}/{courseId}', 'Course\CourseMediumController@changeDeleteStatus');

Route::get('courses/all/courses', 'Course\CourseController@getAllCourses');

//teachers
Route::get('teachers/all/teachers', 'Teacher\TeacherController@getAllActiveTeachers');

Route::get('daily-schedules/date/{date}', 'DailySchedule\DailyScheduleController@showByDate');
Route::get('daily-schedules/date/student/{date}/{studentId}', 'DailySchedule\DailyScheduleController@getStudentScheduleByDate');

Route::get('course/course_mediums/{course_id}', 'Course\CourseMediumController@getAllByCourse');
Route::get('course/medium/all', 'Course\CourseMediumController@getAllCoursesWithCourseMediums');
Route::get('subjects/course_medium/{courseMediumId}', 'Subject\SubjectController@getAllByCourseMedium');


//active lec
Route::get('lectures/course_medium/{courseMediumId}', 'Lecture\LectureController@getLecturesByCourseMedium');

//all lec
Route::get('lectures/course_medium/{courseMediumId}/all', 'Lecture\LectureController@getAllLecByCourseMedium');


Route::get('lectures/subjects/{subjectId}', 'Lecture\LectureController@getLecturesBySubject');

Route::get('schedules/lecture/{lectureId}', 'Schedule\ScheduleController@getSchedulesByLecture');
Route::get('payment/lecture/{lectureId}','Payment\PaymentController@getPaymentOfLecture');
Route::post('schedules/matching', 'Schedule\ScheduleController@getAllMatchingSchedules');

//get daily-schedules by date and lecture
Route::get('daily-schedules/date/lecture/{date}/{lectureId}/{studentId}', 'DailySchedule\DailyScheduleController@getScheduleByLectureAndDate');

Route::get('daily_schedules/find', 'Schedule\ScheduleController@findSchedule');
Route::post('student/payments', 'Payment\PaymentStudentController@store');

//Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::get('payment/test', 'Payment\PaymentStudentController@findNonPayed');
Route::get('payment/test2', 'Payment\PaymentStudentController@findPayed');

Route::get('lecture/{lectureId}/students', 'Lecture\LectureController@getAllStudentsByLecture');


Route::post('students/lecture/add', 'Student\StudentController@addLecture');

Route::post('students/lecture/remove', 'Student\StudentController@removeLecture');

Route::get('students/{studentId}/deactivate', 'Student\StudentController@deactivateStudent');

Route::get('students/course_medium/{courseMediumId}', 'Student\StudentController@getAllStudentsByCourseMedium');

Route::get('students/level/{level}', 'Student\StudentController@getAllStudentsByLevel');

Route::get('payment/student/{studentId}', 'Payment\PaymentStudentController@getPaymentsOfStudent');

Route::get('views/children/all', 'View\ViewController@menu');

Route::get('test1/{roleId}', 'User\UserController@menu');

Route::get('courses/type/{courseType}', 'Course\CourseController@getCourseByType');

Route::post('payment-schemes/find/relevant', 'Payment_Scheme\PaymentSchemeController@getRelevantScheme');
Route::post('payment-schemes/student/lecture', 'StudentSchemeLecture\StudentSchemeLectureController@getRelevantScheme');

Route::get('monthly-payment/student-payment/payable/{studentPaymentId}', 'StudentPayments\MonthlyPaymentController@getMonthlyPayments');
Route::get('monthly-payment/student-payment/paid/{studentId}', 'StudentPayments\MonthlyPaymentController@getMonthlyPaidPayments');
Route::get('monthly-payment/student-payment/due/{studentId}', 'StudentPayments\MonthlyPaymentController@getMonthlyDuePayments');
Route::get('monthly-payment/student-payment/check_due', 'StudentPayments\MonthlyPaymentController@changeStatusInDue');

Route::get('student-payment/student/{studentId}', 'StudentPayments\StudentPaymentController@getPaymentsOfStudent');
Route::get('student-payment-all/student/{studentId}', 'StudentPayments\StudentPaymentController@getAllStudentPayments');


Route::get('teacher/lectures/{teacherId}', 'Teacher\TeacherController@getAllLecturesOfTeacher');

//teacher monthly fee status for lecture
Route::get('teacher/lecture/month/remuneration/{lectureId}/{year}/{month}/all', 'Teacher\TeacherController@getMonthlyRemuneration');

//teacher monthly income for lecture
Route::get('teacher/lecture/month/remuneration/{teacherId}/{lectureId}/{year}/{month}/paid', 'Teacher\TeacherController@getMonthlyRemunerationPaid');

//total teacher monthly income
Route::get('teacher/income/total/{teacherId}', 'Teacher\TeacherController@getTeacherTotalMonthlyIncome');

//total teacher monthly income for lecture
Route::get('teacher/income/total/lecture/{lectureId}/{teacherId}', 'Teacher\TeacherController@getTeacherTotalMonthlyIncomeForLecture');

//teacher time tables
Route::get('teacher/timetables/{teacherId}', 'Teacher\TeacherController@getTeacherSchedulesTimetable');

//lecture months
Route::get('teacher/lecture/months/{lectureId}', 'Teacher\TeacherController@getLectureMonths');


Route::get('student/lectures/{studentId}', 'Student\StudentController@getStudentLectures');

Route::get('test/due', 'StudentPayments\MonthlyPaymentController@test');

//schedule notifications of teacher
Route::get('schedules/notifications/teacher/{teacherId}', 'Schedule\ScheduleNotificationsController@getTeacherUptoDateNotifications');

//schedule notifications of student
Route::get('schedules/notifications/student/{studentId}', 'Schedule\ScheduleNotificationsController@getStudentUptoDateNotifications');



Route::get('lectures/status/{lectureId}/{status}', 'Lecture\LectureController@changeDeleteStatus');


Route::get('subjects/status/{subjectId}/{status}', 'Subject\SubjectController@changeDeleteStatus');

Route::get('subjects/all/course_medium/{courseMediumId}', 'Subject\SubjectController@getAllSubjectsByCM');


//get attendances of lectures by date
Route::get('attendances/lecture/date/{lecture_id}/{date}', 'Attendance\AttendanceController@getStudentsAttendancesOfLecture');

//get lessons by lecture
Route::get('lessons/lecture/{lecture_id}', 'Lecture\LectureLessonsController@getLessonsByLecture');

//lesson delete status
Route::get('lessons/status/{lesson_id}/{status}','Lecture\LectureLessonsController@changeDeleteStatus');

//get lecture materials by lesson
Route::get('lessons_materials/lesson/{lesson_id}', 'Lecture\LessonMaterialsController@getMaterialsByLesson');

//download file
Route::post('lessons_materials/file', 'Lecture\LessonMaterialsController@downloadFile');


//get all lecture materials with lessons
Route::get('lesson_materials/lesson/lecture/{lecture_id}','Lecture\LessonMaterialsController@getAllMaterialsWithLessons' );

//lesson material info update
Route::put('lesson_materials/info/{material_id}','Lecture\LessonMaterialsController@updateLectureMaterialInfo' );

//lesson material update
Route::post('lesson_materials/material/{materialId}','Lecture\LessonMaterialsController@updateLectureMaterial');



//users
Route::get('users/role/{role}', 'User\UserController@getAllUserInformationByRole');

//deactivate and activate account
Route::get('users/status/{status}/{userId}', 'User\UserController@suspendOrActivateAccount');

//test mail
Route::get('test/email','MailController@sendEmail');







//dashboard data
Route::get('admins/dashboard/data', 'User\AdminController@getAdminDashboardData');
