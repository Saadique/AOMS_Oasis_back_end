<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
//    protected $hidden = ['pivot'];
    protected $fillable = ['name', 'lecture_id', 'student_fee'];


    public function lecture() {
        return $this->belongsTo(Lecture::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class)
            ->withPivot(['payment_amount','payment_month','payment_date']);
    }

}
