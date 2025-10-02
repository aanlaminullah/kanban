<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\KanbanController;

// Route::middleware(['auth:sanctum'])->prefix('kanban')->group(function () {
//     // Task CRUD
//     Route::get('/tasks', [KanbanController::class, 'getTasks']);
//     Route::post('/tasks', [KanbanController::class, 'store']);
//     Route::put('/tasks/{id}', [KanbanController::class, 'update']);
//     Route::delete('/tasks/{id}', [KanbanController::class, 'destroy']);

//     // Task Actions
//     Route::post('/tasks/{id}/move', [KanbanController::class, 'move']);
//     Route::post('/tasks/{taskId}/subtasks/{subtaskId}/toggle', [KanbanController::class, 'toggleSubtask']);
//     Route::post('/tasks/{id}/comments', [KanbanController::class, 'addComment']);
//     Route::post('/tasks/{id}/attachments', [KanbanController::class, 'uploadAttachment']);

//     // Master Data
//     Route::get('/users', [KanbanController::class, 'getUsers']);
//     Route::get('/bidangs', [KanbanController::class, 'getBidangs']);
// });
