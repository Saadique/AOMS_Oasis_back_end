<?php


namespace App\Services\Schedule;


use App\Services\Service;
use Illuminate\Support\Facades\DB;

class ScheduleNotificationService extends Service
{
    public function findTeacherUptoDateNotifications($teacherId) {
//        $notifications = DB::select("SELECT * FROM schedule_notifications where
//                            created_at>=NOW()-INTERVAL 1 DAY AND daily_schedule_id IN
//                            (SELECT id from daily_schedules where lecture_id IN
//                            (SELECT id FROM lectures WHERE teacher_id=$teacherId))
//                            ORDER BY created_at DESC");

         DB::statement("create or replace view teacher_notifications as SELECT * FROM schedule_notifications where
                             created_at>=NOW()-INTERVAL 1 DAY AND daily_schedule_id IN
                             (SELECT id from daily_schedules where lecture_id IN
                             (SELECT id FROM lectures WHERE teacher_id=$teacherId))
                             ORDER BY created_at DESC");

        $notifications = DB::select("select lectures.name as lecture_name, rooms.name as room_name, daily_schedules.date,
                                           daily_schedules.start_time,daily_schedules.end_time, daily_schedules.room_id, daily_schedules.lecture_id, teacher_notifications.message
                                            from daily_schedules inner join teacher_notifications on daily_schedules.id=teacher_notifications.daily_schedule_id inner join lectures on
                                           lectures.id=daily_schedules.lecture_id INNER JOIN rooms ON rooms.id=daily_schedules.room_id");


        return $notifications;
    }

    public function findStudentUptoDateNotifications($studentId) {

       DB::statement("CREATE OR REPLACE VIEW student_notifications AS SELECT * FROM schedule_notifications where
                            created_at>=NOW()-INTERVAL 1 DAY AND daily_schedule_id IN
                            (SELECT id from daily_schedules where lecture_id IN
                            (SELECT lecture_id FROM lecture_student WHERE student_id=$studentId))
                            ORDER BY created_at DESC ");

        $notifications = DB::select("select lectures.name as lecture_name, rooms.name as room_name, daily_schedules.date,
                                           daily_schedules.start_time,daily_schedules.end_time, daily_schedules.room_id, daily_schedules.lecture_id, student_notifications.message
                                            from daily_schedules inner join student_notifications on daily_schedules.id=student_notifications.daily_schedule_id inner join lectures on
                                           lectures.id=daily_schedules.lecture_id INNER JOIN rooms ON rooms.id=daily_schedules.room_id");


        return $notifications;
    }
}
