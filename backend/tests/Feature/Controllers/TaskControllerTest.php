<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_tasks()
    {
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'status', 'created_at', 'updated_at']
                ],
                'meta', 'links'
            ]);
    }

    public function test_it_creates_a_task()
    {
        $data = ['name' => 'Test Task', 'status' => 'pending'];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Test Task');
        
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_it_updates_a_task()
    {
        $task = Task::factory()->create();

        $data = ['name' => 'Updated Task'];

        $response = $this->patchJson("/api/tasks/{$task->id}", $data);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Task');

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'name' => 'Updated Task']);
    }


    public function test_it_deletes_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertOk();

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
