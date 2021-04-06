<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LectureLessons extends Model
{
    protected $fillable = ["lecture_id", "name", "description"];

    public function lessonMaterials() {
        return $this->hasMany(LessonMaterials::class);
    }

    public function lecture() {
        return $this->belongsTo(Lecture::class);
    }
}
