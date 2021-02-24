<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyPayment extends Model
{
     public function studentPayment() {
         return $this->belongsTo(Student_Payment::class);
     }

     public function student() {
         return $this->belongsTo(Student::class);
     }
}
