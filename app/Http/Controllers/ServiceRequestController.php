<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequestRequest;
use App\Http\Requests\UpdateServiceRequestRequest;
use App\Models\CannedResponse;
use App\Models\Category;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ServiceRequest::class);
        $user = $request->user();

        $query = ServiceRequest::with(['category', 'assignedUser']);

        if ($user->role === 'employee') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'it_support') {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhereNull('assigned_to');
            });
        }

        $serviceRequests = $query->latest()->paginate(15);

        return view('service-requests.index', compact('serviceRequests'));
    }

    public function create()
    {
        Gate::authorize('create', ServiceRequest::class);
        $categories = Category::where('is_active', true)->get();

        return view('service-requests.create', compact('categories'));
    }

    public function store(StoreServiceRequestRequest $request)
    {
        $validated = $request->validated();

        // Will update ticket_number generation later if necessary. Currently IT- format.
        $ticket_number = 'SR-'.date('Y').'-'.str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $serviceRequest = ServiceRequest::create(array_merge($validated, [
            'ticket_number' => $ticket_number,
            'user_id' => $request->user()->id,
            'department_id' => $request->user()->department_id,
            'status' => 'OPEN',
        ]));

        // Notify Admins
        $admins = User::where('role', 'it_admin')->get();
        Notification::send($admins, new NewTicketNotification($serviceRequest));

        return redirect()->route('service-requests.show', $serviceRequest)->with('success', 'Service Request created successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        Gate::authorize('view', $serviceRequest);
        $serviceRequest->load(['user', 'department', 'category', 'assignedUser', 'comments.user', 'activityLogs']);

        $cannedResponses = collect([]);
        if (in_array(auth()->user()->role, ['it_admin', 'it_support'])) {
            $cannedResponses = CannedResponse::orderBy('title')->get();
        }

        return view('service-requests.show', compact('serviceRequest', 'cannedResponses'));
    }

    public function update(UpdateServiceRequestRequest $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validated();

        if ($validated['status'] === 'RESOLVED') {
            Gate::authorize('resolve', $serviceRequest);
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] === 'CLOSED') {
            Gate::authorize('close', $serviceRequest);
            $validated['closed_at'] = now();
        } else {
            Gate::authorize('update', $serviceRequest);
        }

        $serviceRequest->update($validated);

        // Notify Service Request Creator
        $serviceRequest->user->notify(new TicketStatusUpdatedNotification($serviceRequest));

        return redirect()->back()->with('success', 'Service Request updated successfully.');
    }

    public function rate(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('view', $serviceRequest); // Ensure they own the service request or can view it

        if ($serviceRequest->status !== 'CLOSED' || $serviceRequest->user_id !== $request->user()->id) {
            abort(403, 'Only the creator can rate a closed request.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $serviceRequest->update($validated);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    public function exportCsv(Request $request)
    {
        Gate::authorize('viewAny', ServiceRequest::class);

        $serviceRequests = ServiceRequest::with(['category', 'user', 'assignedUser'])->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=service_requests_'.date('Y-m-d').'.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Request Number', 'Title', 'Status', 'Priority', 'Category', 'Created By', 'Assigned To', 'Created At'];

        $callback = function () use ($serviceRequests, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($serviceRequests as $serviceRequest) {
                fputcsv($file, [
                    $serviceRequest->ticket_number,
                    $serviceRequest->title,
                    $serviceRequest->status,
                    $serviceRequest->priority,
                    $serviceRequest->category->name ?? 'N/A',
                    $serviceRequest->user->name ?? 'N/A',
                    $serviceRequest->assignedUser->name ?? 'N/A',
                    $serviceRequest->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function kanban(Request $request)
    {
        Gate::authorize('viewAny', ServiceRequest::class);
        $user = $request->user();

        $query = ServiceRequest::with(['category', 'assignedUser']);

        if ($user->role === 'employee') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'it_support') {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhereNull('assigned_to');
            });
        }

        $serviceRequests = $query->latest()->get();

        $kanban = [
            'OPEN' => $serviceRequests->where('status', 'OPEN')->values(),
            'IN_PROGRESS' => $serviceRequests->where('status', 'IN_PROGRESS')->values(),
            'RESOLVED' => $serviceRequests->where('status', 'RESOLVED')->values(),
            'CLOSED' => $serviceRequests->where('status', 'CLOSED')->values(),
        ];

        return view('service-requests.kanban', compact('kanban'));
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('update', $serviceRequest);

        $validated = $request->validate([
            'status' => 'required|in:OPEN,IN_PROGRESS,RESOLVED,CLOSED'
        ]);

        if ($validated['status'] === 'RESOLVED') {
            Gate::authorize('resolve', $serviceRequest);
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] === 'CLOSED') {
            Gate::authorize('close', $serviceRequest);
            $validated['closed_at'] = now();
        }

        $serviceRequest->update($validated);

        // Notify Service Request Creator
        $serviceRequest->user->notify(new TicketStatusUpdatedNotification($serviceRequest));

        return response()->json(['success' => true]);
    }
}
