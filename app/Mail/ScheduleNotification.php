<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleNotification extends Mailable
{
    use Queueable, SerializesModels;


    public $message;
    public function __construct($messageText)
    {
        $this->message = $messageText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sadiqzufer@gmail.com', 'Oasis')
            ->subject('Lecture Schedule Updates')->view('schedule-notification', ['message'=> $this->message]);
    }
}
