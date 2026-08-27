<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCleaningTaskRequest;
use App\Http\Requests\UpdateCleaningTaskRequest;
use App\Models\CleaningTask;
use App\Models\Equipment;
use Illuminate\Support\Facades\Gate;

class CleaningTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', CleaningTask::class);

        $cleaningTasks = CleaningTask::with(['equipment', 'performer'])->latest()->paginate(15);

        return view('cleaning-tasks.index', compact('cleaningTasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', CleaningTask::class);

        $equipments = Equipment::orderBy('name')->get();

        return view('cleaning-tasks.create', compact('equipments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCleaningTaskRequest $request)
    {
        $validated = $request->validated();

        $validated['performed_by'] = $request->user()->id;

        CleaningTask::create($validated);

        return redirect()->route('cleaning-tasks.index')->with('success', 'Cleaning task created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCleaningTaskRequest $request, CleaningTask $cleaningTask)
    {
        $validated = $request->validated();

        if ($validated['status'] === 'completed' && $cleaningTask->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $cleaningTask->update($validated);

        return redirect()->back()->with('success', 'Cleaning task updated successfully.');
    }
}
