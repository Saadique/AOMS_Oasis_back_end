<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordCode extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($data)
    {
        $this->code = $data;
    }


    public function build()
    {
        return $this->from('sadiqzufer@gmail.com', 'Oasis')
            ->subject('Reset Password')->view('reset-password-code', ['code'=> $this->code]);
    }
}
