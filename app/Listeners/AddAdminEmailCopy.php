<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;

class AddAdminEmailCopy
{
    public function handle(MessageSending $event): void
    {
        $admin = config('mail.admin_notification');

        if (! $admin) {
            return;
        }

        $message = $event->message;
        $message->bcc($admin);
    }
}
