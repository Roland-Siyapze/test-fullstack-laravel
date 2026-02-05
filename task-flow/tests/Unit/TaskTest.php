<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $task->user);
        $this->assertEquals($user->id, $task->user->id);
    }

    public function test_scope_for_user_filters_correctly(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Task::factory(3)->create(['user_id' => $user1->id]);
        Task::factory(2)->create(['user_id' => $user2->id]);

        $user1Tasks = Task::forUser($user1->id)->get();

        $this->assertCount(3, $user1Tasks);
    }

    public function test_scope_by_status_filters_correctly(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);

        $pendingTasks = Task::forUser($user->id)->byStatus('pending')->get();

        $this->assertCount(1, $pendingTasks);
        $this->assertEquals('pending', $pendingTasks->first()->status);
    }

    public function test_scope_by_priority_filters_correctly(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'urgent',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'low',
        ]);

        $urgentTasks = Task::forUser($user->id)->byPriority('urgent')->get();

        $this->assertCount(1, $urgentTasks);
        $this->assertEquals('urgent', $urgentTasks->first()->priority);
    }

    public function test_scope_search_finds_tasks(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Find this task',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Other task',
        ]);

        $searchResults = Task::forUser($user->id)->search('Find this')->get();

        $this->assertCount(1, $searchResults);
        $this->assertEquals('Find this task', $searchResults->first()->title);
    }

    public function test_scope_overdue_returns_overdue_tasks(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->subDays(5),
            'status' => 'pending',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);

        $overdueTasks = Task::forUser($user->id)->overdue()->get();

        $this->assertCount(1, $overdueTasks);
    }

    public function test_due_date_is_cast_to_date(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2025-12-31',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $task->due_date);
    }
}
