<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleNoti extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($data)
    {
        $this->code = $data;
    }


    public function build()
    {
        return $this->from('sadiqzufer@gmail.com', 'Oasis')
            ->subject('Schedule Update')->view('schedule-noti', ['code'=> $this->code]);
    }
}
