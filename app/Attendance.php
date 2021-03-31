<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id', 'daily_schedule_id', 'attendance_status'];

    public function dailySchedule() {
        return $this->belongsTo(DailySchedule::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
