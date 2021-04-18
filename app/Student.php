<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $hidden = ['pivot'];
    protected $fillable = ['registration_no', 'name','NIC','email','gender','student_type', 'mobile_no',
        'school_name', 'user_id'];

    public function lectures() {
        return $this->belongsToMany(Lecture::class);
    }

    public function payments() {
        return $this->belongsToMany(Payment::class);
    }

    public function registration() {
        return $this->hasOne(Registration::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

    public function studentPayments() {
        return $this->hasMany(Student_Payment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
