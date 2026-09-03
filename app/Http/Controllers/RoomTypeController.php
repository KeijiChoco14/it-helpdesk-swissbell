<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::latest()->paginate(10);
        return view('room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room-types.create');
    }

    public function store(StoreRoomTypeRequest $request)
    {
        RoomType::create($request->validated());

        return redirect()->route('room-types.index')
            ->with('success', 'Room type created successfully.');
    }

    public function edit(RoomType $roomType)
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        $roomType->update($request->validated());

        return redirect()->route('room-types.index')
            ->with('success', 'Room type updated successfully.');
    }

    public function destroy(RoomType $roomType)
    {
        if ($roomType->rooms()->exists()) {
            return back()->with('error', 'Cannot delete this room type because there are rooms using it.');
        }

        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
}
