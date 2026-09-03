<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        $stats = [];

        if ($role === 'employee') {
            $stats['total_open'] = ServiceRequest::where('user_id', $user->id)->where('status', 'OPEN')->count();
            $stats['total_resolved'] = ServiceRequest::where('user_id', $user->id)->where('status', 'RESOLVED')->count();

            return view('dashboard.employee', compact('stats'));
        }

        if ($role === 'it_support') {
            $stats['assigned_open'] = ServiceRequest::where('assigned_to', $user->id)->where('status', 'OPEN')->count();
            $stats['assigned_in_progress'] = ServiceRequest::where('assigned_to', $user->id)->where('status', 'IN_PROGRESS')->count();

            return view('dashboard.it', compact('stats'));
        }

        if ($role === 'it_admin') {
            $stats['total_unassigned'] = ServiceRequest::whereNull('assigned_to')->count();
            $stats['total_open'] = ServiceRequest::where('status', 'OPEN')->count();
            $stats['total_in_progress'] = ServiceRequest::where('status', 'IN_PROGRESS')->count();

            // Advanced Reporting for Admin
            $stats['by_category'] = Category::withCount('serviceRequests')->get();
            $stats['by_status'] = ServiceRequest::selectRaw('status, count(*) as count')->groupBy('status')->get();
            $stats['avg_rating'] = ServiceRequest::whereNotNull('rating')->avg('rating') ?? 0;

            $thirtyDaysAgo = now()->subDays(30);
            $stats['serviceRequests_over_time'] = ServiceRequest::selectRaw('DATE(created_at) as date, count(*) as count')
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return view('dashboard.admin', compact('stats'));
        }

        return view('dashboard');
    }
}


