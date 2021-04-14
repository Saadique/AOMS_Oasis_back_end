<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleNotifications extends Model
{
    protected $fillable = ['daily_schedule_id', 'action', 'mail_status', 'sms_status'];
}
