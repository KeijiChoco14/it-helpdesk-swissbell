<?php

use App\Http\Controllers\CleaningTaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ServiceRequestAssignmentController;
use App\Http\Controllers\ServiceRequestCommentController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;

        return match ($role) {
            'it_admin' => redirect()->route('dashboard.admin'),
            'it_support' => redirect()->route('dashboard.it'),
            default => redirect()->route('dashboard'),
        };
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')->middleware('role:employee');

    Route::post('/locale', function (Request $request) {
        $validated = $request->validate(['locale' => 'required|in:en,id']);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $validated['locale']]);
        }
        session(['locale' => $validated['locale']]);

        return back();
    })->name('locale.update');

    Route::get('/it/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.it')->middleware('role:it_support');

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.admin')->middleware('role:it_admin');

        Route::resource('room-types', \App\Http\Controllers\RoomTypeController::class)->except(['show']);
    Route::resource('rooms', \App\Http\Controllers\RoomController::class);

        // Phase 4: Housekeeping Domain
    Route::get('housekeeping/dashboard', \App\Http\Controllers\HousekeepingDashboardController::class)->name('housekeeping.dashboard');
    Route::post('housekeeping/tasks/{housekeepingTask}/assign', [\App\Http\Controllers\HousekeepingTaskController::class, 'assign'])->name('housekeeping.tasks.assign');
    Route::post('housekeeping/tasks/{housekeepingTask}/start', [\App\Http\Controllers\HousekeepingTaskController::class, 'start'])->name('housekeeping.tasks.start');
    Route::post('housekeeping/tasks/{housekeepingTask}/complete', [\App\Http\Controllers\HousekeepingTaskController::class, 'complete'])->name('housekeeping.tasks.complete');
    Route::post('housekeeping/tasks/{housekeepingTask}/inspect', [\App\Http\Controllers\HousekeepingTaskController::class, 'inspect'])->name('housekeeping.tasks.inspect');
    Route::resource('housekeeping/tasks', \App\Http\Controllers\HousekeepingTaskController::class)->names([
        'index' => 'housekeeping.tasks.index',
        'create' => 'housekeeping.tasks.create',
        'store' => 'housekeeping.tasks.store',
        'show' => 'housekeeping.tasks.show',
        'edit' => 'housekeeping.tasks.edit',
        'update' => 'housekeeping.tasks.update',
        'destroy' => 'housekeeping.tasks.destroy',
    ]);

    // New Canonical Service Request Routes
    Route::get('service-requests/export', [ServiceRequestController::class, 'exportCsv'])->name('service-requests.export');
    Route::get('service-requests/kanban', [ServiceRequestController::class, 'kanban'])->name('service-requests.kanban');
    Route::patch('service-requests/{serviceRequest}/status', [ServiceRequestController::class, 'updateStatus'])->name('service-requests.update-status');
    Route::resource('service-requests', ServiceRequestController::class)->parameters(['service-requests' => 'serviceRequest']);
    Route::get('service-requests/{serviceRequest}/comments', [ServiceRequestCommentController::class, 'index'])->name('service-requests.comments.index');
    Route::post('service-requests/{serviceRequest}/comments', [ServiceRequestCommentController::class, 'store'])->name('service-requests.comments.store');
    Route::post('service-requests/{serviceRequest}/assign', [ServiceRequestAssignmentController::class, 'store'])->name('service-requests.assign');
    Route::post('service-requests/{serviceRequest}/rate', [ServiceRequestController::class, 'rate'])->name('service-requests.rate');

    // Legacy Ticket URL Redirects
    Route::redirect('tickets/export', '/service-requests/export');
    Route::redirect('tickets/kanban', '/service-requests/kanban');
    Route::redirect('tickets', '/service-requests');
    Route::redirect('tickets/create', '/service-requests/create');
    Route::get('tickets/{id}', function ($id) {
        return redirect()->route('service-requests.show', $id);
    });
    Route::get('tickets/{id}/edit', function ($id) {
        return redirect()->route('service-requests.edit', $id);
    });

    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('staff', StaffController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::get('equipment/{equipment}/tag', [EquipmentController::class, 'tag'])->name('equipment.tag');
    Route::resource('equipment', EquipmentController::class);
    Route::post('equipment/{equipment}/assign', [EquipmentController::class, 'assign'])->name('equipment.assign');
    Route::post('equipment/{equipment}/return', [EquipmentController::class, 'returnEquipment'])->name('equipment.return');
    Route::resource('cleaning-tasks', CleaningTaskController::class)->except(['show', 'edit', 'destroy']);

    Route::get('/knowledge-base/manage', [KnowledgeBaseArticleController::class, 'manage'])->name('knowledge-base.manage');
    Route::resource('knowledge-base', KnowledgeBaseArticleController::class)->parameters([
        'knowledge-base' => 'knowledgeBaseArticle',
    ]);

    Route::get('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Check if data contains service_request_id and redirect appropriately
        $requestId = $notification->data['service_request_id'] ?? $notification->data['ticket_id'] ?? null;
        if ($requestId) {
            return redirect()->route('service-requests.show', $requestId);
        }
        
        return back();
    })->name('notifications.read');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


