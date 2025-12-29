<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUser extends Notification
{
    use Queueable;

    public $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('invitations.accept', ['token' => $this->invitation->token]);

        return (new MailMessage)
                    ->subject('You have been invited to join ' . $this->invitation->tenant->name)
                    ->line('You have been invited to join the team at ' . $this->invitation->tenant->name . '.')
                    ->action('Accept Invitation', $url)
                    ->line('This invitation expires in 7 days.');
    }
}
