<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Equipment::class);

        $query = Equipment::with('user');

        if ($request->user()->role === 'employee') {
            $query->where('user_id', $request->user()->id);
        }

        $equipmentList = $query->latest()->paginate(15);

        return view('equipment.index', compact('equipmentList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Equipment::class);

        $users = User::orderBy('name')->get();

        return view('equipment.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $request)
    {
        Equipment::create($request->validated());

        return redirect()->route('equipment.index')->with('success', 'Equipment added successfully.');
    }

    public function tag(Equipment $equipment)
    {
        Gate::authorize('view', $equipment);

        return view('equipment.tag', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        Gate::authorize('update', $equipment);

        $users = User::orderBy('name')->get();

        return view('equipment.edit', compact('equipment', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());

        return redirect()->route('equipment.show', $equipment)->with('success', 'Equipment updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        Gate::authorize('view', $equipment);

        $equipment->load(['user', 'cleaningTasks.performer', 'assignments.user']);
        $users = User::orderBy('name')->get();

        return view('equipment.show', compact('equipment', 'users'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        Gate::authorize('delete', $equipment);

        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }

    public function assign(Request $request, Equipment $equipment)
    {
        Gate::authorize('update', $equipment);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        // If currently assigned, mark returned
        if ($equipment->user_id) {
            $equipment->assignments()->whereNull('returned_at')->update(['returned_at' => now()]);
        }

        $equipment->update(['user_id' => $validated['user_id']]);

        $equipment->assignments()->create([
            'user_id' => $validated['user_id'],
            'notes' => $validated['notes'] ?? null,
            'assigned_at' => now(),
        ]);

        return redirect()->route('equipment.show', $equipment)->with('success', 'Equipment assigned successfully.');
    }

    public function returnEquipment(Equipment $equipment)
    {
        Gate::authorize('update', $equipment);

        if ($equipment->user_id) {
            $equipment->assignments()->whereNull('returned_at')->update(['returned_at' => now()]);
            $equipment->update(['user_id' => null]);
        }

        return redirect()->route('equipment.show', $equipment)->with('success', 'Equipment returned successfully.');
    }
}
