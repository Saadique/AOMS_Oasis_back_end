<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordIssuer extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data)
    {
        $this->password_issuer_data = $data;
    }


    public function build()
    {
        return $this->from('sadiqzufer@gmail.com', 'Oasis')
            ->subject('Your Username And Password')->view('password-issuer', ['mail_data'=> $this->password_issuer_data]);
    }
}
