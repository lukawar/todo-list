<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_fillable_attributes()
    {
        $task = new Task();

        $this->assertEquals(
            ['name', 'description', 'status'],
            $task->getFillable()
        );
    }

    public function test_it_uses_soft_deletes()
    {
        $this->assertTrue(
            in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(Task::class))
        );
    }

    public function test_it_can_be_soft_deleted()
    {
        $task = Task::factory()->create();

        $task->delete();

        $this->assertSoftDeleted($task);
    }

    public function test_it_can_be_restored_after_soft_delete()
    {
        $task = Task::factory()->create();

        $task->delete();
        $task->restore();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }
}
