<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_get_all_tasks(): void
    {
        Sanctum::actingAs($this->user);

        Task::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_task_via_api(): void
    {
        Sanctum::actingAs($this->user);

        $taskData = [
            'title' => 'API Test Task',
            'description' => 'Created via API',
            'category' => 'Testing',
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'category',
                    'priority',
                    'status',
                    'due_date',
                    'user',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('tasks', ['title' => 'API Test Task']);
    }

    public function test_can_show_single_task(): void
    {
        Sanctum::actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                ],
            ]);
    }

    public function test_can_update_task_via_api(): void
    {
        Sanctum::actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated via API',
            'description' => $task->description,
            'category' => $task->category,
            'priority' => 'urgent',
            'status' => 'in_progress',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated via API',
                    'priority' => 'urgent',
                ],
            ]);
    }

    public function test_can_delete_task_via_api(): void
    {
        Sanctum::actingAs($this->user);

        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_unauthenticated_user_cannot_access_api(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }

    public function test_user_cannot_access_other_users_task_via_api(): void
    {
        Sanctum::actingAs($this->user);

        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    public function test_api_returns_paginated_results(): void
    {
        Sanctum::actingAs($this->user);

        Task::factory(20)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_api_can_filter_tasks(): void
    {
        Sanctum::actingAs($this->user);

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

        $response = $this->getJson('/api/tasks?status=pending');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
