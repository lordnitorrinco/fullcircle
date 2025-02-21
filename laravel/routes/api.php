<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Define routes for TaskController
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/completed', [TaskController::class, 'completedTasks']);
Route::put('/tasks/{task}', [TaskController::class, 'update']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);