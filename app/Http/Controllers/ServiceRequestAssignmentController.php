<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceRequestAssignmentController extends Controller
{
    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('assign', $serviceRequest);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $serviceRequest->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => $serviceRequest->status === 'OPEN' ? 'IN_PROGRESS' : $serviceRequest->status,
        ]);

        // Notify Assigned User
        if ($serviceRequest->assignedUser) {
            $serviceRequest->assignedUser->notify(new TicketAssignedNotification($serviceRequest));
        }

        return redirect()->back()->with('success', 'Service Request assigned successfully.');
    }
}
