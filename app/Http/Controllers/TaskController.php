<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index($id)
    {
        $project = Project::with('tasks')->findOrFail($id);
        $members = User::role('Team Member')->get();

        return Inertia::render('Admin/Task/Create', [
            'project' => $project,
            'members' => $members
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $task = $project->tasks()->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'assignee_id' => $request->assignee,
            'dependency_id' => $request->dependency,
        ]);

        $assignee = User::find($task->assignee_id);
        if ($assignee) {
            $assignee->notify(new TaskAssignedNotification($task));
        }

        return redirect()->route('project.tasks', $project->id)->with('flash', [
            'message' => 'Task created successfully!'
        ]);
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'assignee_id' => $request->assignee,
            'dependency_id' => $request->dependency,
        ]);

        return redirect()->route('project.tasks', $project->id)->with('flash', [
            'message' => 'Task updated successfully!'
        ]);
    }
}
