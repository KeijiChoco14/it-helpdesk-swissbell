<?php

namespace App\Models;

use App\Enums\HousekeepingTaskStatus;
use App\Enums\HousekeepingTaskType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousekeepingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_number',
        'room_id',
        'assigned_to',
        'task_type',
        'priority',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'inspected_at',
        'inspected_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => HousekeepingTaskStatus::class,
            'task_type' => HousekeepingTaskType::class,
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'inspected_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($task) {
            if (empty($task->task_number)) {
                $year = date('Y');
                $latestTask = static::whereYear('created_at', $year)
                    ->orderBy('id', 'desc')
                    ->first();

                $sequence = 1;
                if ($latestTask && preg_match('/HK-' . $year . '-(\d+)/', $latestTask->task_number, $matches)) {
                    $sequence = intval($matches[1]) + 1;
                }

                $task->task_number = sprintf("HK-%s-%04d", $year, $sequence);
            }
        });
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}
