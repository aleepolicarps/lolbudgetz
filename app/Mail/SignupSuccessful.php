<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignupSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    public $register_attempt;

    public function __construct($register_attempt)
    {
        $this->register_attempt = $register_attempt;
    }

    public function build()
    {
        return $this->view('emails.signup_successful');
    }
}
