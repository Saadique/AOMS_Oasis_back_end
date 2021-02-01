<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    public $table = "views";

    public function roles() {
        return $this->belongsToMany(Role::class);
    }
}
