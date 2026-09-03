<?php

namespace App\Services;

class SlaService
{
    /**
     * Calculate the due date based on the priority level.
     * Preserves existing SLA logic from the original Ticket model.
     *
     * @param string $priority
     * @return \Illuminate\Support\Carbon
     */
    public function calculateDueAt(string $priority)
    {
        return match ($priority) {
            'Low' => now()->addDays(3),
            'Medium' => now()->addDays(1),
            'High' => now()->addHours(4),
            'Critical' => now()->addHours(1),
            default => now()->addDays(3),
        };
    }
}
