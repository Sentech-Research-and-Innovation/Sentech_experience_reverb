<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use App\Models\User;

class ResetPasswordEmail extends Mailable
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.reset_password')->subject('Feedback Form Submission')
            ->with([
                'user' => $this->user
            ]);
    }
}
