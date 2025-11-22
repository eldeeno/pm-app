<?php

namespace App\Http\Controllers\Team;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project'])
            ->where('assignee_id', Auth::user()->id)->paginate(20);

        return Inertia::render('Task/Index', [
            'tasks' => $tasks
        ]);
    }

    public function show($id)
    {
        $task = Task::with(['project'])
            ->where('assignee_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('Task/Show', [
            'task' => $task
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('assignee_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:in_progress,completed,overdue',
        ]);

        if ($task->dependency_id) {
            $dependency = Task::find($task->dependency_id);

            if ($dependency && $dependency->status->value !== 'completed') {
                return redirect()->back()->with('flash', [
                    'error' => 'This task has a dependency that must be completed before it can be started.',
                ]);
            }
        }

        $task->status = $request->status;
        $task->save();

        return redirect()->route('task.show', $task->id)->with('flash', [
            'message' => 'Task updated successfully!'
        ]);
    }
}
