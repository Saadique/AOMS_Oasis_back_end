<?php


namespace App;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseMedium extends Pivot
{
    protected $hidden = ['pivot'];
    protected $table = "course_medium";
    protected $fillable = ['course_medium_id','course_id', 'medium_id', 'course_medium_type'];
    public $incrementing = true;


    public function subjects() {
        return $this->hasMany(Subject::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function medium() {
        return $this->belongsTo(Medium::class);
    }



}
