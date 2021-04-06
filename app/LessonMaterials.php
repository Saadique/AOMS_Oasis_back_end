<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonMaterials extends Model
{
    protected $fillable = ['lesson_id', 'file_name', 'path'];

    public function lesson() {
        return $this->belongsTo(LectureLessons::class);
    }
}
