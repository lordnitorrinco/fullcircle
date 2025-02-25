<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TaskService
{
    public function getAllTasks(): Collection
    {
        return Task::all();
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data): Task | JsonResponse
    {
        try {
            $task->update($data);
            return $task;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the task'], 500);
        }
    }

    public function deleteTask(Task $task): Task | JsonResponse
    {
        try {
            $taskCopy = $task->replicate();
            $task->delete();
            return $taskCopy;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the task'], 500);
        }
    }

    public function getCompletedTasks(): Collection
    {
        return Task::where('status', Task::STATUS_COMPLETED)
                   ->where('created_at', '>=', now()->subDays(7))
                   ->get();
    }
}