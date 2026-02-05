<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Task $task */
        $task = $this->resource;

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'category' => $task->category,
            'priority' => $task->priority,
            'status' => $task->status,
            'due_date' => $task->due_date?->format('Y-m-d'),
            'is_overdue' => $task->due_date && $task->due_date->isPast() && $task->status !== 'completed',
            'user' => [
                'id' => $task->user->id,
                'name' => $task->user->name,
            ],
            'created_at' => $task->created_at->toISOString(),
            'updated_at' => $task->updated_at->toISOString(),
        ];
    }
}
