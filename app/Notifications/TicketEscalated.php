<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketEscalated extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error()
                    ->subject('SLA Breach: Ticket ' . $this->ticket->ticket_number . ' is Overdue')
                    ->line('A ticket has breached its Service Level Agreement (SLA).')
                    ->line('Priority: ' . $this->ticket->priority)
                    ->line('Title: ' . $this->ticket->title)
                    ->action('View Ticket', route('tickets.show', $this->ticket->id))
                    ->line('Please take immediate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'SLA Breach: ' . $this->ticket->ticket_number,
            'message' => 'Ticket is overdue based on SLA',
        ];
    }
}
