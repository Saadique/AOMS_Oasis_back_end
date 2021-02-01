<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['name', 'mobile_no', 'address', 'email'];

    public function lectures() {
        return $this->hasMany(Lecture::class);
    }
}
