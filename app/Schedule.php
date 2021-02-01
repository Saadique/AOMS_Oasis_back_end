<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['day','start_time','end_time', 'schedule_start_date',
        'schedule_end_date', 'room_id', 'lecture_id'];




    public function lecture() {
        return $this->belongsTo(Lecture::class);
    }

    public function dailySchedules() {
        return $this->hasMany(DailySchedule::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }


}
