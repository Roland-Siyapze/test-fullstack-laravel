<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Tasks\TaskList;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_component_renders_successfully(): void
    {
        $this->actingAs($this->user);

        Livewire::test(TaskList::class)
            ->assertStatus(200);
    }

    public function test_can_search_tasks(): void
    {
        $this->actingAs($this->user);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Find Me',
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Not This One',
        ]);

        Livewire::test(TaskList::class)
            ->set('search', 'Find Me')
            ->assertSee('Find Me')
            ->assertDontSee('Not This One');
    }

    public function test_can_filter_by_status(): void
    {
        $this->actingAs($this->user);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'title' => 'Pending Task',
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
            'title' => 'Completed Task',
        ]);

        Livewire::test(TaskList::class)
            ->set('statusFilter', 'pending')
            ->assertSee('Pending Task')
            ->assertDontSee('Completed Task');
    }

    public function test_can_filter_by_priority(): void
    {
        $this->actingAs($this->user);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'priority' => 'urgent',
            'title' => 'Urgent Task',
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'priority' => 'low',
            'title' => 'Low Priority Task',
        ]);

        Livewire::test(TaskList::class)
            ->set('priorityFilter', 'urgent')
            ->assertSee('Urgent Task')
            ->assertDontSee('Low Priority Task');
    }

    public function test_can_toggle_task_status(): void
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        Livewire::test(TaskList::class)
            ->call('toggleStatus', $task->id);

        $this->assertEquals('completed', $task->fresh()->status);
    }

    public function test_can_delete_task(): void
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        Livewire::test(TaskList::class)
            ->call('deleteTask', $task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_clear_filters(): void
    {
        $this->actingAs($this->user);

        Livewire::test(TaskList::class)
            ->set('search', 'test')
            ->set('statusFilter', 'pending')
            ->set('priorityFilter', 'high')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('statusFilter', '')
            ->assertSet('priorityFilter', '');
    }

    public function test_pagination_works(): void
    {
        $this->actingAs($this->user);

        Task::factory(15)->create(['user_id' => $this->user->id]);

        Livewire::test(TaskList::class)
            ->assertViewHas('tasks', function ($tasks) {
                return $tasks->count() === 10;
            });
    }
}
