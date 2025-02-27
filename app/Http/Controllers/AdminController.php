<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $projects = Project::withCount(['tasks as total_tasks'])
            ->withCount(['tasks as completed_tasks' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function ($project) {
                $progress = $project->total_tasks > 0
                    ? round(($project->completed_tasks / $project->total_tasks) * 100, 2)
                    : 0;

                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'total_tasks' => $project->total_tasks,
                    'completed_tasks' => $project->completed_tasks,
                    'progress' => $progress,
                ];
            });

        $tasks = Task::with('project')
            ->get()
            ->map(function ($task) {
                $isOverdue = $task->status->value !== 'completed' && Carbon::parse($task->end_date)->isPast();

                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'status' => $task->status,
                    'start_date' => $task->start_date,
                    'end_date' => $task->end_date,
                    'project_name' => $task->project->name,
                    'is_overdue' => $isOverdue,
                ];
            });

        return Inertia::render('Dashboard', [
            'projects' => $projects,
            'tasks' => $tasks,
        ]);
    }
}
