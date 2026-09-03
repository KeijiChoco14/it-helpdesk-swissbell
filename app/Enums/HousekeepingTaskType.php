<?php

namespace App\Enums;

enum HousekeepingTaskType: string
{
    case CHECKOUT_CLEANING = 'CHECKOUT_CLEANING';
    case STAYOVER_CLEANING = 'STAYOVER_CLEANING';
    case DEEP_CLEANING = 'DEEP_CLEANING';
    case TURNDOWN = 'TURNDOWN';
    case INSPECTION = 'INSPECTION';

    public function label(): string
    {
        return match ($this) {
            self::CHECKOUT_CLEANING => 'Checkout Cleaning',
            self::STAYOVER_CLEANING => 'Stayover Cleaning',
            self::DEEP_CLEANING => 'Deep Cleaning',
            self::TURNDOWN => 'Turndown Service',
            self::INSPECTION => 'Inspection',
        };
    }
}
