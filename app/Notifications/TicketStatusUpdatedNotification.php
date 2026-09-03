<?php

namespace App$serviceRequestNotifications;

use App\Models\ServiceRequest;
use Illuminate$serviceRequestBus$serviceRequestQueueable;
use Illuminate$serviceRequestContracts$serviceRequestQueue$serviceRequestShouldQueue;
use Illuminate$serviceRequestNotifications$serviceRequestNotification;
use Illuminate$serviceRequestQueue$serviceRequestSerializesModels;

class TicketStatusUpdatedNotification extends Notification implements ShouldQueue
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
            ->subject('Ticket Status Updated: ' . $this->ticket->ticket_number)
            ->line('Your ticket status has been updated to: ' . $this->ticket->status . '.')
            ->line('Title: ' . $this->ticket->title)
            ->action('View Ticket', route('service-requests.show', $this->ticket->id))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'service_request_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title' => $this->ticket->title,
            'message' => 'Your ticket status changed to '.$this->ticket->status,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}



