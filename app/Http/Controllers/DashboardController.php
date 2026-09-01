<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        $stats = [];

        if ($role === 'employee') {
            $stats['total_open'] = Ticket::where('user_id', $user->id)->where('status', 'OPEN')->count();
            $stats['total_resolved'] = Ticket::where('user_id', $user->id)->where('status', 'RESOLVED')->count();

            return view('dashboard.employee', compact('stats'));
        }

        if ($role === 'it_support') {
            $stats['assigned_open'] = Ticket::where('assigned_to', $user->id)->where('status', 'OPEN')->count();
            $stats['assigned_in_progress'] = Ticket::where('assigned_to', $user->id)->where('status', 'IN_PROGRESS')->count();

            return view('dashboard.it', compact('stats'));
        }

        if ($role === 'it_admin') {
            $stats['total_unassigned'] = Ticket::whereNull('assigned_to')->count();
            $stats['total_open'] = Ticket::where('status', 'OPEN')->count();
            $stats['total_in_progress'] = Ticket::where('status', 'IN_PROGRESS')->count();

            // Advanced Reporting for Admin
            $stats['by_category'] = Category::withCount('tickets')->get();
            $stats['by_status'] = Ticket::selectRaw('status, count(*) as count')->groupBy('status')->get();
            $stats['avg_rating'] = Ticket::whereNotNull('rating')->avg('rating') ?? 0;

            return view('dashboard.admin', compact('stats'));
        }

        return view('dashboard');
    }
}
