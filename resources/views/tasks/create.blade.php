<form action="{{ route('tasks.store') }}" method="POST" class="p-6 bg-white shadow-md rounded">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700">Task Title</label>
        <input type="text" name="title" class="w-full border-gray-300 rounded" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Description</label>
        <textarea name="description" class="w-full border-gray-300 rounded"></textarea>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Task</button>
</form>