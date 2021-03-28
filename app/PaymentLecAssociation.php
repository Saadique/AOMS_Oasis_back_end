<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLecAssociation extends Model
{
    protected $fillable = ['student_payment_id', 'lec_student_ass_id', 'teacher_id'];

    public function studentPayment() {
        return $this->belongsTo(Student_Payment::class);
    }

    public function lecture() {
        return $this->belongsTo(Lecture::class);
    }

}
