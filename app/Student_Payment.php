<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_Payment extends Model
{
    protected $fillable = ['student_id', 'payment_type', 'payment_scheme_id', 'payment_id', 'payment_amount',
        'payment_start_date', 'payment_end_date'];

    public function monthlyPayments() {
        return $this->hasMany(MonthlyPayment::class);
    }

}
