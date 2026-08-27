<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $staff = User::with('department')
            ->orderBy('name')
            ->paginate(15);

        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        $departments = Department::where('is_active', true)->get();

        return view('staff.create', compact('departments'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['email'])) {
            $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name']));
            $validated['email'] = $username.'.'.uniqid().'@internal.local';
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('staff.index')->with('success', 'User created successfully.');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        Gate::authorize('delete', $staff);

        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete yourself.');
        }

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
