<?php

use App\Http\Controllers\CleaningTaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TicketAssignmentController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\TicketController;
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

    Route::get('tickets/export', [TicketController::class, 'exportCsv'])->name('tickets.export');
    Route::resource('tickets', TicketController::class);
    Route::get('tickets/{ticket}/comments', [TicketCommentController::class, 'index'])->name('tickets.comments.index');
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');
    Route::post('tickets/{ticket}/assign', [TicketAssignmentController::class, 'store'])->name('tickets.assign');
    Route::post('tickets/{ticket}/rate', [TicketController::class, 'rate'])->name('tickets.rate');

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

        return redirect()->route('tickets.show', $notification->data['ticket_id']);
    })->name('notifications.read');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
