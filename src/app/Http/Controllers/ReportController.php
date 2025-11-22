<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generateReport()
    {
        $projects = Project::with(['tasks' => function ($query) {
                $query->select('id', 'project_id', 'status', 'end_date');
            }])
            ->get()
            ->map(function ($project) {
                // Count tasks by status
                $completedTasks = $project->tasks->where('status', 'completed')->count();
                $pendingTasks = $project->tasks->where('status', 'pending')->count();
                $inProgressTasks = $project->tasks->where('status', 'in_progress')->count();
                $overdueTasks = $project->tasks
                    ->where('end_date', '<', now())
                    ->where('status', '!=', 'completed')
                    ->count();

                return [
                    'name' => $project->name,
                    'pending_tasks' => $pendingTasks,
                    'in_progress_tasks' => $inProgressTasks,
                    'completed_tasks' => $completedTasks,
                    'overdue_tasks' => $overdueTasks,
                ];
            });

        $pdf = Pdf::loadView('reports.project-status', [
            'projects' => $projects,
        ]);

        return $pdf->download('project-status-report.pdf');
    }
}
