<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class EditTask extends Component
{
    public Task $task;
    public string $title = '';
    public string $description = '';
    public string $category = '';
    public string $priority = '';
    public string $status = '';
    public string $dueDate = '';

    public function mount(Task $task): void
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $this->task = $task;
        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->category = $task->category ?? '';
        $this->priority = $task->priority;
        $this->status = $task->status;
        $this->dueDate = $task->due_date?->format('Y-m-d') ?? '';
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed',
            'dueDate' => 'nullable|date',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $this->task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['dueDate'] ?: null,
        ]);

        session()->flash('success', 'Task updated successfully.');

        $this->redirect(route('livewire.tasks'));
    }

    public function render()
    {
        return view('livewire.tasks.edit-task');
    }
}
