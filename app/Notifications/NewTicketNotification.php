<?php

namespace App$serviceRequestNotifications;

use App\Models\ServiceRequest;
use Illuminate$serviceRequestBus$serviceRequestQueueable;
use Illuminate$serviceRequestContracts$serviceRequestQueue$serviceRequestShouldQueue;
use Illuminate$serviceRequestNotifications$serviceRequestNotification;
use Illuminate$serviceRequestQueue$serviceRequestSerializesModels;

class NewTicketNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

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

    public function toMail(object $notifiable): $serviceRequestIlluminate$serviceRequestNotifications$serviceRequestMessages$serviceRequestMailMessage
    {
        return (new $serviceRequestIlluminate$serviceRequestNotifications$serviceRequestMessages$serviceRequestMailMessage)
            ->subject('New Ticket Created: ' . $this->ticket->ticket_number)
            ->line('A new ticket has been created by ' . $this->ticket->user->name . '.')
            ->line('Title: ' . $this->ticket->title)
            ->line('Priority: ' . $this->ticket->priority)
            ->action('View Ticket', route('service-requests.show', $this->ticket->id))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'service_request_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'message' => 'New ticket created by '.$this->ticket->user->name,
        ];
    }
}



