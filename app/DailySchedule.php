<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailySchedule extends Model
{
    protected $fillable = ['date', 'start_time', 'end_time', 'day', 'room_id', 'schedule_id', 'lecture_id'];

    public static $PENDING_STATUS = "pending";
    public static $COMPLETED_STATUS = "completed";
    public static $CANCELED_STATUS = "canceled";

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function lecture() {
        return $this->belongsTo(Lecture::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

}
