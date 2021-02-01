<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    protected $fillable = ['name', 'description', 'short_name'];
    protected $table = "mediums";

    public function courses() {
        return $this->belongsToMany(Course::class)
            ->using(CourseMedium::class)
            ->withPivot('name');
    }
}
