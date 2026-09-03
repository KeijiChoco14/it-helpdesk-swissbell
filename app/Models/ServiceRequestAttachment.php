<?php

namespace App\Models;

use Database\Factories\ServiceRequestAttachmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['service_request_id', 'user_id', 'file_name', 'file_path', 'mime_type', 'file_size'])]
class ServiceRequestAttachment extends Model
{
    /** @use HasFactory<ServiceRequestAttachmentFactory> */
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
