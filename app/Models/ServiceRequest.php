<?php

namespace App\Models;

use Database\Factories\ServiceRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\SlaService;

#[Fillable(['ticket_number', 'user_id', 'department_id', 'category_id', 'assigned_to', 'title', 'description', 'priority', 'status', 'location', 'device', 'resolution', 'due_at', 'resolved_at', 'closed_at', 'rating', 'feedback', 'room_id', 'asset_id'])]
class ServiceRequest extends Model
{
    /** @use HasFactory<ServiceRequestFactory> */
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($serviceRequest) {
            // Only update due_at if priority changed and it's not resolved/closed yet
            if ((! $serviceRequest->exists || $serviceRequest->isDirty('priority')) && ! in_array($serviceRequest->status, ['RESOLVED', 'CLOSED'])) {
                $serviceRequest->due_at = app(SlaService::class)->calculateDueAt($serviceRequest->priority);
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

    public function user() // To be renamed to requester() in future, but let's add an alias
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requester()
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
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(ServiceRequestComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(ServiceRequestAttachment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ServiceRequestActivityLog::class);
    }



    public function asset()
    {
        return $this->belongsTo(Equipment::class, 'asset_id');
    }
}

