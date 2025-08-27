<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        //create user admin/user/create

        // forgot-password

        $urlCurrent = Request::url();

        $url = url('/reset-password') 
     . '?token=' . $this->token 
     . '&email=' . urlencode($notifiable->getEmailForPasswordReset());


        preg_match('~(?:https?://)?[^/]+/(.*)$~', $urlCurrent, $matches);
        $resultURL = $matches[1];

        $subject = "";
        $message = "";
        $message2 = "";

        if ($resultURL == 'admin/user/create') {
            $subject = "Account Created.";
            $message = "Congrats your account has been Created, click the reset password button, to start using the system.";
        } else if ($resultURL == 'forgot-password') {
            $subject = "Forgot password?";
            $message = "You are receiving this email because we received a password reset request for your account.";
            $message2 = "If you did not request a password reset, no further action is required.";
        } else {
            $subject = "Account Approved.";
            $message = "Congrats your account has been approved, click the reset password button, to start using the system.";
        }

        return (new MailMessage)
            ->line($subject)
            ->line($message)
            ->action('Click to reset', $url)
            ->line('This password reset link will expire in 60 minutes.')
            ->line($message2)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
