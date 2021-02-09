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

});

Route::middleware('auth:api')->group(function () {
    Route::post('user/logout', 'User\UserController@logout')->name('logout.api');
    Route::post('user/details', 'User\UserController@details');
    Route::get('teacher/lectures/{teacherId}', 'Teacher\TeacherController@getAllLecturesOfTeacher')
        ->middleware('api.admin');
});

Route::resource('courses', 'Course\CourseController', ['except' => ['create', 'edit']]);
Route::resource('subjects', 'Subject\SubjectController', ['except' => ['create', 'edit']]);
Route::resource('mediums', 'Medium\MediumController', ['except' => ['create', 'edit']]);
Route::resource('lectures', 'Lecture\LectureController', ['except' => ['create', 'edit']]);
Route::resource('schedules', 'Schedule\ScheduleController', ['except' => ['create', 'edit']]);
Route::resource('views', 'View\ViewController', ['except' => ['create', 'edit']]);
Route::resource('teachers', 'Teacher\TeacherController', ['except' => ['create', 'edit']]);

Route::resource('course_mediums', 'Course\CourseMediumController', ['except' => ['create', 'edit']]);
Route::resource('rooms', 'Room\RoomController', ['except' => ['create', 'edit']]);
Route::resource('daily-schedules', 'DailySchedule\DailyScheduleController', ['except' => ['create', 'edit']]);
Route::resource('students', 'Student\StudentController', ['except' => ['create', 'edit']]);
Route::resource('payments', 'Payment\PaymentController', ['except' => ['create', 'edit']]);
Route::resource('registrations', 'Registration\RegistrationController', ['except' => ['create', 'edit']]);
Route::resource('payment-schemes', 'Payment_Scheme\PaymentSchemeController', ['except' => ['create', 'edit']]);
Route::resource('student-payments', 'StudentPayments\StudentPaymentController', ['except' => ['create', 'edit']]);
Route::resource('student-scheme-lecture', 'StudentSchemeLecture\StudentSchemeLectureController', ['except' => ['create', 'edit']]);

Route::get('daily-schedules/date/{date}', 'DailySchedule\DailyScheduleController@showByDate');
Route::get('daily-schedules/date/student/{date}/{studentId}', 'DailySchedule\DailyScheduleController@getStudentScheduleByDate');

Route::get('course/course_mediums/{course_id}', 'Course\CourseMediumController@getAllByCourse');
Route::get('course/medium/all', 'Course\CourseMediumController@getAllCoursesWithCourseMediums');
Route::get('subjects/course_medium/{courseMediumId}', 'Subject\SubjectController@getAllByCourseMedium');

Route::get('lectures/course_medium/{courseMediumId}', 'Lecture\LectureController@getLecturesByCourseMedium');

Route::get('lectures/subjects/{subjectId}', 'Lecture\LectureController@getLecturesBySubject');

Route::get('schedules/lecture/{lectureId}', 'Schedule\ScheduleController@getSchedulesByLecture');
Route::post('schedules/matching', 'Schedule\ScheduleController@getAllMatchingSchedules');

Route::get('daily_schedules/find', 'Schedule\ScheduleController@findSchedule');
Route::post('student/payments', 'Payment\PaymentStudentController@store');

//Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::get('payment/test', 'Payment\PaymentStudentController@findNonPayed');
Route::get('payment/test2', 'Payment\PaymentStudentController@findPayed');

Route::get('lecture/{lectureId}/students', 'Lecture\LectureController@getAllStudentsByLecture');

Route::post('students/lecture/add', 'Student\StudentController@addLecture');

Route::get('payment/student/{studentId}', 'Payment\PaymentStudentController@getPaymentsOfStudent');

Route::get('views/children/all', 'View\ViewController@menu');

Route::get('test1/{roleId}', 'User\UserController@menu');

Route::get('courses/type/{courseType}', 'Course\CourseController@getCourseByType');

Route::post('payment-schemes/find/relevant', 'Payment_Scheme\PaymentSchemeController@getRelevantScheme');
Route::post('payment-schemes/student/lecture', 'StudentSchemeLecture\StudentSchemeLectureController@getRelevantScheme');
Route::get('monthly-payment/student-payment/{studentPaymentId}', 'StudentPayments\MonthlyPaymentController@getMonthlyPayments');

Route::get('student-payment/student/{studentId}', 'StudentPayments\StudentPaymentController@getPaymentsOfStudent');

//Route::get('teacher/lectures/{teacherId}', 'Teacher\TeacherController@getAllLecturesOfTeacher');
Route::get('student/lectures/{studentId}', 'Student\StudentController@getStudentLectures');
