<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getTasks(Request $request)
    {
        $tasks = auth()->user()->tasks;
        return response()->json(
            $tasks
        );
    }

    public function getTask(Request $request, $id)
    {
        $task = Task::find($id);
        return response()->json(
            $task
        );
    }

    public function createTask(Request $request)
    {
        $this->validate($request, [
            'task' => 'required'
        ]);
        $task = new Task();
        $task->task = $request->task;

        if (auth()->user()->tasks()->save($task))
            return response()->json([
                'success' => true,
                'data' => $task->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Task not added'
            ], 500);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::find($id);
        $task->task = $request->input('task');
        $task->update();
        return response()->json([
            'success' => true
        ]);
        // $updated = $task->fill($request->all())->save();

        // if ($updated)
        //     return response()->json([
        //         'success' => true
        //     ]);
        // else
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Task can not be updated.'
        //     ], 500);
    }

    public function deleteTask(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.'
            ], 404);
        }

        if ($task->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Task can not be deleted'
            ], 500);
        }
    }

    public function getActiveTasks(Request $request)
    {
        $active_tasks = auth()->user()->tasks->where('is_active', true);

        return response()->json(
            $active_tasks
        );
    }

    public function getCompleteTasks(Request $request)
    {
        $complete_tasks = auth()->user()->tasks->where('complete', true);

        return response()->json(
            $complete_tasks
        );
    }

    public function makeComplete(Request $request, $id)
    {
        $task = Task::find($id);
        $task->complete = true;
        $task->update();
        return response()->json(
            $task
        );
    }

    public function makeIncomplete(Request $request, $id)
    {
        $task = Task::find($id);
        $task->complete = false;
        $task->update();
        return response()->json(
            $task
        );
    }

    public function archive(Request $request)
    {
        $user_id = auth()->user()->id;
        $updates = Task::where('user_id', $user_id)
        ->where('complete', 1)
        ->update(['is_active' => 0]);

        return response()->json(
            $updates
        );
    }
}
