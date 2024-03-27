<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SampleMail;

class MailController extends Controller
{
    public function sendTestEmail()
    {
        $recipientEmail = 'lancedota12@gmail.com';

        // Replace 'SampleMail' with the actual Mailable class you want to use
        Mail::to($recipientEmail)->send(new SampleMail());

        return "Test email sent to $recipientEmail!";
    }
}
