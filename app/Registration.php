<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    public static $count = 0;
    protected $fillable = ['registration_date', 'student_id'];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public static function generateRegNo(){
        return self::$count++;
    }
}
