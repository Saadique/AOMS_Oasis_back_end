<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_Scheme extends Model
{
    protected $fillable=['scheme_name', 'no_of_subjects', 'student_fee', 'fixed_institute_amount', 'class_level'];
}
