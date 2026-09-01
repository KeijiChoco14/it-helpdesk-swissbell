<?php

namespace App\Models;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ticket_number', 'user_id', 'department_id', 'category_id', 'assigned_to', 'title', 'description', 'priority', 'status', 'location', 'device', 'resolution', 'due_at', 'resolved_at', 'closed_at', 'rating', 'feedback'])]
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($ticket) {
            // Only update due_at if priority changed and it's not resolved/closed yet
            if ((! $ticket->exists || $ticket->isDirty('priority')) && ! in_array($ticket->status, ['RESOLVED', 'CLOSED'])) {
                $ticket->due_at = match ($ticket->priority) {
                    'Low' => now()->addDays(3),
                    'Medium' => now()->addDays(1),
                    'High' => now()->addHours(4),
                    'Critical' => now()->addHours(1),
                    default => now()->addDays(3),
                };
            }
        });
    }

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(TicketActivityLog::class);
    }
}
