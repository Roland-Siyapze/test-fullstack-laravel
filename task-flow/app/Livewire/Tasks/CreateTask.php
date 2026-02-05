<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class CreateTask extends Component
{
    public string $title = '';
    public string $description = '';
    public string $category = '';
    public string $priority = 'medium';
    public string $status = 'pending';
    public string $dueDate = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed',
            'dueDate' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        Task::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'due_date' => $validated['dueDate'] ?: null,
        ]);

        session()->flash('success', 'Task created successfully.');

        $this->redirect(route('livewire.tasks'));
    }

    public function render()
    {
        return view('livewire.tasks.create-task');
    }
}
