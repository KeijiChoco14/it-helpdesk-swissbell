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

        $equipment->load(['user', 'cleaningTasks.performer']);

        return view('equipment.show', compact('equipment'));
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
}
