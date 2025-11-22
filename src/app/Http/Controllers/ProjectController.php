<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['tasks', 'user'])->orderBy('created_at', 'DESC')->paginate(20);

        return Inertia::render('Admin/Project/Index', [
            'projects' => $projects,
            'role' => auth()->user()->roles->pluck('name'),
            'permissions' => auth()->user()->getAllPermissions()->pluck('name'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Project/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));

        return redirect()->route('project.tasks', $project->id)->with('flash', [
            'message' => 'Project created successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $project = Project::with('tasks.assignee')->findOrFail($id);

        return Inertia::render('Admin/Project/View', [
            'project' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $members = User::role('Team Member')->get();
        $project = Project::with('tasks.assignee')->findOrFail($id);

        return Inertia::render('Admin/Project/Edit', [
            'project' => $project,
            'members' => $members
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->route('project.tasks', $project->id)->with('flash', [
            'message' => 'Project updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        DB::beginTransaction();

        try {
            $project->tasks()->delete();

            $project->delete();

            DB::commit();

            return redirect()->route('projects.index')->with('flash', [
                'message' => 'Project deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('flash', [
                'error' => 'Failed to delete project. Please try again.'
            ]);
        }
    }
}
