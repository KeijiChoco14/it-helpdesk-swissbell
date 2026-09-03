<?php

namespace App$serviceRequestNotifications;

use App\Models\ServiceRequest;
use Illuminate$serviceRequestBus$serviceRequestQueueable;
use Illuminate$serviceRequestContracts$serviceRequestQueue$serviceRequestShouldQueue;
use Illuminate$serviceRequestNotifications$serviceRequestNotification;
use Illuminate$serviceRequestQueue$serviceRequestSerializesModels;

class TicketAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): $serviceRequestIlluminate$serviceRequestNotifications$serviceRequestMessages$serviceRequestMailMessage
    {
        return (new $serviceRequestIlluminate$serviceRequestNotifications$serviceRequestMessages$serviceRequestMailMessage)
            ->subject('Ticket Assigned: ' . $this->ticket->ticket_number)
            ->line('You have been assigned to ticket ' . $this->ticket->ticket_number . '.')
            ->line('Title: ' . $this->ticket->title)
            ->line('Priority: ' . $this->ticket->priority)
            ->action('View Ticket', route('service-requests.show', $this->ticket->id))
            ->line('Please review and take necessary action.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'service_request_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'message' => 'You have been assigned to ticket '.$this->ticket->ticket_number,
        ];
    }
}



