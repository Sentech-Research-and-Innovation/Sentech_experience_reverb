<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $reason)
    {
        $this->name = $name;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Registration Request Was Declined')
                    ->view('emails.request_declined');
    }
}
