<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verification de votre adresse email')
            ->greeting('Bonjour,')
            ->line('Merci pour votre inscription. Veuillez confirmer votre adresse email pour activer votre compte.')
            ->action('Verifier mon adresse email', $verificationUrl)
            ->line('Si vous n\'etes pas a l\'origine de cette demande, vous pouvez ignorer cet email.')
            ->salutation('Cordialement');
    }
}
