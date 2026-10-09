<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard', [TaskController::class, 'dashboard']); // alias, tanpa nama duplikat

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::post('/tasks/{task}/done', [TaskController::class, 'markDone'])->name('tasks.markDone');
Route::post('/tasks/{task}/undone', [TaskController::class, 'markUndone'])->name('tasks.markUndone');

Route::get('/calendar', [TaskController::class, 'calendar'])->name('calendar');
Route::get('/history', [TaskController::class, 'history'])->name('history');
Route::get('/settings', [TaskController::class, 'settings'])->name('settings');
Route::post('/settings', [TaskController::class, 'updateSettings'])->name('settings.update');
