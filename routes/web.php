<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); // <- Tambahkan ini
Route::patch('/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/priority', [TaskController::class, 'priority'])->name('tasks.priority');
Route::get('/tasks/completed', [TaskController::class, 'completed'])->name('tasks.completed');
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::post('/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
Route::get('/tasks/{task}', [TaskController::class, 'show']);
Route::get('/tasks/{task}/subtasks', [SubtaskController::class, 'getSubtasks']);
Route::get('api/subtasks/{task}', [SubtaskController::class, 'show'])->name('api.subtasks.show');
Route::post('/tasks/{task}/priority', [TaskController::class, 'markAsPriority'])->name('tasks.priority.mark');
Route::get('/tasks/priority', [TaskController::class, 'priority'])->name('tasks.priority');
Route::patch('/subtasks/{id}', [SubtaskController::class, 'update'])->name('subtasks.update');
Route::delete('/subtasks/{id}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
Route::patch('/subtasks/{id}', [SubtaskController::class, 'update'])->name('subtasks.update');





