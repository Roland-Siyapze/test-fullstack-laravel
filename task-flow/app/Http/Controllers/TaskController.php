<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(): Response
    {
        $tasks = Task::query()
            ->forUser(auth()->id())
            ->with('user')
            ->when(request('search'), fn ($query, $search) => $query->search($search))
            ->when(request('status'), fn ($query, $status) => $query->byStatus($status))
            ->when(request('priority'), fn ($query, $priority) => $query->byPriority($priority))
            ->when(request('category'), fn ($query, $category) => $query->byCategory($category))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => request()->only(['search', 'status', 'priority', 'category']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tasks/Create');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): Response
    {
        $this->authorize('view', $task);

        return Inertia::render('Tasks/Show', [
            'task' => $task->load('user'),
        ]);
    }

    public function edit(Task $task): Response
    {
        $this->authorize('update', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
