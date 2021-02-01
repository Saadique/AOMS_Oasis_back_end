<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = ['name', 'teacher_id', 'course_medium_id',
        'subject_id', 'type', 'class_type'];

//    protected $hidden = ['pivot'];

    public static $NORMAL_CLASS = "normal";
    public static $REVISION_CLASS = "revision";
    public static $PAPER_CLASS = "paper";
    public static $CATCH_UP = "catch_up";


    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class);
    }

    public function payment() {
        return $this->hasOne(Lecture::class);
    }

    public function courseMedium() {
        return $this->belongsTo(CourseMedium::class);
    }
}
