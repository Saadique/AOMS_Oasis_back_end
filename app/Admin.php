<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['first_name', 'last_name', 'contact_number', 'nic', 'address', 'email', 'status', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
