<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    // public function index()
    // {
    //     $tasks = Task::whereNull('isCompletedTask')
    //     ->orWhere('isCompletedTask', false)
    //     ->get();

    // return response()->json($tasks);
    // }

    public function getUserTask($userId)
    {
        $tasks = Task::where('user_id', $userId)
        ->whereNull('isCompletedTask')
        ->orWhere('isCompletedTask', false)
        ->get();

        return response()->json($tasks);
    }
    public function store(TaskRequest $request)
    {
        try {
            $tas = Task::updateOrCreate(
                ['id' => $request['task_id']],
                [
                    'user_id' => $request['user_id'],
                    'taskInfo' => $request['taskInfo'],
                    'taskDescription' => $request['taskDescription'],
                    'date' => $request['date'],
                ]
            );
            if ($tas->wasRecentlyCreated) {
                return response()->json([
                    'status' => "Created",
                    'message' => "Task Successfully Created"
                ]);
            } else {
                return response()->json([
                    'status' => "Updated",
                    'message' => "Task Successfully Updated"
                ]);
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function show(Task $task)
    {
        $task = Task::where('id', $task->id)
            ->first();

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function update($taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->isCompletedTask = true;
        $task->save();

        return response()->json(['message' => 'Task status updated successfully']);
    }

    public function getCompletedTask($userId)
    {
        $completedTask = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('isCompletedTask', true)
            ->whereNull('deleted_at')
            ->get();

        return response()->json(['completedTask' => $completedTask]);
    }
}
