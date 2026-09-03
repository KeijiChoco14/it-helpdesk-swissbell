<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceRequestCommentController extends Controller
{
    public function index(ServiceRequest $serviceRequest)
    {
        Gate::authorize('view', $serviceRequest);

        $comments = $serviceRequest->comments()->with('user')->orderBy('created_at', 'asc')->get();

        $html = '';
        foreach ($comments as $comment) {
            $html .= view('service-requests.partials.comment', compact('comment'))->render();
        }

        return response()->json(['html' => $html]);
    }

    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('view', $serviceRequest);

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $serviceRequest->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Comment added.');
    }
}
