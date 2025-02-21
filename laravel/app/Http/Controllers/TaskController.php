<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $tasks = Task::all();
        return response($tasks, 200);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'in:' . implode(',', [Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED])
        ]);

        $task = Task::create($request->all());
        return response($task, 201);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task): Response
    {
        $request->validate([
            'status' => 'in:' . implode(',', [Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS, Task::STATUS_COMPLETED])
        ]);

        $task->update($request->all());
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
        $task->delete();
        return response()->noContent();
    }

    /**
     * Display a listing of the completed tasks created in the last 7 days.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedTasks(): Response
    {
        $completedTasks = Task::where('status', Task::STATUS_COMPLETED)
                              ->where('created_at', '>=', now()->subDays(7))
                              ->get();
        return response($completedTasks, 200);
    }
}