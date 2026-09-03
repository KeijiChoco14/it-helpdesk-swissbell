<?php

namespace App\Models;

use Database\Factories\ServiceRequestCommentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['service_request_id', 'user_id', 'comment'])]
class ServiceRequestComment extends Model
{
    /** @use HasFactory<ServiceRequestCommentFactory> */
    use HasFactory;

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
