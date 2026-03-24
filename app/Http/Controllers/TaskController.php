<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller {
    
    public function index() {
        // [Telescope: Monitor query performance for listing all tasks]
        // Fetch all tasks directly since we aren't filtering by logged-in user
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        Log::warning('Task validation failed');
//         Log::info('Task Created', [
//     'id' => $task->id, 
//     'title' => $task->title, 
//     'user_id' => $task->user_id
// ]);

        // Logic: Find the first user in the DB to associate the task with.
        // If no user exists, we use ID 1 as a fallback.
        $user = User::first();
        $validated['user_id'] = $user ? $user->id : 1; 
        $validated['status'] = 'pending';

        $task =Task::create($validated);

        Log::info('Task created without auth');

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function update(Request $request, Task $task) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'status' => 'required|in:pending,completed',
        ]);

        $task->update($validated);

        // [LOGGING: Add Log::info('Task updated', ['task_id' => $task->id]) here]

        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function destroy(Task $task) {
        $task->delete();

        // [LOGGING: Add Log::notice('Task deleted', ['task_id' => $task->id]) here]
        Log::notice('Task Deleted', ['id' => $task->id, 'title' => $task->title]);

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}