<?php

namespace App\Enums;

enum RoomStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case OCCUPIED = 'OCCUPIED';
    case DIRTY = 'DIRTY';
    case CLEANING = 'CLEANING';
    case INSPECTED = 'INSPECTED';
    case MAINTENANCE = 'MAINTENANCE';
    case OUT_OF_ORDER = 'OUT_OF_ORDER';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::OCCUPIED => 'Occupied',
            self::DIRTY => 'Dirty',
            self::CLEANING => 'Cleaning',
            self::INSPECTED => 'Inspected',
            self::MAINTENANCE => 'Maintenance',
            self::OUT_OF_ORDER => 'Out of Order',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AVAILABLE => 'emerald',
            self::OCCUPIED => 'blue',
            self::DIRTY => 'amber',
            self::CLEANING => 'indigo',
            self::INSPECTED => 'teal',
            self::MAINTENANCE => 'rose',
            self::OUT_OF_ORDER => 'slate',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
