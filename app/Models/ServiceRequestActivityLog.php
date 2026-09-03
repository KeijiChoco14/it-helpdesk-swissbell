<?php

namespace App\Models;

use Database\Factories\ServiceRequestActivityLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['service_request_id', 'user_id', 'action', 'description', 'old_values', 'new_values'])]
class ServiceRequestActivityLog extends Model
{
    /** @use HasFactory<ServiceRequestActivityLogFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'old_values' => 'json',
            'new_values' => 'json',
        ];
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
