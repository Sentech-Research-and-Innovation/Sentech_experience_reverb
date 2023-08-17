<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use App\Models\User;

class ResetPasswordEmail extends Mailable
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.reset_password')
            ->with([
                'reset_link' => url('/password/reset/' . $this->user->password_reset_token),
            ]);
    }
}
