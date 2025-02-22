<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $tasks = $this->taskService->getAllTasks();
        return response($tasks, 200);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request): Response
    {
        $task = $this->taskService->createTask($request->validated());
        return response($task, 201);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task): Response
    {
        $task = $this->taskService->updateTask($task, $request->validated());
        return response($task, 200);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task): Response
    {
        $this->taskService->deleteTask($task);
        return response()->noContent();
    }

    /**
     * Display a listing of the completed tasks created in the last 7 days.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedTasks(): Response
    {
        $completedTasks = $this->taskService->getCompletedTasks();
        return response($completedTasks, 200);
    }
}