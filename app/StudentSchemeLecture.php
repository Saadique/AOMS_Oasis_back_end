<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentSchemeLecture extends Model
{
    protected $fillable = ['student_id', 'payment_scheme_id', 'lecture_id'];
}
