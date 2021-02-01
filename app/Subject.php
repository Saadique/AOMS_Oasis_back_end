<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table="subjects";

    protected $fillable = ['name', 'description', 'type', 'course_medium_id', 'subject_type'];

    public static $COMPULSORY = "compulsory";
    public static $BUCKET = "bucket";
    public static $OPTIONAL = "optional";

    public function courseMedium() {
        return $this->belongsTo(CourseMedium::class);
    }
}
