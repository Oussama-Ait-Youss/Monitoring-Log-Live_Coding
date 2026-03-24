<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel To-Do</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Task Manager</h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow mb-8">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="flex flex-col gap-4">
                    <input type="text" name="title" placeholder="Task title..." class="border p-2 rounded @error('title') border-red-500 @enderror">
                    <textarea name="description" placeholder="Description (optional)" class="border p-2 rounded"></textarea>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Add Task
                    </button>
                </div>
            </form>
            @error('title')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                {{-- [LOGGING: Add Log::warning('User attempted to create task with empty title') here] --}}
            @enderror
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4">Status</th>
                        <th class="p-4">Task</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td class="p-4 font-medium">{{ $task->title }}</td>
                            <td class="p-4 flex gap-2">
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500">No tasks yet. Create one above!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>