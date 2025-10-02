<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Kanban Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // API Endpoints for Kanban
    Route::prefix('api/kanban')->group(function () {
        Route::get('/tasks', [KanbanController::class, 'getTasks']);
        Route::post('/tasks', [KanbanController::class, 'store']);
        Route::put('/tasks/{id}', [KanbanController::class, 'update']);
        Route::delete('/tasks/{id}', [KanbanController::class, 'destroy']);
        Route::post('/tasks/{id}/move', [KanbanController::class, 'move']);
        Route::post('/tasks/{taskId}/subtasks/{subtaskId}/toggle', [KanbanController::class, 'toggleSubtask']);
        Route::post('/tasks/{id}/comments', [KanbanController::class, 'addComment']);
        Route::post('/tasks/{id}/attachments', [KanbanController::class, 'uploadAttachment']);
        Route::get('/users', [KanbanController::class, 'getUsers']);
        Route::get('/bidangs', [KanbanController::class, 'getBidangs']);
    });
});
