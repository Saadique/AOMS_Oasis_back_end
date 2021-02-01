<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name','no_of_seats', 'description', 'status'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

    public function dailySchedules() {
        return $this->hasMany(DailySchedule::class);
    }
}
