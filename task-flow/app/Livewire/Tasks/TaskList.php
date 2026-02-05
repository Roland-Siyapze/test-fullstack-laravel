<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class TaskList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $priorityFilter = '';

    #[Url]
    public string $categoryFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter', 'categoryFilter']);
        $this->resetPage();
    }

    public function deleteTask(int $taskId): void
    {
        $task = Task::findOrFail($taskId);

        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        session()->flash('success', 'Task deleted successfully.');
    }

    public function toggleStatus(int $taskId): void
    {
        $task = Task::findOrFail($taskId);

        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
        ]);
    }

    public function render()
    {
        $tasks = Task::query()
            ->forUser(auth()->id())
            ->search($this->search)
            ->when($this->statusFilter, fn ($q) => $q->byStatus($this->statusFilter))
            ->when($this->priorityFilter, fn ($q) => $q->byPriority($this->priorityFilter))
            ->when($this->categoryFilter, fn ($q) => $q->byCategory($this->categoryFilter))
            ->latest()
            ->paginate(10);

        $categories = Task::forUser(auth()->id())
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('livewire.tasks.task-list', [
            'tasks' => $tasks,
            'categories' => $categories,
        ]);
    }
}
