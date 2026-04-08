<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reinitialisation du mot de passe')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email car nous avons recu une demande de reinitialisation de mot de passe pour votre compte.')
            ->action('Reinitialiser le mot de passe', $resetUrl)
            ->line('Ce lien de reinitialisation expirera dans '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' minutes.')
            ->line('Si vous n\'avez pas demande de reinitialisation de mot de passe, aucune action supplementaire n\'est requise.')
            ->salutation('Cordialement');
    }
}
