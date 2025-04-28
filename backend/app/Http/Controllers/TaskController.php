<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(public TaskService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     * 
     * @return AnonymousResourceCollection
     */
    public function index() :AnonymousResourceCollection
    {
        $tasks = $this->service->getAllTasks();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param StoreTaskRequest $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request) :TaskResource
    {
        $validated = $request->validated();

        $task = $this->service->createTask($validated);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     * 
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task) :TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(UpdateTaskRequest $request, Task $task) :TaskResource
    {
        $validated = $request->validated();

        $task = $this->service->updateTask($task, $validated);

        return new TaskResource($task);
    }

    /**
     * Mark the specified resource as complete.
     * 
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function complete(UpdateTaskRequest $request, Task $task) :TaskResource
    {
        $validated = $request->validated();

        $task = $this->service->updateTask($task, $validated);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Task $task
     * @return AnonymousResourceCollection
     */
    public function destroy(Task $task) :AnonymousResourceCollection
    {
        $task->delete();

        $tasks = $this->service->getAllTasks();

        return TaskResource::collection($tasks);
    }
}
