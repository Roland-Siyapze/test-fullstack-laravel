<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_tasks_index(): void
    {
        $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page->component('Tasks/Index'));
    }

    public function test_user_can_create_task(): void
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'category' => 'Work',
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ];

        $this->actingAs($this->user)
            ->post(route('tasks.store'), $taskData)
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_task_show_page(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('tasks.show', $task))
            ->assertStatus(200)
            ->assertInertia(
                fn ($page) => $page
                ->component('Tasks/Show')
                ->has('task')
            );
    }

    public function test_user_can_update_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'category' => 'Personal',
            'priority' => 'low',
            'status' => 'completed',
            'due_date' => now()->addDays(10)->format('Y-m-d'),
        ];

        $this->actingAs($this->user)
            ->put(route('tasks.update', $task), $updateData)
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
        ]);
    }

    public function test_user_can_delete_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_view_other_users_task(): void
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->get(route('tasks.show', $task))
            ->assertStatus(403);
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->put(route('tasks.update', $task), ['title' => 'Hacked'])
            ->assertStatus(403);
    }

    public function test_user_cannot_delete_other_users_task(): void
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task))
            ->assertStatus(403);
    }

    public function test_task_validation_works(): void
    {
        $this->actingAs($this->user)
            ->post(route('tasks.store'), [])
            ->assertSessionHasErrors(['title', 'priority', 'status']);
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
        ]);

        $this->actingAs($this->user)
            ->get(route('tasks.index', ['status' => 'pending']))
            ->assertStatus(200);
    }
}
