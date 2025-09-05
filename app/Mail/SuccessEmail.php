<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Your Parent Account is Activated!')
            ->view('emails.success_email')
            ->with(['user' => $this->user]);

        return $this->subject('Parent Account Successfully Created')
            ->view('emails.success_email');
    }
}
