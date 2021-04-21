<?php

namespace App\Http\Controllers;

use App\Mail\PasswordIssuer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail() {
        $data = [
            'name'=> 'Abdullah',
            'password'=>'45656'
        ];
        Mail::to('zufersaadique@gmail.com')->send(new PasswordIssuer($data));
    }


}
