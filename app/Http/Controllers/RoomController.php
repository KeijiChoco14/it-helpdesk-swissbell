<?php

namespace App\Http\Controllers;

use App\Enums\RoomStatus;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('roomType')->withCount(['serviceRequests' => function($q) {
            $q->whereIn('status', ['OPEN', 'IN_PROGRESS']);
        }]);

        // Filters
        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->orderBy('room_number')->paginate(15)->withQueryString();
        
        $roomTypes = RoomType::orderBy('name')->get();
        $statuses = RoomStatus::cases();

        // Dashboard Summary
        $stats = [
            'total' => Room::count(),
            'available' => Room::where('status', RoomStatus::AVAILABLE)->count(),
            'occupied' => Room::where('status', RoomStatus::OCCUPIED)->count(),
            'dirty' => Room::where('status', RoomStatus::DIRTY)->count(),
            'cleaning' => Room::where('status', RoomStatus::CLEANING)->count(),
            'maintenance' => Room::where('status', RoomStatus::MAINTENANCE)->count(),
            'out_of_order' => Room::where('status', RoomStatus::OUT_OF_ORDER)->count(),
        ];

        return view('rooms.index', compact('rooms', 'roomTypes', 'statuses', 'stats'));
    }

    public function create()
    {
        $roomTypes = RoomType::orderBy('name')->get();
        $statuses = RoomStatus::cases();
        return view('rooms.create', compact('roomTypes', 'statuses'));
    }

    public function store(StoreRoomRequest $request)
    {
        Room::create($request->validated());

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load('roomType');
        $serviceRequests = $room->serviceRequests()->latest()->paginate(10);
        return view('rooms.show', compact('room', 'serviceRequests'));
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::orderBy('name')->get();
        $statuses = RoomStatus::cases();
        return view('rooms.edit', compact('room', 'roomTypes', 'statuses'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->serviceRequests()->exists()) {
            return back()->with('error', 'Cannot delete this room because there are service requests associated with it. Consider changing the status to "Out of Order" instead.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
