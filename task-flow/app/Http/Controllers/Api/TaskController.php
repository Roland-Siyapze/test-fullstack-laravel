<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tasks = Task::query()
            ->forUser(auth()->id())
            ->when(request('search'), fn ($query, $search) => $query->search($search))
            ->when(request('status'), fn ($query, $status) => $query->byStatus($status))
            ->when(request('priority'), fn ($query, $priority) => $query->byPriority($priority))
            ->when(request('category'), fn ($query, $category) => $query->byCategory($category))
            ->latest()
            ->paginate(request('per_page', 15));

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = Task::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        return new TaskResource($task);
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.'], 200);
    }
}
