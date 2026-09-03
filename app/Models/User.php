<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'job_title', 'department_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }

    public function assignedServiceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'assigned_to');
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

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function cleaningTasks()
    {
        return $this->hasMany(CleaningTask::class, 'performed_by');
    }

    public function isItStaff(): bool
    {
        return in_array($this->role, ['it_admin', 'it_support']);
    }

    public function isItAdmin(): bool
    {
        return $this->role === 'it_admin';
    }

    public function isItSupport(): bool
    {
        return $this->role === 'it_support';
    }
    public function assignedHousekeepingTasks()
    {
        return $this->hasMany(HousekeepingTask::class, 'assigned_to');
    }

    public function inspectedHousekeepingTasks()
    {
        return $this->hasMany(HousekeepingTask::class, 'inspected_by');
    }
}
