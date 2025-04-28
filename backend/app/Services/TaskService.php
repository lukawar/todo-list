<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Contracts\Pagination\Paginator;

class TaskService
{
    public function getAllTasks(): Paginator
    {
        $tasks = Task::paginate(10);

        return $tasks;
    }
    
    public function createTask($data)
    {
        $task = Task::create($data);

        return $task;
    }

    public function updateTask($task, $data)
    {
        $task->update($data);

        return $task;
    }
}