<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Course extends Model
{
    protected $fillable = ['name', 'description','coordinator', 'course_type'];

    public function mediums() {
        return $this->belongsToMany(Medium::class)
            ->using(CourseMedium::class)
            ->withPivot('name');
    }
}
