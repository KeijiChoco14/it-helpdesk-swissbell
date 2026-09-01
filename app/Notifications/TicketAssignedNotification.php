<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Ticket Assigned: ' . $this->ticket->ticket_number)
            ->line('You have been assigned to ticket ' . $this->ticket->ticket_number . '.')
            ->line('Title: ' . $this->ticket->title)
            ->line('Priority: ' . $this->ticket->priority)
            ->action('View Ticket', route('tickets.show', $this->ticket->id))
            ->line('Please review and take necessary action.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'message' => 'You have been assigned to ticket '.$this->ticket->ticket_number,
        ];
    }
}
