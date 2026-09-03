<?php

namespace App\Enums;

enum HousekeepingTaskStatus: string
{
    case PENDING = 'PENDING';
    case ASSIGNED = 'ASSIGNED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case INSPECTED = 'INSPECTED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ASSIGNED => 'Assigned',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::INSPECTED => 'Inspected',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-slate-100 text-slate-800',
            self::ASSIGNED => 'bg-blue-100 text-blue-800',
            self::IN_PROGRESS => 'bg-amber-100 text-amber-800',
            self::COMPLETED => 'bg-indigo-100 text-indigo-800',
            self::INSPECTED => 'bg-emerald-100 text-emerald-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
        };
    }
}
