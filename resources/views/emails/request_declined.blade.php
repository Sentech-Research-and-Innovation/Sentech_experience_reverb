<!-- resources/views/emails/request_declined.blade.php -->

<p>Dear {{ $name }},</p>

<p>We regret to inform you that your registration request has been declined.</p>

@if ($reason)
    <p><strong>Reason:</strong> {{ $reason }}</p>
@endif

<p>If you have any questions, feel free to contact us.</p>

<p>Regards,<br>Sentech Admin</p>
