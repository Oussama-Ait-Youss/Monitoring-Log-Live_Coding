<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Removed the auth middleware group
Route::resource('tasks', TaskController::class);

// Redirect root to tasks for convenience
Route::get('/', function () {
    return redirect()->route('tasks.index');
});