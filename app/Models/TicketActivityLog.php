<?php

namespace App\Models;

use Database\Factories\TicketActivityLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ticket_id', 'user_id', 'action', 'description', 'old_values', 'new_values'])]
class TicketActivityLog extends Model
{
    /** @use HasFactory<TicketActivityLogFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'old_values' => 'json',
            'new_values' => 'json',
        ];
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
